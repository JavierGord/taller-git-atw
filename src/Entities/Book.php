<?php

declare(strict_types=1);

namespace App\Entities;

class Book
{
    private int $id;
    private string $title;
    private string $description;
    private \DateTime $publicationDate;
    private Author $author;
    private string $publisher;
    private string $isbn;
    private int $totalPages;
    private string $genre;
    private string $edition;

    public function __construct(
        int $id,
        string $title,
        string $description,
        \DateTime $publicationDate,
        Author $author,
        string $publisher,
        string $isbn,
        int $totalPages,
        string $genre,
        string $edition
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->publicationDate = $publicationDate;
        $this->author = $author;
        $this->publisher = $publisher;
        $this->isbn = $isbn;
        $this->totalPages = $totalPages;
        $this->genre = $genre;
        $this->edition = $edition;
    }
    
    public function getId(): int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): string { return $this->description; }
    public function getPublicationDate(): \DateTime { return $this->publicationDate; }
    public function getAuthor(): Author { return $this->author; }
    public function getPublisher(): string { return $this->publisher; }
    public function getIsbn(): string { return $this->isbn; }
    public function getTotalPages(): int { return $this->totalPages; }
    public function getGenre(): string { return $this->genre; }
    public function getEdition(): string { return $this->edition; }

    public function setId(int $id): void { $this->id = $id; }
    public function setTitle(string $title): void { $this->title = $title; }
    public function setDescription(string $description): void { $this->description = $description; }
    public function setPublicationDate(\DateTime $publicationDate): void { $this->publicationDate = $publicationDate; }
    public function setAuthor(Author $author): void { $this->author = $author; }
    public function setPublisher(string $publisher): void { $this->publisher = $publisher; }
    public function setIsbn(string $isbn): void { $this->isbn = $isbn; }
    public function setTotalPages(int $totalPages): void { $this->totalPages = $totalPages; }
    public function setGenre(string $genre): void { $this->genre = $genre; }
    public function setEdition(string $edition): void { $this->edition = $edition; }
}
