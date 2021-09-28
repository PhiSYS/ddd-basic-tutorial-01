<?php
declare(strict_types=1);

namespace PhiSYS\Domain\Model\Author\Event;

use PhiSYS\Shared\Domain\DomainEvent;
use PhiSYS\Domain\Model\Author\Author;
use PhiSYS\Domain\Model\Author\ValueObject\AuthorId;
use PhiSYS\Domain\Model\Author\ValueObject\Name;
use PhiSYS\Shared\ValueObject\Uuid;

final class AuthorWasCreated extends DomainEvent
{
    private Uuid $id;
    private AuthorId $authorId;
    private Name $authorName;

    private function __construct(Uuid $eventId, \DateTimeImmutable $occurredOn, AuthorId $authorId, Name $authorName)
    {
        $this->id = $eventId;
        $this->authorId = $authorId;
        $this->authorName = $authorName;
        $this->occurredOn = $occurredOn;
    }

    public static function from(Author $author): self
    {
        return new self(
            Uuid::v4(),
            new \DateTimeImmutable('now', new \DateTimeZone('UTC')),
            $author->id(),
            $author->name(),
        );
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function authorId(): AuthorId
    {
        return $this->authorId;
    }

    public function authorName(): Name
    {
        return $this->authorName;
    }

    public function jsonSerialize()
    {
        return [
            'event' => self::class,
            'id' => $this->id,
            'occurred_on' => $this->getOcurredOnString(),
            'author_id' => $this->authorId,
            'author_name' => $this->authorName,
        ];
    }
}
