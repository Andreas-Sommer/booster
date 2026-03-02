<?php
declare(strict_types=1);

namespace Belsignum\Booster\Command;

use PDO;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RepairStructuredDataLocalizationCommand extends Command
{
    private const TABLE_PAGES = 'pages';
    private const TABLE_CONTENT = 'tx_booster_domain_model_content';
    private const TABLE_MM = 'tx_booster_pages_content_mm';
    private const FAQ_FIELDNAME = 'tx_booster_faq';

    protected ConnectionPool $connectionPool;

    protected array $contentTranslationCache = [];
    protected array $parentPageCache = [];

    public function __construct(ConnectionPool $connectionPool, string $name = null)
    {
        $this->connectionPool = $connectionPool;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setDescription('Repairs localized booster structured-data relations on translated pages.')
            ->addOption('page', 'p', InputOption::VALUE_REQUIRED, 'Page uid (default or localized page).')
            ->addOption('language', 'l', InputOption::VALUE_REQUIRED, 'sys_language_uid filter.')
            ->addOption('dry-run', 'd', InputOption::VALUE_NONE, 'Do not write changes.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dryRun = (bool)$input->getOption('dry-run');
        $pageFilter = $input->getOption('page');
        $languageFilter = $input->getOption('language');

        $pageFilter = $pageFilter !== null ? (int)$pageFilter : null;
        $languageFilter = $languageFilter !== null ? (int)$languageFilter : null;

        $localizedPages = $this->getLocalizedPages($pageFilter, $languageFilter);
        if ($localizedPages === []) {
            $output->writeln('No localized pages matched the given filter.');
            return Command::SUCCESS;
        }

        $stats = [
            'pages_checked' => 0,
            'pages_changed' => 0,
            'product_fixed' => 0,
            'faq_inserted' => 0,
            'faq_converted' => 0,
            'faq_counter_fixed' => 0,
        ];

        foreach ($localizedPages as $localizedPage) {
            $stats['pages_checked']++;
            $pageUid = (int)$localizedPage['uid'];
            $languageUid = (int)$localizedPage['sys_language_uid'];
            $parentUid = (int)$localizedPage['l10n_parent'];

            $parentPage = $this->getParentPage($parentUid);
            if ($parentPage === null) {
                continue;
            }

            $pageChanged = false;

            // Product: pages.tx_booster_product is a direct reference, map default -> translated.
            $parentProductUid = (int)$parentPage['tx_booster_product'];
            $currentProductUid = (int)$localizedPage['tx_booster_product'];
            if ($parentProductUid > 0) {
                $translatedProductUid = $this->resolveTranslatedContentUid($parentProductUid, $languageUid);
                if ($translatedProductUid > 0 && $translatedProductUid !== $currentProductUid) {
                    $pageChanged = true;
                    $stats['product_fixed']++;
                    $output->writeln(
                        sprintf(
                            'Page %d (L=%d): product %d -> %d',
                            $pageUid,
                            $languageUid,
                            $currentProductUid,
                            $translatedProductUid
                        )
                    );

                    if (!$dryRun) {
                        $this->updatePageFields($pageUid, ['tx_booster_product' => $translatedProductUid]);
                    }
                }
            }

            // FAQ: MM relation from page to content, map each default relation -> translated relation.
            $parentFaqRows = $this->getFaqMmRows($parentUid);
            $localizedFaqRows = $this->getFaqMmRows($pageUid);
            $plannedFaqCount = count($localizedFaqRows);
            $localizedByForeignUid = [];
            foreach ($localizedFaqRows as $row) {
                $localizedByForeignUid[(int)$row['uid_foreign']] = $row;
            }

            if ($parentFaqRows !== []) {

                foreach ($parentFaqRows as $parentFaqRow) {
                    $sourceFaqUid = (int)$parentFaqRow['uid_foreign'];
                    if ($sourceFaqUid <= 0) {
                        continue;
                    }

                    $translatedFaqUid = $this->resolveTranslatedContentUid($sourceFaqUid, $languageUid);
                    if ($translatedFaqUid <= 0) {
                        continue;
                    }

                    if (isset($localizedByForeignUid[$translatedFaqUid])) {
                        continue;
                    }

                    $pageChanged = true;
                    if (isset($localizedByForeignUid[$sourceFaqUid])) {
                        $stats['faq_converted']++;
                        $output->writeln(
                            sprintf(
                                'Page %d (L=%d): FAQ relation converted %d -> %d',
                                $pageUid,
                                $languageUid,
                                $sourceFaqUid,
                                $translatedFaqUid
                            )
                        );

                        if (!$dryRun) {
                            $this->convertFaqMmRow($pageUid, $sourceFaqUid, $translatedFaqUid);
                        }
                        unset($localizedByForeignUid[$sourceFaqUid]);
                    } else {
                        $stats['faq_inserted']++;
                        $plannedFaqCount++;
                        $output->writeln(
                            sprintf(
                                'Page %d (L=%d): FAQ relation inserted %d',
                                $pageUid,
                                $languageUid,
                                $translatedFaqUid
                            )
                        );

                        if (!$dryRun) {
                            $this->insertFaqMmRow($pageUid, $translatedFaqUid, (int)$parentFaqRow['sorting']);
                        }
                    }

                    $localizedByForeignUid[$translatedFaqUid] = ['uid_foreign' => $translatedFaqUid];
                }
            }

            $currentFaqCounter = (int)$localizedPage['tx_booster_faqs'];
            $faqCount = $dryRun ? $plannedFaqCount : $this->getFaqCount($pageUid);
            if ($faqCount !== $currentFaqCounter) {
                $pageChanged = true;
                $stats['faq_counter_fixed']++;
                $output->writeln(
                    sprintf(
                        'Page %d (L=%d): tx_booster_faqs %d -> %d',
                        $pageUid,
                        $languageUid,
                        $currentFaqCounter,
                        $faqCount
                    )
                );
                if (!$dryRun) {
                    $this->updatePageFields($pageUid, ['tx_booster_faqs' => $faqCount]);
                }
            }

            if ($pageChanged) {
                $stats['pages_changed']++;
            }
        }

        $output->writeln(
            sprintf(
                'Done. pages_checked=%d, pages_changed=%d, product_fixed=%d, faq_inserted=%d, faq_converted=%d, faq_counter_fixed=%d, dry_run=%s',
                $stats['pages_checked'],
                $stats['pages_changed'],
                $stats['product_fixed'],
                $stats['faq_inserted'],
                $stats['faq_converted'],
                $stats['faq_counter_fixed'],
                $dryRun ? 'yes' : 'no'
            )
        );

        return Command::SUCCESS;
    }

    protected function getLocalizedPages(?int $pageFilter, ?int $languageFilter): array
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(self::TABLE_PAGES);
        $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));

        $constraints = [
            $queryBuilder->expr()->gt(
                'sys_language_uid',
                $queryBuilder->createNamedParameter(0, PDO::PARAM_INT)
            ),
            $queryBuilder->expr()->gt(
                'l10n_parent',
                $queryBuilder->createNamedParameter(0, PDO::PARAM_INT)
            ),
        ];

        if ($pageFilter !== null) {
            $constraints[] = $queryBuilder->expr()->orX(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($pageFilter, PDO::PARAM_INT)),
                $queryBuilder->expr()->eq('l10n_parent', $queryBuilder->createNamedParameter($pageFilter, PDO::PARAM_INT))
            );
        }
        if ($languageFilter !== null) {
            $constraints[] = $queryBuilder->expr()->eq(
                'sys_language_uid',
                $queryBuilder->createNamedParameter($languageFilter, PDO::PARAM_INT)
            );
        }

        return $queryBuilder
            ->select('uid', 'l10n_parent', 'sys_language_uid', 'tx_booster_product', 'tx_booster_faqs')
            ->from(self::TABLE_PAGES)
            ->where(...$constraints)
            ->orderBy('uid', 'ASC')
            ->execute()
            ->fetchAllAssociative();
    }

    protected function getParentPage(int $uid): ?array
    {
        if (array_key_exists($uid, $this->parentPageCache)) {
            return $this->parentPageCache[$uid];
        }

        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(self::TABLE_PAGES);
        $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));

        $row = $queryBuilder
            ->select('uid', 'tx_booster_product')
            ->from(self::TABLE_PAGES)
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, PDO::PARAM_INT))
            )
            ->execute()
            ->fetchAssociative();

        $this->parentPageCache[$uid] = $row ?: null;
        return $this->parentPageCache[$uid];
    }

    protected function resolveTranslatedContentUid(int $defaultUid, int $languageUid): int
    {
        if (!isset($this->contentTranslationCache[$defaultUid])) {
            $this->contentTranslationCache[$defaultUid] = [];
        }
        if (array_key_exists($languageUid, $this->contentTranslationCache[$defaultUid])) {
            return $this->contentTranslationCache[$defaultUid][$languageUid];
        }

        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(self::TABLE_CONTENT);
        $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));

        $row = $queryBuilder
            ->select('uid')
            ->from(self::TABLE_CONTENT)
            ->where(
                $queryBuilder->expr()->eq(
                    'l18n_parent',
                    $queryBuilder->createNamedParameter($defaultUid, PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'sys_language_uid',
                    $queryBuilder->createNamedParameter($languageUid, PDO::PARAM_INT)
                )
            )
            ->setMaxResults(1)
            ->execute()
            ->fetchAssociative();

        $translatedUid = isset($row['uid']) ? (int)$row['uid'] : 0;
        $this->contentTranslationCache[$defaultUid][$languageUid] = $translatedUid;
        return $translatedUid;
    }

    protected function getFaqMmRows(int $pageUid): array
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(self::TABLE_MM);
        return $queryBuilder
            ->select('uid_local', 'uid_foreign', 'sorting')
            ->from(self::TABLE_MM)
            ->where(
                $queryBuilder->expr()->eq(
                    'uid_local',
                    $queryBuilder->createNamedParameter($pageUid, PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'fieldname',
                    $queryBuilder->createNamedParameter(self::FAQ_FIELDNAME, PDO::PARAM_STR)
                )
            )
            ->orderBy('sorting', 'ASC')
            ->addOrderBy('uid_foreign', 'ASC')
            ->execute()
            ->fetchAllAssociative();
    }

    protected function convertFaqMmRow(int $pageUid, int $sourceFaqUid, int $translatedFaqUid): void
    {
        $this->connectionPool->getConnectionForTable(self::TABLE_MM)->update(
            self::TABLE_MM,
            ['uid_foreign' => $translatedFaqUid],
            [
                'uid_local' => $pageUid,
                'uid_foreign' => $sourceFaqUid,
                'fieldname' => self::FAQ_FIELDNAME,
            ]
        );
    }

    protected function insertFaqMmRow(int $pageUid, int $translatedFaqUid, int $sorting): void
    {
        $this->connectionPool->getConnectionForTable(self::TABLE_MM)->insert(
            self::TABLE_MM,
            [
                'uid_local' => $pageUid,
                'uid_foreign' => $translatedFaqUid,
                'fieldname' => self::FAQ_FIELDNAME,
                'sorting' => $sorting > 0 ? $sorting : 0,
                'sorting_foreign' => 0,
            ]
        );
    }

    protected function getFaqCount(int $pageUid): int
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(self::TABLE_MM);
        return (int)$queryBuilder
            ->count('uid_foreign')
            ->from(self::TABLE_MM)
            ->where(
                $queryBuilder->expr()->eq(
                    'uid_local',
                    $queryBuilder->createNamedParameter($pageUid, PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'fieldname',
                    $queryBuilder->createNamedParameter(self::FAQ_FIELDNAME, PDO::PARAM_STR)
                )
            )
            ->execute()
            ->fetchOne();
    }

    protected function updatePageFields(int $pageUid, array $values): void
    {
        $this->connectionPool->getConnectionForTable(self::TABLE_PAGES)->update(
            self::TABLE_PAGES,
            $values,
            ['uid' => $pageUid]
        );
    }
}
