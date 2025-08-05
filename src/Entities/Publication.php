<?php

declare(strict_types=1);

namespace App\Entities;

class Publication
{
    private int $id;
    private string $title;
    private string $description;
    private \DateTime $publicationDate;
    private Author $author;
    private string $type; // book | article

    public function __construct(
        int $id,
        string $title,
        string $description,
        \DateTime $publicationDate,
        Author $author,
        string $type
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->publicationDate = $publicationDate;
        $this->author = $author;
        $this->type = $type;
    }

    public function getId(): int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): string { return $this->description; }
    public function getPublicationDate(): \DateTime { return $this->publicationDate; }
    public function getAuthor(): Author { return $this->author; }
    public function getType(): string { return $this->type; }
    
    public function setId(int $id): void { $this->id = $id; }
    public function setTitle(string $title): void { $this->title = $title; }
    public function setDescription(string $description): void { $this->description = $description; }
    public function setPublicationDate(\DateTime $publicationDate): void { $this->publicationDate = $publicationDate; }
    public function setAuthor(Author $author): void { $this->author = $author; }
    public function setType(string $type): void { $this->type = $type; }
}