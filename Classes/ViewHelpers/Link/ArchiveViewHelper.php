<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\ViewHelpers\Link;

final class ArchiveViewHelper extends AbstractCompatLinkViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('year', 'int', '', false, 0);
        $this->registerArgument('month', 'int', '', false, 0);
    }

    public function render(): string
    {
        $year = (int)$this->arguments['year'];
        $month = (int)$this->arguments['month'];
        $fallback = trim((string)$this->arguments['title']) ?: trim((string)$year . ' ' . (string)$month);
        $content = $this->renderFallback($fallback);
        return '<a href="#">' . $content . '</a>';
    }
}
