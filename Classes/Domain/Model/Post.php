<?php
declare(strict_types=1);

namespace NITSAN\NsBlog\Domain\Model;

use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Post extends AbstractEntity
{
    protected string $title = '';
    protected string $subtitle = '';
    protected string $abstract = '';
    protected string $description = '';
    protected int $publishDate = 0;
    protected ?FileReference $featuredImage = null;

    /**
     * @var ObjectStorage<Category>
     * @Extbase\ORM\Lazy
     */
    protected ObjectStorage $categories;

    /**
     * @var ObjectStorage<Author>
     * @Extbase\ORM\Lazy
     */
    protected ObjectStorage $authors;

    /**
     * @var ObjectStorage<FileReference>
     * @Extbase\ORM\Lazy
     */
    protected ObjectStorage $media;

    public function __construct()
    {
        $this->initializeObject();
    }

    public function initializeObject(): void
    {
        $this->categories = new ObjectStorage();
        $this->authors = new ObjectStorage();
        $this->media = new ObjectStorage();
    }

    public function getTitle(): string { return $this->title; }
    public function getSubtitle(): string { return $this->subtitle; }
    public function getAbstract(): string { return $this->abstract; }
    public function getDescription(): string { return $this->description; }
    public function getPublishDate(): int { return $this->publishDate; }
    public function getFeaturedImage(): ?FileReference { return $this->featuredImage; }
    public function getCategories(): ObjectStorage { return $this->categories; }
    public function getAuthors(): ObjectStorage { return $this->authors; }
    public function getMedia(): ObjectStorage { return $this->media; }
}
