<?php
declare(strict_types=1);

namespace PhiSYS\Application\Service\Command\Book\Create;

use PhiSYS\Domain\Model\Book\Book;
use PhiSYS\Domain\Model\Book\BookRepository;
use PhiSYS\Domain\Model\Book\Exception\BookAlreadyExistsException;
use PhiSYS\Domain\Model\Book\ValueObject\BookId;
use PhiSYS\Shared\Domain\DomainModel;

class CreateBookHandler
{
    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function __invoke(CreateBookCommand $command): void
    {
        $this->assertBookDoesNotAlreadyExist($command->bookId());

        $book = Book::create($command->bookId(), $command->bookTitle());

        $this->dispatchEvents($book);

        $this->bookRepository->save($book);
    }

    private function assertBookDoesNotAlreadyExist(BookId $bookId): void
    {
        $book = $this->bookRepository->find($bookId);

        if (null !== $book) {
            throw new BookAlreadyExistsException(
                \sprintf("Book '%s' titled '%s' already exists", $bookId->value(), $book->title()->value()),
            );
        }
    }

    /** @TODO use an injected event bus. */
    private function dispatchEvents(DomainModel $model): void
    {
        /*
        foreach ($model->events() as $event) {
            $this->eventBus->dispatch($event);
        }
        */
    }
}
