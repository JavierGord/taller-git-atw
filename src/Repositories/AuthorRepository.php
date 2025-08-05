<?php
declare(strict_types=1);
namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Config\Database;
use App\Entities\Author;
use PDO;

class AuthorRepository implements RepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function findAll(): array
    {
        $query = $this->connection->query("SELECT * FROM author");
        $authors = [];

        while ($data = $query->fetch()) {
            $authors[] = $this->mapToAuthor($data);
        }

        return $authors;
    }

    public function findByID(int $id): ?object
    {
        $query = $this->connection->prepare("SELECT * FROM author WHERE id = :id");
        $query->execute([':id' => $id]);
        $record = $query->fetch();

        return $record ? $this->mapToAuthor($record) : null;
    }

    public function create(object $entity): bool
    {
        if (!$entity instanceof Author) {
            throw new \InvalidArgumentException('Expected an instance of Author');
        }

        $sql = "INSERT INTO author (first_name, last_name, username, email, password, orcid, affiliation) 
                VALUES (:first, :last, :user, :mail, :pass, :orcid, :affil)";

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
            ':first' => $entity->getFirstName(),
            ':last' => $entity->getLastName(),
            ':user' => $entity->getUsername(),
            ':mail' => $entity->getEmail(),
            ':pass' => $entity->getPassword(),
            ':orcid' => $entity->getOrcid(),
            ':affil' => $entity->getAffiliation()
        ]);
    }

    public function update(object $entity): bool
    {
        if (!$entity instanceof Author) {
            throw new \InvalidArgumentException('Expected an instance of Author');
        }

        $sql = "UPDATE author SET 
                    first_name = :first, 
                    last_name = :last, 
                    username = :user, 
                    email = :mail, 
                    password = :pass, 
                    orcid = :orcid, 
                    affiliation = :affil 
                WHERE id = :id";

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
            ':id' => $entity->getId(),
            ':first' => $entity->getFirstName(),
            ':last' => $entity->getLastName(),
            ':user' => $entity->getUsername(),
            ':mail' => $entity->getEmail(),
            ':pass' => $entity->getPassword(),
            ':orcid' => $entity->getOrcid(),
            ':affil' => $entity->getAffiliation()
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM author WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    private function mapToAuthor(array $data): Author
    {
        $instance = new Author(
            (int)$data['id'],
            $data['first_name'],
            $data['last_name'],
            $data['username'],
            $data['email'],
            'temporal',
            $data['orcid'],
            $data['affiliation']
        );

        $refClass = new \ReflectionClass($instance);
        $passwordProp = $refClass->getProperty('password');
        $passwordProp->setAccessible(true);
        $passwordProp->setValue($instance, $data['password']);

        return $instance;
    }
}
