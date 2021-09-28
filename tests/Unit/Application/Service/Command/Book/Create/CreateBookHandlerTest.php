<?php

namespace PhiSYS\Tests\Unit\Application\Service\Command\Book\Create;

use PhiSYS\Application\Service\Command\Book\Create\CreateBookCommand;
use PhiSYS\Application\Service\Command\Book\Create\CreateBookHandler;
use PhiSYS\Domain\Model\Author\ValueObject\AuthorId;
use PhiSYS\Domain\Model\Book\Book;
use PhiSYS\Domain\Model\Book\BookRepository;
use PhiSYS\Domain\Model\Book\Exception\BookAlreadyExistsException;
use PhiSYS\Domain\Model\Book\ValueObject\BookId;
use PhiSYS\Domain\Model\Book\ValueObject\Title;
use PhiSYS\Domain\Service\Author\ByIdAuthorFinder;
use PhiSYS\Domain\Service\Book\BookCreator;
use PHPUnit\Framework\TestCase;

class CreateBookHandlerTest extends TestCase
{
    private BookRepository $bookRepository;
    private ByIdAuthorFinder $byIdAuthorFinder;
    private BookCreator $bookCreator;
    private CreateBookHandler $createBookHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->byIdAuthorFinder = $this->createMock(ByIdAuthorFinder::class);
        $this->bookCreator = $this->getMockBuilder(BookCreator::class)
            ->enableOriginalConstructor()
            ->setConstructorArgs([$this->bookRepository, $this->byIdAuthorFinder])
            ->getMock()
        ;
        $this->createBookHandler = new CreateBookHandler($this->bookCreator);
    }

    /* Happy Path */
    public function test_given_command_when_handled_then_creator_invoked()
    {
        $bookIdValue = 'bc680af0-3ce4-40b7-9b1d-dceddbbad6bd';
        $bookTitleValue = 'The bible of foo bar';
        $authorIdValue = '39a422ea-9320-4b3e-95d3-d1f201eaa1ec';

        $this->bookCreator
            ->expects($this->once())
                ->method('__invoke')
                    ->with(
                        $this->isInstanceOf(BookId::class),
                        $this->isInstanceOf(Title::class),
                        $this->isInstanceOf(AuthorId::class),
                    )
        ;

        $command = CreateBookCommand::from($bookIdValue, $bookTitleValue, $authorIdValue);
        ($this->createBookHandler)($command);
    }
}
