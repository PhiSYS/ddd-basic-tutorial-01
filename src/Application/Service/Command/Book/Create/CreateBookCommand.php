<?php
declare(strict_types=1);

namespace PhiSYS\Application\Service\Command\Book\Create;

use PhiSYS\Domain\Model\Book\ValueObject\BookId;
use PhiSYS\Domain\Model\Book\ValueObject\Title as BookTitle;

final class CreateBookCommand
{
    private BookId $bookId;
    private BookTitle $bookTitle;

    private function __construct(BookId $bookId, BookTitle $bookTitle)
    {
        $this->bookId = $bookId;
        $this->bookTitle = $bookTitle;
    }

    public static function from(string $bookId, string $bookTitle): self
    {
        return new self(
            BookId::from($bookId),
            BookTitle::from($bookTitle),
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
}
