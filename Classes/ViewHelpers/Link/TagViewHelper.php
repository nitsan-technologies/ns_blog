<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\ViewHelpers\Link;

final class TagViewHelper extends AbstractCompatLinkViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('tag', 'mixed', '', false, null);
    }

    public function render(): string
    {
        $tag = $this->arguments['tag'];
        $title = is_object($tag) && method_exists($tag, 'getTitle') ? (string)$tag->getTitle() : 'Tag';
        $content = $this->renderFallback($title);
        return '<a href="#">' . $content . '</a>';
    }
}
