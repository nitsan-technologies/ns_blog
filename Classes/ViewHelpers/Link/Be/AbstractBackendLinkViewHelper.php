<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\ViewHelpers\Link\Be;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

abstract class AbstractBackendLinkViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('class', 'string', '', false, '');
        $this->registerArgument('title', 'string', '', false, '');
    }

    protected function build(string $label): string
    {
        $content = trim((string)$this->renderChildren());
        if ($content === '') {
            $content = $label;
        }
        $class = trim((string)$this->arguments['class']);
        $classAttr = $class !== '' ? ' class="' . htmlspecialchars($class) . '"' : '';
        return '<a href="#"' . $classAttr . '>' . $content . '</a>';
    }
}
