<?php
declare(strict_types=1);

namespace PhiSYS\Application\Service\Command\Book\Create;

use PhiSYS\Domain\Model\Author\ValueObject\AuthorId;
use PhiSYS\Domain\Model\Book\ValueObject\BookId;
use PhiSYS\Domain\Model\Book\ValueObject\Title as BookTitle;

final class CreateBookCommand
{
    private BookId $bookId;
    private BookTitle $bookTitle;
    private AuthorId $authorId;

    private function __construct(BookId $bookId, BookTitle $bookTitle, AuthorId $authorId)
    {
        $this->bookId = $bookId;
        $this->bookTitle = $bookTitle;
        $this->authorId = $authorId;
    }

    public static function from(string $bookId, string $bookTitle, string $authorId): self
    {
        return new self(
            BookId::from($bookId),
            BookTitle::from($bookTitle),
            AuthorId::from($authorId),
        );
    }

    public function bookId(): BookId
    {
        return $this->bookId;
    }

    public function bookTitle(): BookTitle
    {
        return $this->bookTitle;
    }

    public function authorId(): AuthorId
    {
        return $this->authorId;
    }
}
