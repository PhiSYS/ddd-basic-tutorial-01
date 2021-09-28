<?php
declare(strict_types=1);

namespace PhiSYS\Domain\Model\Book;

use PhiSYS\Domain\Model\Author\ValueObject\AuthorId;
use PhiSYS\Domain\Model\Book\Event\BookWasCreated;
use PhiSYS\Shared\Domain\DomainModel;
use PhiSYS\Domain\Model\Book\ValueObject\BookId;
use PhiSYS\Domain\Model\Book\ValueObject\Title;

final class Book extends DomainModel
{
    private BookId $id;
    private Title $title;
    private AuthorId $authorId;

    private function __construct(BookId $id, Title $title, AuthorId $authorId)
    {
        $this->id = $id;
        $this->title = $title;
        $this->authorId = $authorId;
    }

    /**
     * Used to create a non previously existent entity. May register events.
     */
    public static function create(BookId $id, Title $title, AuthorId $authorId): self
    {
        $instance = new self($id, $title, $authorId);
        $instance->recordThat(BookWasCreated::from($instance));

        return $instance;
    }

    /**
     * Used to hydrate an entity. Does not register events.
     */
    public static function from(BookId $id, Title $title, AuthorId $authorId): self
    {
        return new self($id, $title, $authorId);
    }

    public function id(): BookId
    {
        return $this->id;
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function authorId(): AuthorId
    {
        return $this->authorId;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author_id' => $this->authorId,
        ];
    }
}
