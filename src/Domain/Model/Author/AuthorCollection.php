<?php
declare(strict_types=1);

namespace PhiSYS\Domain\Model\Author;

use PhiSYS\Shared;

final class AuthorCollection implements Shared\Collection
{
    use Shared\IteratorTrait;
    use Shared\CountableTrait;
    use Shared\CollectionTrait {
        add as private traitAdd;
    }

    public function add(Author $author): void
    {
        $this->traitAdd($author);
    }
}
