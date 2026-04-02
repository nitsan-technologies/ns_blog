<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\ViewHelpers\Link\Be;

final class AuthorViewHelper extends AbstractBackendLinkViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('author', 'mixed', '', false, null);
    }

    public function render(): string
    {
        $author = $this->arguments['author'];
        $label = is_object($author) && method_exists($author, 'getName') ? (string)$author->getName() : (string)$this->arguments['title'];
        return $this->build($label !== '' ? $label : 'Author');
    }
}
