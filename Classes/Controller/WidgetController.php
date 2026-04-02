<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\Controller;

use NITSAN\NsBlog\Domain\Repository\CategoryRepository;
use NITSAN\NsBlog\Domain\Repository\PostRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

final class WidgetController extends ActionController
{
    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly CategoryRepository $categoryRepository
    ) {
    }

    public function recentPostsAction(): ResponseInterface
    {
        $limit = (int)($this->settings['widgets']['recentposts']['limit'] ?? 5);
        $storagePageIds = $this->resolveStoragePageIdsFromContentElement();
        $posts = $limit > 0
            ? $this->postRepository->findAllWithLimit($limit, $storagePageIds)
            : $this->postRepository->findAllBlogPosts($storagePageIds);
        $this->view->assign('posts', $posts);
        return $this->htmlResponse();
    }

    public function categoriesAction(): ResponseInterface
    {
        $categories = $this->categoryRepository->findDistinctBlogCategoriesWithCount($this->resolveStoragePageIdsFromContentElement());
        $this->view->assign('categories', $categories);
        return $this->htmlResponse();
    }

    /**
     * @return int[]
     */
    private function resolveStoragePageIdsFromContentElement(): array
    {
        $contentObject = $this->request->getAttribute('currentContentObject');
        $data = is_object($contentObject) ? (array)($contentObject->data ?? []) : [];
        $basePageIds = GeneralUtility::intExplode(',', (string)($data['pages'] ?? ''), true);
        $basePageIds = array_values(array_filter($basePageIds, static fn(int $pid): bool => $pid > 0));
        if ($basePageIds === []) {
            return [];
        }

        $recursiveDepth = max(0, (int)($data['recursive'] ?? 0));
        if ($recursiveDepth === 0) {
            return $basePageIds;
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        $allPageIds = array_fill_keys($basePageIds, true);
        $currentLevelIds = $basePageIds;

        for ($level = 0; $level < $recursiveDepth; $level++) {
            if ($currentLevelIds === []) {
                break;
            }
            $rows = $queryBuilder
                ->select('uid')
                ->from('pages')
                ->where(
                    $queryBuilder->expr()->in('pid', $queryBuilder->createNamedParameter($currentLevelIds, \TYPO3\CMS\Core\Database\Connection::PARAM_INT_ARRAY)),
                    $queryBuilder->expr()->eq('deleted', $queryBuilder->createNamedParameter(0)),
                    $queryBuilder->expr()->eq('hidden', $queryBuilder->createNamedParameter(0))
                )
                ->executeQuery()
                ->fetchAllAssociative();

            $nextLevelIds = [];
            foreach ($rows as $row) {
                $uid = (int)($row['uid'] ?? 0);
                if ($uid > 0 && !isset($allPageIds[$uid])) {
                    $allPageIds[$uid] = true;
                    $nextLevelIds[] = $uid;
                }
            }
            $currentLevelIds = $nextLevelIds;
        }

        return array_map(static fn(string $uid): int => (int)$uid, array_keys($allPageIds));
    }
}
