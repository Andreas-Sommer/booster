<?php
declare(strict_types=1);

namespace Belsignum\Booster\Hook\DataHandler;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Validates and pretty-prints the page field "tx_booster_custom_jsonld" on save.
 *
 * Behaviour:
 * - Empty value: accepted as-is.
 * - Valid JSON: re-encoded as pretty-printed JSON before saving (canonical, unminified form).
 * - Invalid JSON: change is discarded; previous value is restored and an error FlashMessage is emitted.
 * - Soft hint: if @context/@type is missing in the top-level (or in any array element), a warning is shown
 *   but the value is still saved.
 */
final class CustomJsonLdValidator
{
    private const FIELD = 'tx_booster_custom_jsonld';
    private const TABLE = 'pages';
    private const JSON_PRETTY_FLAGS = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT;

    /**
     * Per-request guard to prevent duplicate FlashMessages when DataHandler
     * re-enters preProcessFieldArray for the same record (e.g. due to
     * allowLanguageSynchronization or translation handling).
     *
     * @var array<string, true>
     */
    private static array $emittedMessages = [];

    /**
     * @param array<string, mixed> $fieldArray
     */
    public function processDatamap_preProcessFieldArray(
        array &$fieldArray,
        string $table,
        int|string $id,
        DataHandler $dataHandler
    ): void {
        if ($table !== self::TABLE || !array_key_exists(self::FIELD, $fieldArray)) {
            return;
        }

        $raw = is_string($fieldArray[self::FIELD]) ? trim($fieldArray[self::FIELD]) : '';

        if ($raw === '') {
            $fieldArray[self::FIELD] = '';
            return;
        }

        $decoded = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->addFlashMessageOnce(
                'invalid',
                sprintf(
                    'Custom JSON-LD nicht gespeichert: ungültiges JSON (%s). Der vorherige Wert bleibt erhalten.',
                    json_last_error_msg()
                ),
                'Booster — Custom JSON-LD',
                ContextualFeedbackSeverity::ERROR
            );
            $previous = $this->loadPreviousValue($id);
            if ($previous === null) {
                unset($fieldArray[self::FIELD]);
            } else {
                $fieldArray[self::FIELD] = $previous;
            }
            return;
        }

        if (!$this->hasContextAndType($decoded)) {
            $this->addFlashMessageOnce(
                'missing-context-type',
                'Hinweis: Im Custom JSON-LD fehlt mindestens ein @context oder @type. Die Eingabe wurde gespeichert, '
                . 'ist für Suchmaschinen aber wahrscheinlich nicht verwertbar.',
                'Booster — Custom JSON-LD',
                ContextualFeedbackSeverity::WARNING
            );
        }

        $pretty = json_encode($decoded, self::JSON_PRETTY_FLAGS);
        $fieldArray[self::FIELD] = $pretty === false ? $raw : $pretty;
    }

    private function loadPreviousValue(int|string $id): ?string
    {
        if (!is_int($id) && !ctype_digit((string)$id)) {
            return null;
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable(self::TABLE);
        $queryBuilder->getRestrictions()->removeAll();

        $value = $queryBuilder
            ->select(self::FIELD)
            ->from(self::TABLE)
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter((int)$id, Connection::PARAM_INT)
                )
            )
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchOne();

        return is_string($value) ? $value : null;
    }

    /**
     * Soft check: returns true if the decoded document contains both at least
     * one "@context" and at least one "@type" anywhere in the structure. This
     * accepts JSON-LD `@graph` containers where types are nested inside graph
     * nodes rather than at the root.
     */
    private function hasContextAndType(mixed $decoded): bool
    {
        if (!is_array($decoded)) {
            return false;
        }
        return $this->containsKey($decoded, '@context') && $this->containsKey($decoded, '@type');
    }

    /**
     * @param array<int|string, mixed> $data
     */
    private function containsKey(array $data, string $key): bool
    {
        if (array_key_exists($key, $data)) {
            return true;
        }
        foreach ($data as $value) {
            if (is_array($value) && $this->containsKey($value, $key)) {
                return true;
            }
        }
        return false;
    }

    private function addFlashMessageOnce(
        string $dedupKey,
        string $message,
        string $title,
        ContextualFeedbackSeverity $severity
    ): void {
        if (isset(self::$emittedMessages[$dedupKey])) {
            return;
        }
        self::$emittedMessages[$dedupKey] = true;

        $flashMessage = GeneralUtility::makeInstance(
            FlashMessage::class,
            $message,
            $title,
            $severity,
            true
        );
        GeneralUtility::makeInstance(FlashMessageService::class)
            ->getMessageQueueByIdentifier()
            ->enqueue($flashMessage);
    }
}
