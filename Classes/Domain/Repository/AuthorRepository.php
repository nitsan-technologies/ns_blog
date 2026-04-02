<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class AuthorRepository extends Repository
{
    /**
     * @param int[] $storagePageIds
     */
    public function findAll(array $storagePageIds = []): QueryResultInterface
    {
        $query = $this->createQuery();
        $this->applyStorageOverrideWhenProvided($query, $storagePageIds);
        if ($storagePageIds !== []) {
            $query->matching($query->in('pid', $storagePageIds));
        }
        return $query->execute();
    }

    /**
     * @param int[] $storagePageIds
     */
    private function applyStorageOverrideWhenProvided(QueryInterface $query, array $storagePageIds): void
    {
        if ($storagePageIds === []) {
            return;
        }
        $query->getQuerySettings()->setRespectStoragePage(false);
    }
}
