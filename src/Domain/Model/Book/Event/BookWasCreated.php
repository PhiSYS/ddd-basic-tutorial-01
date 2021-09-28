<?php
declare(strict_types=1);

namespace PhiSYS\Domain\Model\Book\Event;

use PhiSYS\Domain\Model\Book\Book;
use PhiSYS\Domain\Model\Book\ValueObject\BookId;
use PhiSYS\Domain\Model\Book\ValueObject\Title;
use PhiSYS\Shared\Domain\DomainEvent;
use PhiSYS\Shared\ValueObject\Uuid;

final class BookWasCreated extends DomainEvent
{
    private Uuid $id;
    private BookId $bookId;
    private Title $bookTitle;

    private function __construct(Uuid $eventId, \DateTimeImmutable $occurredOn, BookId $bookId, Title $bookTitle)
    {
        $this->id = $eventId;
        $this->bookId = $bookId;
        $this->bookTitle = $bookTitle;
        $this->occurredOn = $occurredOn;
    }

    public static function from(Book $book): self
    {
        return new self(
            Uuid::v4(),
            new \DateTimeImmutable('now', new \DateTimeZone('UTC')),
            $book->id(),
            $book->title(),
        );
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function bookId(): BookId
    {
        return $this->bookId;
    }

    public function bookTitle(): Title
    {
        return $this->bookTitle;
    }

    public function jsonSerialize()
    {
        return [
            'event' => self::class,
            'id' => $this->id,
            'occurred_on' => $this->getOcurredOnString(),
            'book_id' => $this->bookId,
            'book_title' => $this->bookTitle,
        ];
    }
}
