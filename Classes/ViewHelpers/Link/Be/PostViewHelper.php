<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\ViewHelpers\Link\Be;

final class PostViewHelper extends AbstractBackendLinkViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('post', 'mixed', '', false, null);
    }

    public function render(): string
    {
        $post = $this->arguments['post'];
        $label = is_object($post) && method_exists($post, 'getTitle') ? (string)$post->getTitle() : (string)$this->arguments['title'];
        return $this->build($label !== '' ? $label : 'Post');
    }
}
