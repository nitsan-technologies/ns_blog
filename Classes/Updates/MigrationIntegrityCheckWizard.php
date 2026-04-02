<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\Updates;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

#[UpgradeWizard('nsBlogMigrationIntegrityCheckWizard')]
final class MigrationIntegrityCheckWizard implements UpgradeWizardInterface
{
    public function getTitle(): string
    {
        return 'Validate ns_blog migration integrity';
    }

    public function getDescription(): string
    {
        return 'Checks unresolved old blog plugin signatures and missing category/author slugs after migration.';
    }

    public function updateNecessary(): bool
    {
        return $this->hasUnresolvedPluginSignatures() || $this->hasMissingSlugs();
    }

    public function executeUpdate(): bool
    {
        // Read-only validation wizard: does not modify data.
        // Returning true allows the install tool flow to continue.
        return true;
    }

    public function getPrerequisites(): array
    {
        return [];
    }

    private function hasUnresolvedPluginSignatures(): bool
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content');
        $qb = $connection->createQueryBuilder();
        $count = (int)$qb->count('uid')
            ->from('tt_content')
            ->where(
                $qb->expr()->or(
                    $qb->expr()->like('CType', $qb->createNamedParameter('blog\\_%')),
                    $qb->expr()->like('list_type', $qb->createNamedParameter('blog\\_%')),
                    $qb->expr()->like('pi_flexform', $qb->createNamedParameter('%tx_blog_%'))
                )
            )
            ->executeQuery()
            ->fetchOne();

        return $count > 0;
    }

    private function hasMissingSlugs(): bool
    {
        $pool = GeneralUtility::makeInstance(ConnectionPool::class);

        $authorConnection = $pool->getConnectionForTable('tx_blog_domain_model_author');
        $authorQb = $authorConnection->createQueryBuilder();
        $authorMissing = (int)$authorQb->count('uid')
            ->from('tx_blog_domain_model_author')
            ->where($authorQb->expr()->eq('slug', $authorQb->createNamedParameter('')))
            ->executeQuery()
            ->fetchOne();

        $categoryConnection = $pool->getConnectionForTable('sys_category');
        $categoryQb = $categoryConnection->createQueryBuilder();
        $categoryMissing = (int)$categoryQb->count('uid')
            ->from('sys_category')
            ->where($categoryQb->expr()->eq('slug', $categoryQb->createNamedParameter('')))
            ->executeQuery()
            ->fetchOne();

        return ($authorMissing + $categoryMissing) > 0;
    }
}
