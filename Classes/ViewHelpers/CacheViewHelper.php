<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

final class CacheViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('post', 'mixed', 'Kept for compatibility', false, null);
    }

    public function render(): string
    {
        // Compatibility no-op for old blog template calls.
        return '';
    }
}
