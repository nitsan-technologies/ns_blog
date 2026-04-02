<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\ViewHelpers\Link\Be;

final class CommentViewHelper extends AbstractBackendLinkViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('comment', 'mixed', '', false, null);
    }

    public function render(): string
    {
        $comment = $this->arguments['comment'];
        $label = is_object($comment) && method_exists($comment, 'getComment') ? (string)$comment->getComment() : (string)$this->arguments['title'];
        return $this->build($label !== '' ? $label : 'Comment');
    }
}
