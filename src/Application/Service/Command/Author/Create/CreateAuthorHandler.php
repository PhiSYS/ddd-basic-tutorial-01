<?php
declare(strict_types=1);

namespace PhiSYS\Application\Service\Command\Author\Create;

use PhiSYS\Domain\Model\Author\Author;
use PhiSYS\Domain\Model\Author\AuthorRepository;
use PhiSYS\Domain\Model\Author\Exception\AuthorAlreadyExistsException;
use PhiSYS\Domain\Model\Author\ValueObject\AuthorId;
use PhiSYS\Shared\Domain\DomainModel;

class CreateAuthorHandler
{
    private AuthorRepository $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    public function __invoke(CreateAuthorCommand $command): void
    {
        $this->assertAuthorDoesNotAlreadyExist($command->authorId());

        $author = Author::create($command->authorId(), $command->authorName());

        $this->dispatchEvents($author);

        $this->authorRepository->save($author);
    }

    private function assertAuthorDoesNotAlreadyExist(AuthorId $authorId): void
    {
        $author = $this->authorRepository->find($authorId);

        if (null !== $author) {
            throw new AuthorAlreadyExistsException(
                \sprintf("Author '%s' named '%s' already exists", $authorId->value(), $author->name()->value()),
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
