<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\Domain\Repository;

use NITSAN\NsBlog\Constants;
use NITSAN\NsBlog\Domain\Model\Author;
use NITSAN\NsBlog\Domain\Model\Category;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class PostRepository extends Repository
{
    /**
     * @param int[] $storagePageIds
     */
    public function findAllWithLimit(int $limit, array $storagePageIds = []): QueryResultInterface
    {
        $query = $this->createQuery();
        $this->applyStorageOverrideWhenProvided($query, $storagePageIds);
        $constraints = [$query->equals('doktype', Constants::DOKTYPE_BLOG_POST)];
        if ($storagePageIds !== []) {
            $constraints[] = $query->in('pid', $storagePageIds);
        }
        $query->matching($query->logicalAnd(...$constraints));
        $query->setOrderings([
            'publishDate' => QueryInterface::ORDER_DESCENDING,
            'uid' => QueryInterface::ORDER_DESCENDING,
        ]);
        $query->setLimit($limit);
        return $query->execute();
    }

    /**
     * @param int[] $storagePageIds
     */
    public function findAllBlogPosts(array $storagePageIds = []): QueryResultInterface
    {
        $query = $this->createQuery();
        $this->applyStorageOverrideWhenProvided($query, $storagePageIds);
        $constraints = [$query->equals('doktype', Constants::DOKTYPE_BLOG_POST)];
        if ($storagePageIds !== []) {
            $constraints[] = $query->in('pid', $storagePageIds);
        }
        $query->matching($query->logicalAnd(...$constraints));
        $query->setOrderings([
            'publishDate' => QueryInterface::ORDER_DESCENDING,
            'uid' => QueryInterface::ORDER_DESCENDING,
        ]);
        return $query->execute();
    }

    /**
     * @param int[] $storagePageIds
     */
    public function findAllByCategory(Category $category, array $storagePageIds = []): QueryResultInterface
    {
        $query = $this->createQuery();
        $this->applyStorageOverrideWhenProvided($query, $storagePageIds);
        $constraints = [
            $query->equals('doktype', Constants::DOKTYPE_BLOG_POST),
            $query->contains('categories', $category),
        ];
        if ($storagePageIds !== []) {
            $constraints[] = $query->in('pid', $storagePageIds);
        }
        $query->matching(
            $query->logicalAnd(...$constraints)
        );
        $query->setOrderings([
            'publishDate' => QueryInterface::ORDER_DESCENDING,
            'uid' => QueryInterface::ORDER_DESCENDING,
        ]);
        return $query->execute();
    }

    /**
     * @param int[] $storagePageIds
     */
    public function findAllByAuthor(Author $author, array $storagePageIds = []): QueryResultInterface
    {
        $query = $this->createQuery();
        $this->applyStorageOverrideWhenProvided($query, $storagePageIds);
        $constraints = [
            $query->equals('doktype', Constants::DOKTYPE_BLOG_POST),
            $query->contains('authors', $author),
        ];
        if ($storagePageIds !== []) {
            $constraints[] = $query->in('pid', $storagePageIds);
        }
        $query->matching(
            $query->logicalAnd(...$constraints)
        );
        $query->setOrderings([
            'publishDate' => QueryInterface::ORDER_DESCENDING,
            'uid' => QueryInterface::ORDER_DESCENDING,
        ]);
        return $query->execute();
    }

    /**
     * @param int[] $storagePageIds
     */
    private function applyStorageOverrideWhenProvided(QueryInterface $query, array $storagePageIds): void
    {
        // Always disable implicit Extbase storagePid filtering.
        // We apply explicit PID constraints per query only when a starting point is provided.
        $query->getQuerySettings()->setRespectStoragePage(false);
    }
}
