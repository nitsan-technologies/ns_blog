<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Author extends AbstractEntity
{
    protected string $name = '';
    protected string $slug = '';
    protected string $title = '';
    protected string $bio = '';

    public function getName(): string { return $this->name; }
    public function getSlug(): string { return $this->slug; }
    public function getTitle(): string { return $this->title; }
    public function getBio(): string { return $this->bio; }
}
