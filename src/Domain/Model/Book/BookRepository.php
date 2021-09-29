<?php
declare(strict_types=1);

namespace PhiSYS\Domain\Model\Book;

use PhiSYS\Domain\Model\Author\ValueObject\AuthorId;
use PhiSYS\Domain\Model\Book\ValueObject\BookId;

interface BookRepository
{
    public function find(BookId $bookId): ?Book;

    public function findManyByAuthorId(AuthorId $authorId): BookCollection;

    public function save(Book $book): void;
}
