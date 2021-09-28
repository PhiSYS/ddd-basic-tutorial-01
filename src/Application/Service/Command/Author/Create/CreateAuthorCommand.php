<?php
declare(strict_types=1);

namespace PhiSYS\Application\Service\Command\Author\Create;

use PhiSYS\Domain\Model\Author\ValueObject\AuthorId;
use PhiSYS\Domain\Model\Author\ValueObject\Name as AuthorName;

final class CreateAuthorCommand
{
    private AuthorId $authorId;
    private AuthorName $authorName;

    private function __construct(AuthorId $authorId, AuthorName $authorName)
    {
        $this->authorId = $authorId;
        $this->authorName = $authorName;
    }

    public static function from(string $authorId, string $authorName): self
    {
        return new self(
            AuthorId::from($authorId),
            AuthorName::from($authorName),
        );
    }

    public function authorId(): AuthorId
    {
        return $this->authorId;
    }

    public function authorName(): AuthorName
    {
        return $this->authorName;
    }
}
