<?php

namespace PhiSYS\Tests\Unit\Domain\Model\Book;

use PhiSYS\Domain\Model\Book\Event\BookWasCreated;
use PhiSYS\Shared\Domain\DomainModel;
use PhiSYS\Domain\Model\Book\Book;
use PhiSYS\Domain\Model\Book\ValueObject\BookId;
use PhiSYS\Domain\Model\Book\ValueObject\Title;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
    public function test_given_book_values_when_book_created_then_contains_created_event()
    {
        $book = Book::create(
            $this->createMock(BookId::class),
            $this->createMock(Title::class),
        );

        $this->assertInstanceOf(DomainModel::class, $book);
        $this->assertIsArray($book->events());
        $this->assertCount(1, $book->events());
        $this->assertContainsOnlyInstancesOf(BookWasCreated::class, $book->events());
    }

    public function test_given_book_values_when_book_hydrated_then_has_no_events()
    {
        $book = Book::from(
            $this->createMock(BookId::class),
            $this->createMock(Title::class),
        );

        $this->assertInstanceOf(DomainModel::class, $book);
        $this->assertEmpty($book->events());
    }

    public function test_given_book_when_get_properties_then_return_expected_values()
    {
        $bookId = $this->createMock(BookId::class);
        $bookTitle = $this->createMock(Title::class);
        $book = Book::from($bookId, $bookTitle);

        $this->assertSame($bookId, $book->id());
        $this->assertSame($bookTitle, $book->title());
    }

    public function test_given_book_when_json_serialize_then_serializes_as_expected()
    {
        $bookId = $this->createMock(BookId::class);
        $bookTitle = $this->createMock(Title::class);

        $book = Book::from($bookId, $bookTitle);

        $this->assertEquals(
            [
                'id' => $bookId,
                'title' => $bookTitle,
            ],
            $book->jsonSerialize(),
        );
    }
}
