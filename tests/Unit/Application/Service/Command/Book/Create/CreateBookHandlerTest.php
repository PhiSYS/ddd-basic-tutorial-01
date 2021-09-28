<?php

namespace PhiSYS\Tests\Unit\Application\Service\Command\Book\Create;

use PhiSYS\Application\Service\Command\Book\Create\CreateBookCommand;
use PhiSYS\Application\Service\Command\Book\Create\CreateBookHandler;
use PhiSYS\Domain\Model\Book\Book;
use PhiSYS\Domain\Model\Book\BookRepository;
use PhiSYS\Domain\Model\Book\Exception\BookAlreadyExistsException;
use PHPUnit\Framework\TestCase;

class CreateBookHandlerTest extends TestCase
{
    private BookRepository $bookRepository;
    private CreateBookHandler $createBookHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->createBookHandler = new CreateBookHandler($this->bookRepository);
    }

    /* Happy Path */
    public function test_given_command_when_handled_then_saved()
    {
        $bookIdValue = 'bc680af0-3ce4-40b7-9b1d-dceddbbad6bd';
        $bookTitleValue = 'The bible of foo bar';

        $this->bookRepository
            ->expects($this->once())
                ->method('save')
                    ->with($this->isInstanceOf(Book::class))
        ;

        $command = CreateBookCommand::from($bookIdValue, $bookTitleValue);
        ($this->createBookHandler)($command);
    }

    /* Sad Path */
    public function test_given_command_when_handled_and_book_already_exists_then()
    {
        $bookIdValue = '71d452e5-36af-4f2e-86e1-473c61bfb4bd';
        $bookTitleValue = 'Foo bar for dummies';

        $this->bookRepository
            ->expects($this->once())
                ->method('find')
                    ->willReturn($this->createMock(Book::class))
        ;
        $this->bookRepository
            ->expects($this->never())
                ->method('save')
        ;
        $this->expectException(BookAlreadyExistsException::class);

        $command = CreateBookCommand::from($bookIdValue, $bookTitleValue);
        ($this->createBookHandler)($command);
    }
}
