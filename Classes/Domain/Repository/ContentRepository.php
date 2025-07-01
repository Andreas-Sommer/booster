<?php
/**
 * Created by PhpStorm.
 * User: Andreas Sommer
 * Date: 21.11.2019
 * Time: 19:45
 */

namespace Belsignum\Booster\Domain\Repository;

use Belsignum\Booster\Domain\Model\Content;
use Doctrine\DBAL\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use TYPO3\CMS\Extbase\Persistence\Repository;

class ContentRepository extends Repository
{
    public function findByUid($uid): ?Content
    {
        $querySettings = $this->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
        return parent::findByUid($uid);
    }

    public function findInUids(array $uids): QueryResult
    {
        $query = $this->createQuery();
        $query->getQuerySettings()
            ->setRespectStoragePage(false);
        $query->matching(
            $query->in('uid', $uids)
        );
        return $query->execute();
    }

    public function getFaqsByPid(int $pid)
    {
        $uids = $this->getFaqUids($pid);
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_booster_domain_model_content');
        return $queryBuilder->select('*')
                ->from('tx_booster_domain_model_content')->where($queryBuilder->expr()->in(
                'uid',
                $queryBuilder->createNamedParameter($uids, Connection::PARAM_INT_ARRAY)
            ))->executeQuery()
            ->fetchAllAssociative();
    }

    public function getFaqUids(int $local_uid): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
        							  ->getQueryBuilderForTable('tx_booster_pages_content_mm');
        return $queryBuilder->select('uid_foreign')
            ->from('tx_booster_pages_content_mm')->where($queryBuilder->expr()->eq(
                'uid_local',
                $queryBuilder->createNamedParameter($local_uid, \PDO::PARAM_INT)
            ), $queryBuilder->expr()->eq(
                'fieldname',
                $queryBuilder->createNamedParameter('tx_booster_faq', \PDO::PARAM_STR)
            ))->executeQuery()
            ->fetchFirstColumn();
    }
}
