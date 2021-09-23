<?php
declare(strict_types=1);

namespace PhiSYS\Domain\Model\Author;

use PhiSYS\Domain\Model\Author\ValueObject\AuthorId;

interface AuthorRepository
{
    public function find(AuthorId $authorId): ?Author;

    public function save(Author $author): void;
}
