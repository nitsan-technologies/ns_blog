<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\ViewHelpers\Link;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

final class AuthorViewHelper extends AbstractCompatLinkViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('author', 'mixed', '', false, null);
    }

    public function render(): string
    {
        $author = $this->arguments['author'];
        $uid = is_object($author) && method_exists($author, 'getUid') ? (int)$author->getUid() : 0;
        $name = is_object($author) && method_exists($author, 'getName') ? (string)$author->getName() : (string)$this->arguments['title'];
        $content = $this->renderFallback($name);
        $href = '#';
        if ($uid > 0) {
            $request = $this->getRequest();
            $pageUid = (int)($request->getAttribute('site')?->getSettings()?->get('plugin.tx_nsblog.settings.authorUid') ?? 0);
            $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
            $uriBuilder->reset()->setRequest($request)->setTargetPageUid($pageUid);
            $href = (string)$uriBuilder->uriFor('listPostsByAuthor', ['author' => $uid], 'Post', 'NsBlog', 'AuthorPosts');
        }
        return '<a href="' . htmlspecialchars($href) . '">' . $content . '</a>';
    }
}
