<?php
declare(strict_types=1);

namespace PhiSYS\Domain\Service\Book;

use PhiSYS\Domain\Model\Book\Book;
use PhiSYS\Domain\Model\Book\BookRepository;
use PhiSYS\Domain\Model\Book\Exception\BookDoesNotExistException;
use PhiSYS\Domain\Model\Book\ValueObject\BookId;

final class ByIdBookFinder
{
    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function __invoke(BookId $bookId): Book
    {
        $book = $this->bookRepository->find($bookId);

        if (null === $book) {
            throw new BookDoesNotExistException(
                sprintf("Book '%s' does not exist.", $bookId->value()),
                BookDoesNotExistException::ERROR_CODE,
            );
        }

        return $book;
    }
}
