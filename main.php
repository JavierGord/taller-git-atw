<?php
require_once __DIR__ . '/vendor/autoload.php';


use App\Repositories\AuthorRepository;
use App\Repositories\BookRepository;
use App\Repositories\ArticleRepository;

$authorRepo = new AuthorRepository();
$bookRepo = new BookRepository();
$articleRepo = new ArticleRepository();

$authors = $authorRepo->findAll();
foreach ($authors as $author) {
    echo $author->getFirstName() . ' ' . $author->getLastName() . PHP_EOL;
}

$books = $bookRepo->findAll();
foreach ($books as $book) {
    echo $book->getTitle() . ' (' . $book->getIsbn() . ')' . PHP_EOL;
}

$articles = $articleRepo->findAll();
foreach ($articles as $article) {
    echo $article->getTitle() . ' - ' . $article->getJournal() . PHP_EOL;
}
