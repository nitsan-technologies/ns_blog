<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\ViewHelpers\Link\Be;

final class CategoryViewHelper extends AbstractBackendLinkViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('category', 'mixed', '', false, null);
    }

    public function render(): string
    {
        $category = $this->arguments['category'];
        $label = is_object($category) && method_exists($category, 'getTitle') ? (string)$category->getTitle() : (string)$this->arguments['title'];
        return $this->build($label !== '' ? $label : 'Category');
    }
}
