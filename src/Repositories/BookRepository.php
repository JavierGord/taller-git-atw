<?php
declare(strict_types=1);
namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Config\Database;
use App\Entities\Book;
use App\Entities\Author;
use App\Repositories\AuthorRepository;
use PDO;

class BookRepository implements RepositoryInterface
{
    private PDO $conn;
    private AuthorRepository $authorRepo;

    public function __construct()
    {
        $this->conn = Database::getConnection();
        $this->authorRepo = new AuthorRepository();
    }

    public function findAll(): array
    {
        $query = $this->conn->query("CALL sp_book_list()");
        $data = $query->fetchAll();
        $query->closeCursor();
        $collection = [];

        foreach ($data as $item) {
            $collection[] = $this->buildEntity($item);
        }

        return $collection;
    }

    public function findByID(int $id): ?object
    {
        $stmt = $this->conn->prepare("CALL sp_find_book(:id)");
        $stmt->execute([':id' => $id]);
        $record = $stmt->fetch();
        $stmt->closeCursor();

        return $record ? $this->buildEntity($record) : null;
    }

    public function create(object $entity): bool
    {
        if (!$entity instanceof Book) {
            throw new \InvalidArgumentException('Expected an instance of Book');
        }

        $stmt = $this->conn->prepare(
            "CALL sp_create_book(:title, :desc, :pub_date, :auth_id, :isbn, :genre, :ed)"
        );

        $success = $stmt->execute([
            ':title' => $entity->getTitle(),
            ':desc' => $entity->getDescription(),
            ':pub_date' => $entity->getPublicationDate()->format('Y-m-d'),
            ':auth_id' => $entity->getAuthor()->getId(),
            ':isbn' => $entity->getIsbn(),
            ':genre' => $entity->getGender(),
            ':ed' => $entity->getEdition()
        ]);

        if ($success) {
            $stmt->fetch();
        }

        $stmt->closeCursor();

        return $success;
    }

    public function update(object $entity): bool
    {
        if (!$entity instanceof Book) {
            throw new \InvalidArgumentException('Expected an instance of Book');
        }

        $stmt = $this->conn->prepare(
            "CALL sp_update_book(:id, :title, :desc, :pub_date, :auth_id, :isbn, :genre, :ed)"
        );

        $success = $stmt->execute([
            ':id' => $entity->getId(),
            ':title' => $entity->getTitle(),
            ':desc' => $entity->getDescription(),
            ':pub_date' => $entity->getPublicationDate()->format('Y-m-d'),
            ':auth_id' => $entity->getAuthor()->getId(),
            ':isbn' => $entity->getIsbn(),
            ':genre' => $entity->getGender(),
            ':ed' => $entity->getEdition()
        ]);

        if ($success) {
            $stmt->fetch();
        }

        $stmt->closeCursor();

        return $success;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("CALL sp_delete_book(:id)");
        $success = $stmt->execute([':id' => $id]);

        if ($success) {
            $stmt->fetch();
        }

        $stmt->closeCursor();

        return $success;
    }

    private function buildEntity(array $data): Book
    {
        $writer = new Author(
            (int)$data['author_id'],
            $data['first_name'],
            $data['last_name'],
            '',
            '',
            'temporal',
            '',
            ''
        );

        return new Book(
            (int)$data['publication_id'],
            $data['title'],
            $data['description'] ?? '',
            new \DateTime($data['publication_date']),
            $writer,
            $data['isbn'],
            $data['gender'],
            (int)$data['edition']
        );
    }
}
