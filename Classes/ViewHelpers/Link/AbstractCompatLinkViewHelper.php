<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\ViewHelpers\Link;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

abstract class AbstractCompatLinkViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('class', 'string', '', false, '');
        $this->registerArgument('section', 'string', '', false, '');
        $this->registerArgument('rss', 'bool', '', false, false);
        $this->registerArgument('data', 'array', '', false, []);
        $this->registerArgument('title', 'string', '', false, '');
    }

    protected function renderFallback(string $fallback): string
    {
        $content = trim((string)$this->renderChildren());
        if ($content === '') {
            $content = $fallback;
        }
        $class = trim((string)$this->arguments['class']);
        if ($class !== '') {
            return '<span class="' . htmlspecialchars($class) . '">' . $content . '</span>';
        }
        return $content;
    }

    protected function getRequest(): RequestInterface
    {
        $request = null;
        if ($this->renderingContext->hasAttribute(ServerRequestInterface::class)) {
            $request = $this->renderingContext->getAttribute(ServerRequestInterface::class);
        }

        if ($request === null || !$request instanceof RequestInterface) {
            throw new \RuntimeException(
                'ViewHelper can be used only in extbase context and needs a request implementing extbase RequestInterface.',
                1743096911
            );
        }

        return $request;
    }
}
