<?php
declare(strict_types=1);

namespace PhiSYS\Domain\Model\Author;

use PhiSYS\Domain\Model\Author\Event\AuthorWasCreated;
use PhiSYS\Shared\Domain\DomainModel;
use PhiSYS\Domain\Model\Author\ValueObject\AuthorId;
use PhiSYS\Domain\Model\Author\ValueObject\Name;

final class Author extends DomainModel
{
    private AuthorId $id;
    private Name $name;

    private function __construct(AuthorId $id, Name $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Used to create a non previously existent entity. May register events.
     */
    public static function create(AuthorId $id, Name $name): self
    {
        $instance = new self($id, $name);
        $instance->recordThat(AuthorWasCreated::from($instance));

        return $instance;
    }

    /**
     * Used to hydrate an entity. Does not register events.
     */
    public static function from(AuthorId $id, Name $name): self
    {
        return new self($id, $name);
    }

    public function id(): AuthorId
    {
        return $this->id;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
