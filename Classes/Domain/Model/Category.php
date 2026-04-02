<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Category extends AbstractEntity
{
    protected string $title = '';
    protected string $slug = '';
    protected string $description = '';

    public function getTitle(): string { return $this->title; }
    public function getSlug(): string { return $this->slug; }
    public function getDescription(): string { return $this->description; }
}
