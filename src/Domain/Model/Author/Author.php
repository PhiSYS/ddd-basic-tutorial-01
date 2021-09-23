<?php
declare(strict_types=1);

namespace PhiSYS\Domain\Model\Author;

use PhiSYS\Domain\DomainModel;
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
}
