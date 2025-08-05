<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Entities\Author;
use App\Entities\Article;
use App\Repositories\AuthorRepository;
use App\Repositories\ArticleRepository;


class ArticleController
{
    private ArticleRepository $articleRepository;
    private AuthorRepository $authorRepository;
    

    public function __construct()
    {
        $this->articleRepository = new ArticleRepository;
        $this->authorRepository = new AuthorRepository;
    }

    public function articleToArray(Article  $article): array
    {
        return [
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'description' => $article->getDescription(),
            'publication_date' => $article->getPublicationDate()->format('Y-m-d'),
            'author' => [
                'id' => $article->getAuthor()->getId(),
                'first_name' => $article->getAuthor()->getFirstName(),
                'last_name' => $article->getAuthor()->getLastName(),
            ],
            'doi' => $article->getDoi(),
            'abstract' => $article->getAbstract(),
            'keywords' => $article->getKeywords(),
            'indexacion' => $article->getIndexacion(),
            'magazine' => $article->getMagazine(),
            'area' => $article->getArea()
        ];
    }

    public function handle():void{
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];

        if($method === 'GET'){
            if(isset($_GET['id'])){
                $article = $this->articleRepository->findByID((int) $_GET['id']);
                echo json_encode($article?$this->articleToArray($article) : null);
            }else{
                $list = array_map([$this, 'articleToArray'], $this->articleRepository->findAll());
                echo json_encode($list);
            }
            return;
        }

        $payload = json_decode(file_get_contents('php://input'),true);

        if($method === 'POST'){
            $author=$this->authorRepository->findByID((int)$payload['authorid']) ?? 0;
            if($author){
                http_response_code(400);
                echo json_encode(['error'=>'Invalid Author']);
                return;
            }

            $article = new Article (
                (int)null,
                $payload['title'],
                $payload['description'],
                new \DateTime( $payload['title'] ?? 'now'),
                $author,
                $payload['doi'],
                $payload['abstract'],
                $payload['keywords'],
                $payload['indexacion'],
                $payload['magazine'],
                $payload['area']

            );

            echo json_encode(['Success' =>$this->articleRepository -> create($article)]);
        }
        
    }
}