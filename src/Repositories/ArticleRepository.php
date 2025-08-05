<?php
declare(strict_types=1);
namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Repositories\AuthorRepository;
use App\Config\Database;
use App\Entities\Author;
use App\Entities\Article;
use PDO;

class ArticleRepository implements RepositoryInterface
{
    private PDO $connection;
    private AuthorRepository $authorRepo;

    public function __construct()
    {
        $this->connection = Database::getConnection();
        $this->authorRepo = new AuthorRepository();
    }

    public function findAll(): array
    {
        $query = $this->connection->query("CALL sp_article_list()");
        $results = $query->fetchAll();
        $query->closeCursor();

        $articles = [];
        foreach ($results as $item) {
            $articles[] = $this->build($item);
        }

        return $articles;
    }

    public function findByID(int $id): ?object
    {
        $query = $this->connection->prepare("CALL sp_find_article(:id)");
        $query->execute([':id' => $id]);
        $record = $query->fetch();
        $query->closeCursor();

        return $record ? $this->build($record) : null;
    }

    public function create(object $entity): bool
    {
        if (!$entity instanceof Article) {
            throw new \InvalidArgumentException('Expected an instance of Article');
        }

        $stmt = $this->connection->prepare("CALL sp_create_article(:title, :description, :publication_date, :author_id, :doi, :abstract, :keywords, :indexacion, :magazine, :area)");
        $executed = $stmt->execute([
            ':title' => $entity->getTitle(),
            ':description' => $entity->getDescription(),
            ':publication_date' => $entity->getPublicationDate()->format('Y-m-d'),
            ':author_id' => $entity->getAuthor()->getId(),
            ':doi' => $entity->getDoi(),
            ':abstract' => $entity->getAbstract(),
            ':keywords' => $entity->getKeywords(),
            ':indexacion' => $entity->getIndexacion(),
            ':magazine' => $entity->getMagazine(),
            ':area' => $entity->getArea()
        ]);

        if ($executed) {
            $stmt->fetch();
        }

        $stmt->closeCursor();
        return $executed;
    }

    public function update(object $entity): bool
    {
        if (!$entity instanceof Article) {
            throw new \InvalidArgumentException('Expected an instance of Article');
        }

        $stmt = $this->connection->prepare("CALL sp_update_article(:title, :description, :publication_date, :author_id, :doi, :abstract, :keywords, :indexacion, :magazine, :area)");
        $executed = $stmt->execute([
            ':id' => $entity->getId(),
            ':title' => $entity->getTitle(),
            ':description' => $entity->getDescription(),
            ':publication_date' => $entity->getPublicationDate()->format('Y-m-d'),
            ':author_id' => $entity->getAuthor()->getId(),
            ':doi' => $entity->getDoi(),
            ':abstract' => $entity->getAbstract(),
            ':keywords' => $entity->getKeywords(),
            ':indexacion' => $entity->getIndexacion(),
            ':magazine' => $entity->getMagazine(),
            ':area' => $entity->getArea()
        ]);

        if ($executed) {
            $stmt->fetch();
        }

        $stmt->closeCursor();
        return $executed;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->connection->prepare("CALL sp_delete_article(:id)");
        $executed = $stmt->execute([':id' => $id]);

        if ($executed) {
            $stmt->fetch();
        }

        $stmt->closeCursor();
        return $executed;
    }

    private function build(array $data): Article
    {
        $creator = new Author(
            (int)$data['author_id'],
            $data['first_name'],
            $data['last_name'],
            '',
            '',
            'temporal',
            '',
            ''
        );

        return new Article(
            (int)$data['publication_id'],
            $data['title'],
            $data['description'] ?? '',
            new \DateTime($data['publication_date']),
            $creator,
            $data['doi'],
            $data['abstract'],
            $data['keywords'],
            $data['indexacion'],
            $data['magazine'],
            $data['area']
        );
    }
}
