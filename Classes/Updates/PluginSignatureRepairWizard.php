<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\Updates;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

#[UpgradeWizard('nsBlogPluginSignatureRepairWizard')]
final class PluginSignatureRepairWizard implements UpgradeWizardInterface
{
    /**
     * @var array<string, string>
     */
    private const SIGNATURE_MAP = [
        'blog_posts' => 'nsblog_posts',
        'blog_latestposts' => 'nsblog_latestposts',
        'blog_category' => 'nsblog_category',
        'blog_authorposts' => 'nsblog_authorposts',
        'blog_sidebar' => 'nsblog_sidebar',
        'blog_relatedposts' => 'nsblog_relatedposts',
        // Canonical ns_blog signatures (for CType=list + list_type=nsblog_* legacy rows).
        'nsblog_posts' => 'nsblog_posts',
        'nsblog_latestposts' => 'nsblog_latestposts',
        'nsblog_category' => 'nsblog_category',
        'nsblog_authorposts' => 'nsblog_authorposts',
        'nsblog_sidebar' => 'nsblog_sidebar',
        'nsblog_relatedposts' => 'nsblog_relatedposts',
        'blog_recentpostswidget' => 'nsblog_sidebar',
        'blog_categorywidget' => 'nsblog_sidebar',
        'nsblog_recentpostswidget' => 'nsblog_sidebar',
        'nsblog_categorywidget' => 'nsblog_sidebar',
    ];

    public function getTitle(): string
    {
        return 'Repair migrated blog plugin signatures for TYPO3 v14';
    }

    public function getDescription(): string
    {
        return 'Normalizes legacy blog/ns_blog list_type records to valid ns_blog CTypes after database import.';
    }

    public function updateNecessary(): bool
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content');
        $qb = $connection->createQueryBuilder();
        $count = (int)$qb->count('uid')
            ->from('tt_content')
            ->where(
                $qb->expr()->or(
                    $qb->expr()->eq('CType', $qb->createNamedParameter('list')),
                    $qb->expr()->like('CType', $qb->createNamedParameter('blog\\_%')),
                    $qb->expr()->eq('CType', $qb->createNamedParameter('nsblog_recentpostswidget')),
                    $qb->expr()->eq('CType', $qb->createNamedParameter('nsblog_categorywidget')),
                    $qb->expr()->like('list_type', $qb->createNamedParameter('blog\\_%')),
                    $qb->expr()->like('list_type', $qb->createNamedParameter('nsblog\\_%')),
                    $qb->expr()->like('pi_flexform', $qb->createNamedParameter('%tx_blog_%'))
                )
            )
            ->executeQuery()
            ->fetchOne();

        return $count > 0;
    }

    public function executeUpdate(): bool
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content');
        $rows = $connection->select(
            ['uid', 'CType', 'list_type', 'pi_flexform'],
            'tt_content',
            []
        )->fetchAllAssociative();

        foreach ($rows as $row) {
            $ctype = (string)($row['CType'] ?? '');
            $listType = (string)($row['list_type'] ?? '');
            $flexform = (string)($row['pi_flexform'] ?? '');
            $update = [];

            $targetType = self::SIGNATURE_MAP[$ctype] ?? null;
            if ($targetType === null && $ctype === 'list') {
                $targetType = self::SIGNATURE_MAP[$listType] ?? null;
            } elseif ($targetType === null && str_starts_with($listType, 'nsblog_')) {
                $targetType = $listType;
            }

            if ($targetType !== null && $targetType !== '') {
                $update['CType'] = $targetType;
                if ($listType !== '') {
                    $update['list_type'] = '';
                }
            }

            if ($flexform !== '' && str_contains($flexform, 'tx_blog_')) {
                $update['pi_flexform'] = str_replace('tx_blog_', 'tx_nsblog_', $flexform);
            }

            if ($update !== []) {
                $connection->update('tt_content', $update, ['uid' => (int)$row['uid']]);
            }
        }

        return true;
    }

    public function getPrerequisites(): array
    {
        return [];
    }
}
