<?php
declare(strict_types=1);

namespace PhiSYS\Domain\Service\Book;

use PhiSYS\Domain\Model\Author\ValueObject\AuthorId;
use PhiSYS\Domain\Model\Book\Book;
use PhiSYS\Domain\Model\Book\BookRepository;
use PhiSYS\Domain\Model\Book\Exception\BookAlreadyExistsException;
use PhiSYS\Domain\Model\Book\ValueObject\BookId;
use PhiSYS\Domain\Model\Book\ValueObject\Title;
use PhiSYS\Domain\Service\Author\ByIdAuthorFinder;

final class BookCreator
{
    private BookRepository $bookRepository;
    private ByIdAuthorFinder $byIdAuthorFinder;

    public function __construct(BookRepository $bookRepository, ByIdAuthorFinder $byIdAuthorFinder)
    {
        $this->bookRepository = $bookRepository;
        $this->byIdAuthorFinder = $byIdAuthorFinder;
    }

    public function __invoke(BookId $bookId, Title $title, AuthorId $authorId): Book
    {
        $this->assertBookDoesNotAlreadyExist($bookId);
        $this->assertAuthorExists($authorId);

        $book = Book::create($bookId, $title);

        $this->bookRepository->save($book);

        return $book;
    }

    private function assertAuthorExists(AuthorId $authorId): void
    {
        ($this->byIdAuthorFinder)($authorId);
    }

    private function assertBookDoesNotAlreadyExist(BookId $bookId): void
    {
        $book = $this->bookRepository->find($bookId);

        if (null !== $book) {
            throw new BookAlreadyExistsException(
                \sprintf("Book '%s' titled '%s' already exists", $bookId->value(), $book->title()->value()),
            );
        }
    }
}
