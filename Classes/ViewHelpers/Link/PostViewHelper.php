<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\ViewHelpers\Link;

final class PostViewHelper extends AbstractCompatLinkViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('post', 'mixed', '', false, null);
        $this->registerArgument('rel', 'string', '', false, '');
        $this->registerArgument('returnUri', 'bool', '', false, false);
        $this->registerArgument('createAbsoluteUri', 'bool', '', false, false);
        $this->registerArgument('additionalAttributes', 'array', '', false, []);
        $this->registerArgument('tabindex', 'string', '', false, '');
    }

    public function render(): string
    {
        $post = $this->arguments['post'];
        $uid = is_object($post) && method_exists($post, 'getUid') ? (int)$post->getUid() : 0;
        $href = $uid > 0 ? '/?id=' . $uid : '#';
        if ((bool)$this->arguments['returnUri']) {
            return $href;
        }

        $fallback = is_object($post) && method_exists($post, 'getTitle') ? (string)$post->getTitle() : (string)$this->arguments['title'];
        $content = $this->renderFallback($fallback);
        $rel = trim((string)$this->arguments['rel']);
        $section = trim((string)$this->arguments['section']);
        $class = trim((string)$this->arguments['class']);
        $tabindex = trim((string)$this->arguments['tabindex']);
        $additionalAttributes = is_array($this->arguments['additionalAttributes']) ? $this->arguments['additionalAttributes'] : [];
        if ($section !== '') {
            $href .= '#' . rawurlencode($section);
        }
        $relAttr = $rel !== '' ? ' rel="' . htmlspecialchars($rel) . '"' : '';
        $classAttr = $class !== '' ? ' class="' . htmlspecialchars($class) . '"' : '';
        $tabindexAttr = $tabindex !== '' ? ' tabindex="' . htmlspecialchars($tabindex) . '"' : '';

        $customAttributes = '';
        foreach ($additionalAttributes as $name => $value) {
            $attributeName = trim((string)$name);
            if ($attributeName === '') {
                continue;
            }
            $customAttributes .= ' ' . htmlspecialchars($attributeName) . '="' . htmlspecialchars((string)$value) . '"';
        }

        return '<a href="' . htmlspecialchars($href) . '"' . $classAttr . $relAttr . $tabindexAttr . $customAttributes . '>' . $content . '</a>';
    }
}
