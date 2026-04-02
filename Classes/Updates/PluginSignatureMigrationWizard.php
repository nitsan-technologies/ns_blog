<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\Updates;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

#[UpgradeWizard('nsBlogPluginSignatureMigrationWizard')]
final class PluginSignatureMigrationWizard implements UpgradeWizardInterface
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
        // Widgets were replaced by a single Sidebar plugin.
        'blog_recentpostswidget' => 'nsblog_sidebar',
        'blog_categorywidget' => 'nsblog_sidebar',
        // Already-migrated-but-broken widget types.
        'nsblog_recentpostswidget' => 'nsblog_sidebar',
        'nsblog_categorywidget' => 'nsblog_sidebar',
    ];

    public function getTitle(): string
    {
        return 'Migrate blog plugin signatures to ns_blog';
    }

    public function getDescription(): string
    {
        return 'Converts old blog CTypes/list types and plugin parameter namespace to ns_blog signatures.';
    }

    public function updateNecessary(): bool
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content');
        $qb = $connection->createQueryBuilder();
        $count = (int)$qb->count('uid')
            ->from('tt_content')
            ->where(
                $qb->expr()->or(
                    $qb->expr()->like('CType', $qb->createNamedParameter('blog\\_%')),
                    $qb->expr()->like('list_type', $qb->createNamedParameter('blog\\_%')),
                    $qb->expr()->like('pi_flexform', $qb->createNamedParameter('%tx_blog_%')),
                    // In case the DB was exported/imported between TYPO3 versions after a partial migration,
                    // make sure we still fix already-migrated widget content elements.
                    $qb->expr()->eq('CType', $qb->createNamedParameter('nsblog_recentpostswidget')),
                    $qb->expr()->eq('CType', $qb->createNamedParameter('nsblog_categorywidget')),
                    $qb->expr()->eq('list_type', $qb->createNamedParameter('nsblog_recentpostswidget')),
                    $qb->expr()->eq('list_type', $qb->createNamedParameter('nsblog_categorywidget')),
                    // Legacy extbase-list records migrated from v13 can still have CType=list.
                    $qb->expr()->and(
                        $qb->expr()->eq('CType', $qb->createNamedParameter('list')),
                        $qb->expr()->or(
                            $qb->expr()->like('list_type', $qb->createNamedParameter('blog\\_%')),
                            $qb->expr()->like('list_type', $qb->createNamedParameter('nsblog\\_%'))
                        )
                    )
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
                // Normalize records where list_type already points to nsblog signature.
                $targetType = $listType;
            }

            if ($targetType !== null && $targetType !== '') {
                $update['CType'] = $targetType;
                // ns_blog uses dedicated CType registrations; list_type is legacy here.
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
