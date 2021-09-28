<?php
declare(strict_types=1);

namespace PhiSYS\Application\Service\Command\Book\Create;

use PhiSYS\Domain\Service\Book\BookCreator;
use PhiSYS\Shared\Domain\DomainModel;

final class CreateBookHandler
{
    private BookCreator $bookCreator;

    public function __construct(BookCreator $bookCreator)
    {
        $this->bookCreator = $bookCreator;
    }

    public function __invoke(CreateBookCommand $command): void
    {
        $book = ($this->bookCreator)($command->bookId(), $command->bookTitle(), $command->authorId());

        $this->dispatchEvents($book);
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
