<?php

namespace PhiSYS\Tests\Unit\Domain\Service\Book;

use PhiSYS\Domain\Model\Author\Author;
use PhiSYS\Domain\Model\Author\ValueObject\AuthorId;
use PhiSYS\Domain\Model\Book\Book;
use PhiSYS\Domain\Model\Book\BookRepository;
use PhiSYS\Domain\Model\Book\Exception\BookAlreadyExistsException;
use PhiSYS\Domain\Model\Book\ValueObject\BookId;
use PhiSYS\Domain\Model\Book\ValueObject\Title;
use PhiSYS\Domain\Service\Author\ByIdAuthorFinder;
use PhiSYS\Domain\Service\Book\BookCreator;
use PHPUnit\Framework\TestCase;

class BookCreatorTest extends TestCase
{
    private BookRepository $bookRepository;
    private ByIdAuthorFinder $byIdAuthorFinder;
    private BookCreator $bookCreator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->byIdAuthorFinder = $this->createMock(ByIdAuthorFinder::class);

        $this->bookCreator = new BookCreator($this->bookRepository, $this->byIdAuthorFinder);
    }

    public function test_given_valid_parameters_when_invoked_then_saved()
    {
        $bookId = $this->createMock(BookId::class);
        $bookTitle = $this->createMock(Title::class);
        $authorId = $this->createMock(AuthorId::class);

        $this->byIdAuthorFinder
            ->expects($this->once())
                ->method('__invoke')
                    ->willReturn($this->createMock(Author::class))
        ;
        $this->bookRepository
            ->expects($this->once())
                ->method('save')
                    ->with($this->isInstanceOf(Book::class))
        ;

        $book = ($this->bookCreator)($bookId, $bookTitle, $authorId);

        $this->assertInstanceOf(Book::class, $book);
    }

    public function test_given_valid_parameters_when_invoked_and_book_id_already_exists_then_fail()
    {
        $bookId = $this->createMock(BookId::class);
        $bookTitle = $this->createMock(Title::class);
        $authorId = $this->createMock(AuthorId::class);

        $this->bookRepository
            ->expects($this->never())
                ->method('save');
        $this->bookRepository
            ->expects($this->once())
                ->method('find')
                    ->willReturn($this->createMock(Book::class))
        ;
        $this->expectException(BookAlreadyExistsException::class);

        ($this->bookCreator)($bookId, $bookTitle, $authorId);
    }
}
