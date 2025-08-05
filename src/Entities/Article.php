<?php

declare(strict_types=1);

namespace App\Entities;

class Article
{
    private int $id;
    private string $title;
    private string $description;
    private \DateTime $publicationDate;
    private Author $author;
    private string $doi;
    private string $abstract;
    private string $keywords;
    private string $indexation;
    private string $journal;
    private string $area;

    public function __construct(
        int $id,
        string $title,
        string $description,
        \DateTime $publicationDate,
        Author $author,
        string $doi,
        string $abstract,
        string $keywords,
        string $indexation,
        string $journal,
        string $area
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->publicationDate = $publicationDate;
        $this->author = $author;
        $this->doi = $doi;
        $this->abstract = $abstract;
        $this->keywords = $keywords;
        $this->indexation = $indexation;
        $this->journal = $journal;
        $this->area = $area;
    }

    public function getId(): int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): string { return $this->description; }
    public function getPublicationDate(): \DateTime { return $this->publicationDate; }
    public function getAuthor(): Author { return $this->author; }
    public function getDoi(): string { return $this->doi; }
    public function getAbstract(): string { return $this->abstract; }
    public function getKeywords(): string { return $this->keywords; }
    public function getIndexation(): string { return $this->indexation; }
    public function getJournal(): string { return $this->journal; }
    public function getArea(): string { return $this->area; }

    public function setId(int $id): void { $this->id = $id; }
    public function setTitle(string $title): void { $this->title = $title; }
    public function setDescription(string $description): void { $this->description = $description; }
    public function setPublicationDate(\DateTime $publicationDate): void { $this->publicationDate = $publicationDate; }
    public function setAuthor(Author $author): void { $this->author = $author; }
    public function setDoi(string $doi): void { $this->doi = $doi; }
    public function setAbstract(string $abstract): void { $this->abstract = $abstract; }
    public function setKeywords(string $keywords): void { $this->keywords = $keywords; }
    public function setIndexation(string $indexation): void { $this->indexation = $indexation; }
    public function setJournal(string $journal): void { $this->journal = $journal; }
    public function setArea(string $area): void { $this->area = $area; }
}
