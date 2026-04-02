<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\Updates;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

#[UpgradeWizard('nsBlogTagTableUidMigrationWizard')]
final class TagTableUidMigrationWizard implements UpgradeWizardInterface
{
    public function getTitle(): string
    {
        return 'Fix tx_blog_domain_model_tag uid primary key';
    }

    public function getDescription(): string
    {
        return 'Adds missing uid auto_increment primary key to tx_blog_domain_model_tag for installations migrated from t3g/blog.';
    }

    public function updateNecessary(): bool
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_blog_domain_model_tag');
        $columns = $connection->createSchemaManager()->listTableColumns('tx_blog_domain_model_tag');
        return !isset($columns['uid']);
    }

    public function executeUpdate(): bool
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_blog_domain_model_tag');
        $connection->executeStatement(
            'ALTER TABLE tx_blog_domain_model_tag ADD uid INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST'
        );
        return true;
    }

    public function getPrerequisites(): array
    {
        return [];
    }
}

