<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\ViewHelpers\Link;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

final class CategoryViewHelper extends AbstractCompatLinkViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('category', 'mixed', '', false, null);
    }

    public function render(): string
    {
        $category = $this->arguments['category'];
        $uid = 0;
        if (is_object($category) && method_exists($category, 'getUid')) {
            $uid = (int)$category->getUid();
        } elseif (is_array($category)) {
            $uid = (int)($category['uid'] ?? 0);
        }
        $title = is_object($category) && method_exists($category, 'getTitle')
            ? (string)$category->getTitle()
            : (is_array($category) ? (string)($category['title'] ?? '') : (string)$this->arguments['title']);
        $content = $this->renderFallback($title);
        $href = '#';
        if ($uid > 0) {
            $request = $this->getRequest();
            $pageUid = (int)($request->getAttribute('site')?->getSettings()?->get('plugin.tx_nsblog.settings.categoryUid') ?? 0);
            $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
            $uriBuilder->reset()->setRequest($request)->setTargetPageUid($pageUid);
            $arguments = ['category' => $uid];
            $href = (string)$uriBuilder->uriFor('listPostsByCategory', $arguments, 'Post', 'NsBlog', 'Category');
        }
        return '<a href="' . htmlspecialchars($href) . '">' . $content . '</a>';
    }
}
