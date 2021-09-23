<?php
declare(strict_types=1);

namespace PhiSYS\Domain\Service\Author;

use PhiSYS\Domain\Model\Author\Author;
use PhiSYS\Domain\Model\Author\AuthorRepository;
use PhiSYS\Domain\Model\Author\Exception\AuthorDoesNotExistException;
use PhiSYS\Domain\Model\Author\ValueObject\AuthorId;

final class ByIdAuthorFinder
{
    private AuthorRepository $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    public function __invoke(AuthorId $authorId): Author
    {
        $author = $this->authorRepository->find($authorId);

        if (null === $author) {
            throw new AuthorDoesNotExistException(
                sprintf("Author '%s' does not exist.", $authorId->value()),
                AuthorDoesNotExistException::ERROR_CODE,
            );
        }

        return $author;
    }
}
