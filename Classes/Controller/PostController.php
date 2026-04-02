<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\Controller;

use NITSAN\NsBlog\Domain\Model\Author;
use NITSAN\NsBlog\Domain\Model\Category;
use NITSAN\NsBlog\Domain\Model\Post;
use NITSAN\NsBlog\Domain\Repository\AuthorRepository;
use NITSAN\NsBlog\Domain\Repository\CategoryRepository;
use NITSAN\NsBlog\Domain\Repository\PostRepository;
use NITSAN\NsBlog\Pagination\BlogPagination;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class PostController extends ActionController
{
    public function __construct(
        protected readonly PostRepository $postRepository,
        protected readonly CategoryRepository $categoryRepository,
        protected readonly AuthorRepository $authorRepository
    ) {
    }

    protected function initializeView($view): void
    {
        $this->assignContentElementData();
    }

    public function listRecentPostsAction(int $currentPage = 1): ResponseInterface
    {
        $this->assignContentElementData();
        $storagePageIds = $this->resolveStoragePageIdsFromContentElement();
        $posts = $this->postRepository->findAllBlogPosts($storagePageIds);
        $pagination = $this->getPagination($posts, $currentPage);
        $this->assignCategoriesForFilterFromPosts($pagination->getPaginatedItems());
        $this->view->assign('type', 'recent');
        $this->view->assign('posts', $posts);
        $this->view->assign('pagination', $pagination);
        return $this->htmlResponse();
    }

    public function listLatestPostsAction(): ResponseInterface
    {
        $this->assignContentElementData();
        $limit = (int)($this->settings['latestPosts']['limit'] ?? 3);
        $this->assignCategoriesForFilter($this->resolveStoragePageIdsFromContentElement());
        $this->view->assign('type', 'latest');
        $this->view->assign('posts', $this->postRepository->findAllWithLimit($limit, $this->resolveStoragePageIdsFromContentElement()));
        return $this->htmlResponse();
    }

    public function listPostsByCategoryAction(?int $category = null, ?string $storage = null, int $currentPage = 1): ResponseInterface
    {
        $this->assignContentElementData();
        $storagePageIds = $this->resolveStoragePageIdsFromContentElement($storage);
        $categoryObject = $this->categoryRepository->findByUidWithoutStorage((int)$category);
        if ($categoryObject !== null) {
            $posts = $this->postRepository->findAllByCategory($categoryObject, $storagePageIds);
            $pagination = $this->getPagination($posts, $currentPage);
            $this->assignCategoriesForFilterFromPosts($pagination->getPaginatedItems());
            $this->view->assign('category', $categoryObject);
            $this->view->assign('posts', $posts);
            $this->view->assign('pagination', $pagination);
        } else {
            $categories = $this->categoryRepository->findDistinctCategoriesByPageScope($storagePageIds);
            if ($categories === []) {
                $fallbackCategories = $this->categoryRepository->findAll();
                $this->view->assign('categories', $this->deduplicateCategoriesByUid($fallbackCategories));
            } else {
                $this->view->assign('categories', $categories);
            }
        }
        return $this->htmlResponse();
    }

    public function listPostsByAuthorAction(?Author $author = null, int $currentPage = 1): ResponseInterface
    {
        $this->assignContentElementData();
        $storagePageIds = $this->resolveStoragePageIdsFromContentElement();
        if ($author instanceof Author) {
            $posts = $this->postRepository->findAllByAuthor($author, $storagePageIds);
            $pagination = $this->getPagination($posts, $currentPage);
            $this->assignCategoriesForFilterFromPosts($pagination->getPaginatedItems());
            $this->view->assign('author', $author);
            $this->view->assign('posts', $posts);
            $this->view->assign('pagination', $pagination);
        } else {
            $this->view->assign('authors', $this->authorRepository->findAll($storagePageIds));
        }
        return $this->htmlResponse();
    }

    public function relatedPostsAction(): ResponseInterface
    {
        $this->assignContentElementData();
        $limit = (int)($this->settings['relatedPosts']['limit'] ?? 3);
        $this->assignCategoriesForFilter($this->resolveStoragePageIdsFromContentElement());
        $this->view->assign('type', 'related');
        $this->view->assign('posts', $this->postRepository->findAllWithLimit($limit, $this->resolveStoragePageIdsFromContentElement()));
        return $this->htmlResponse();
    }

    public function sidebarAction(): ResponseInterface
    {
        $this->assignContentElementData();
        $storagePageIds = $this->resolveStoragePageIdsFromContentElement();
        $limit = (int)($this->settings['widgets']['recentposts']['limit'] ?? 5);
        $recentPosts = $limit > 0
            ? $this->postRepository->findAllWithLimit($limit, $storagePageIds)
            : $this->postRepository->findAllBlogPosts($storagePageIds);
        $sidebarCategories = $this->categoryRepository->findDistinctBlogCategoriesWithCount($storagePageIds);

        $this->view->assign('recentPosts', $recentPosts);
        $this->view->assign('sidebarCategories', $sidebarCategories);
        return $this->htmlResponse();
    }

    protected function assignContentElementData(): void
    {
        $contentObject = $this->request->getAttribute('currentContentObject');
        $this->view->assign('data', $contentObject !== null ? $contentObject->data : null);
    }

    /**
     * @param int[] $storagePageIds
     */
    protected function assignCategoriesForFilter(array $storagePageIds): void
    {
        $this->view->assign(
            'categoriesForFilter',
            $this->categoryRepository->findDistinctBlogCategoriesWithCount($storagePageIds)
        );
    }

    /**
     * @param iterable<Post> $posts
     */
    protected function assignCategoriesForFilterFromPosts(iterable $posts): void
    {
        $uniqueCategories = [];
        foreach ($posts as $post) {
            if (!$post instanceof Post) {
                continue;
            }
            foreach ($post->getCategories() as $category) {
                $uid = (int)$category->getUid();
                if ($uid <= 0 || isset($uniqueCategories[$uid])) {
                    continue;
                }
                $uniqueCategories[$uid] = [
                    'uid' => $uid,
                    'title' => $category->getTitle(),
                    'count' => 0,
                ];
            }
        }

        uasort(
            $uniqueCategories,
            static fn(array $a, array $b): int => strcmp((string)$a['title'], (string)$b['title'])
        );

        $this->view->assign('categoriesForFilter', array_values($uniqueCategories));
    }

    /**
     * @param iterable<mixed> $categories
     * @return array<int, Category>
     */
    protected function deduplicateCategoriesByUid(iterable $categories): array
    {
        $uniqueCategories = [];
        foreach ($categories as $category) {
            if (!$category instanceof Category) {
                continue;
            }
            $uid = (int)$category->getUid();
            if ($uid <= 0 || isset($uniqueCategories[$uid])) {
                continue;
            }
            $uniqueCategories[$uid] = $category;
        }
        return array_values($uniqueCategories);
    }

    protected function getPagination(QueryResultInterface $posts, int $currentPage): BlogPagination
    {
        $itemsPerPage = (int)($this->settings['lists']['pagination']['itemsPerPage'] ?? 10);
        $contentObject = $this->request->getAttribute('currentContentObject');
        $layout = is_object($contentObject) ? (int)(($contentObject->data['layout'] ?? 0)) : 0;
        if ($layout === 36) {
            $itemsPerPage = (int)($this->settings['loadMore']['itemsPerPage'] ?? 4);
        }
        $maximumNumberOfLinks = (int)($this->settings['lists']['pagination']['maximumNumberOfLinks'] ?? 10);
        $paginator = new QueryResultPaginator($posts, $currentPage, max(1, $itemsPerPage));
        return new BlogPagination($paginator, max(1, $maximumNumberOfLinks));
    }

    /**
     * @return int[]
     */
    protected function resolveStoragePageIdsFromContentElement(?string $storageOverride = null): array
    {
        if ($storageOverride !== null && trim($storageOverride) !== '') {
            $overridePageIds = GeneralUtility::intExplode(',', $storageOverride, true);
            $overridePageIds = array_values(array_filter($overridePageIds, static fn(int $pid): bool => $pid > 0));
            if ($overridePageIds !== []) {
                return $overridePageIds;
            }
        }

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
