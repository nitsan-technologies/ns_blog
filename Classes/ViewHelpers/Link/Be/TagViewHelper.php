<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\ViewHelpers\Link\Be;

final class TagViewHelper extends AbstractBackendLinkViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('tag', 'mixed', '', false, null);
    }

    public function render(): string
    {
        $tag = $this->arguments['tag'];
        $label = is_object($tag) && method_exists($tag, 'getTitle') ? (string)$tag->getTitle() : (string)$this->arguments['title'];
        return $this->build($label !== '' ? $label : 'Tag');
    }
}
