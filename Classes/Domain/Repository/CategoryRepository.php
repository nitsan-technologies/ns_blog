<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\Domain\Repository;

use NITSAN\NsBlog\Constants;
use NITSAN\NsBlog\Domain\Model\Category;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;

class CategoryRepository extends Repository
{
    public function findByUidWithoutStorage(int $uid): ?Category
    {
        if ($uid <= 0) {
            return null;
        }

        $query = $this->createQuery();
        $querySettings = $query->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $query->setQuerySettings($querySettings);
        $query->matching($query->equals('uid', $uid));

        $result = $query->execute()->getFirst();
        return $result instanceof Category ? $result : null;
    }

    /**
     * Return unique categories assigned to blog post pages with post counters.
     *
     * @param int[] $storagePageIds
     * @return array<int, array{uid:int,title:string,count:int}>
     */
    public function findDistinctBlogCategoriesWithCount(array $storagePageIds = []): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_category');

        $rows = $queryBuilder
            ->selectLiteral(
                'sys_category.uid AS uid',
                'sys_category.title AS title',
                'COUNT(DISTINCT pages.uid) AS count'
            )
            ->from('sys_category')
            ->innerJoin(
                'sys_category',
                'sys_category_record_mm',
                'mm',
                'mm.uid_local = sys_category.uid'
                . ' AND mm.tablenames = ' . $queryBuilder->createNamedParameter('pages')
                . ' AND mm.fieldname = ' . $queryBuilder->createNamedParameter('categories')
            )
            ->innerJoin(
                'mm',
                'pages',
                'pages',
                'pages.uid = mm.uid_foreign'
                . ' AND pages.doktype = ' . $queryBuilder->createNamedParameter(Constants::DOKTYPE_BLOG_POST)
                . ' AND pages.deleted = ' . $queryBuilder->createNamedParameter(0)
                . ' AND pages.hidden = ' . $queryBuilder->createNamedParameter(0)
            )
            ->where(
                $queryBuilder->expr()->eq('sys_category.deleted', $queryBuilder->createNamedParameter(0)),
                $queryBuilder->expr()->eq('sys_category.hidden', $queryBuilder->createNamedParameter(0)),
                $queryBuilder->expr()->in(
                    'sys_category.sys_language_uid',
                    $queryBuilder->createNamedParameter([-1, 0], \TYPO3\CMS\Core\Database\Connection::PARAM_INT_ARRAY)
                )
            );

        if ($storagePageIds === []) {
            return [];
        }
        $queryBuilder->andWhere(
            $queryBuilder->expr()->in(
                'sys_category.pid',
                array_map(
                    static fn(int $pid): int => (int)$pid,
                    array_values($storagePageIds)
                )
            )
        );

        $rows = $queryBuilder
            ->groupBy('sys_category.uid', 'sys_category.title')
            ->orderBy('sys_category.title', 'ASC')
            ->executeQuery()
            ->fetchAllAssociative();

        return array_map(
            static fn(array $row): array => [
                'uid' => (int)($row['uid'] ?? 0),
                'title' => (string)($row['title'] ?? ''),
                'count' => (int)($row['count'] ?? 0),
            ],
            $rows
        );
    }

    /**
     * Return unique categories linked to pages in the resolved storage scope.
     *
     * @param int[] $storagePageIds
     * @return array<int, array{uid:int,title:string,count:int}>
     */
    public function findDistinctCategoriesByPageScope(array $storagePageIds = []): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_category');

        $queryBuilder
            ->selectLiteral(
                'sys_category.uid AS uid',
                'sys_category.title AS title',
                'COUNT(DISTINCT pages.uid) AS count'
            )
            ->from('sys_category')
            ->innerJoin(
                'sys_category',
                'sys_category_record_mm',
                'mm',
                'mm.uid_local = sys_category.uid'
                . ' AND mm.tablenames = ' . $queryBuilder->createNamedParameter('pages')
                . ' AND mm.fieldname = ' . $queryBuilder->createNamedParameter('categories')
            )
            ->innerJoin(
                'mm',
                'pages',
                'pages',
                'pages.uid = mm.uid_foreign'
                . ' AND pages.deleted = ' . $queryBuilder->createNamedParameter(0)
                . ' AND pages.hidden = ' . $queryBuilder->createNamedParameter(0)
            )
            ->where(
                $queryBuilder->expr()->eq('sys_category.deleted', $queryBuilder->createNamedParameter(0)),
                $queryBuilder->expr()->eq('sys_category.hidden', $queryBuilder->createNamedParameter(0)),
                $queryBuilder->expr()->in(
                    'sys_category.sys_language_uid',
                    $queryBuilder->createNamedParameter([-1, 0], \TYPO3\CMS\Core\Database\Connection::PARAM_INT_ARRAY)
                )
            );

        if ($storagePageIds === []) {
            return [];
        }
        $queryBuilder->andWhere(
            $queryBuilder->expr()->in(
                'sys_category.pid',
                array_map(
                    static fn(int $pid): int => (int)$pid,
                    array_values($storagePageIds)
                )
            )
        );

        $rows = $queryBuilder
            ->groupBy('sys_category.uid', 'sys_category.title')
            ->orderBy('sys_category.title', 'ASC')
            ->executeQuery()
            ->fetchAllAssociative();

        return array_map(
            static fn(array $row): array => [
                'uid' => (int)($row['uid'] ?? 0),
                'title' => (string)($row['title'] ?? ''),
                'count' => (int)($row['count'] ?? 0),
            ],
            $rows
        );
    }
}
