<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Entities\Author;
use App\Repositories\AuthorRepository;

class AuthorController
{
    private AuthorRepository $repo;

    public function __construct()
    {
        $this->repo = new AuthorRepository();
    }

    public function mapAuthorToArray(Author $author): array
    {
        return [
            'id' => $author->getId(),
            'first_name' => $author->getFirstName(),
            'last_name' => $author->getLastName(),
        ];
    }

    public function handle(): void
    {
        header('Content-Type: application/json');
        $action = $_SERVER['REQUEST_METHOD'];

        if ($action === 'GET') {
            if (isset($_GET['id'])) {
                $result = $this->repo->findByID((int) $_GET['id']);
                echo json_encode($result ? $this->mapAuthorToArray($result) : null);
            } else {
                $authors = array_map([$this, 'mapAuthorToArray'], $this->repo->findAll());
                echo json_encode($authors);
            }
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if ($action === 'POST') {
            $existing = $this->repo->findByID((int) $data['authorid']) ?? null;
            if ($existing) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid Author']);
                return;
            }

            $newAuthor = new Author(
                null,
                $data['first_name'],
                $data['last_name'],
                $data['username'],
                $data['email'],
                $data['password'],
                $data['orcid'],
                $data['affiliation']
            );

            echo json_encode(['Success' => $this->repo->create($newAuthor)]);
        }
    }
}
