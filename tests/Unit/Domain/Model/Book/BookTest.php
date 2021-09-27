<?php

namespace PhiSYS\Tests\Unit\Domain\Model\Book;

use PhiSYS\Shared\Domain\DomainModel;
use PhiSYS\Domain\Model\Book\Book;
use PhiSYS\Domain\Model\Book\ValueObject\BookId;
use PhiSYS\Domain\Model\Book\ValueObject\Title;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
    public function test_given_when_then()
    {
        $book = Book::from(
            $this->createMock(BookId::class),
            $this->createMock(Title::class),
        );

        $this->assertInstanceOf(DomainModel::class, $book);
    }
}
