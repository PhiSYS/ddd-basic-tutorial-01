<?php
declare(strict_types=1);

namespace PhiSYS\Domain\Model\Book;

use PhiSYS\Shared;

final class BookCollection implements Shared\Collection
{
    use Shared\IteratorTrait;
    use Shared\CountableTrait;
    use Shared\CollectionTrait {
        add as private traitAdd;
    }

    public function add(Book $book): void
    {
        $this->traitAdd($book);
    }
}
