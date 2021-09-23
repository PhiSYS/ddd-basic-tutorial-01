<?php
declare(strict_types=1);

namespace PhiSYS\Domain\Model\Book;

use PhiSYS\Domain\DomainModel;
use PhiSYS\Domain\Model\Book\ValueObject\BookId;
use PhiSYS\Domain\Model\Book\ValueObject\Title;

final class Book extends DomainModel
{
    private BookId $id;
    private Title $title;

    private function __construct(BookId $id, Title $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    public function id(): BookId
    {
        return $this->id;
    }

    public function title(): Title
    {
        return $this->title;
    }

    /*
     * Used to create a non previously existent entity. May register events.
     */
    public function create(BookId $id, Title $title): self
    {
        $instance = new self($id, $title);
        // $instance->recordThat(new BookCreatedEvent(...));

        return $instance;
    }

    /*
     * Used to hydrate an entity. Does not register events.
     */
    public static function from(BookId $id, Title $title): self
    {
        return new self($id, $title);
    }
}
