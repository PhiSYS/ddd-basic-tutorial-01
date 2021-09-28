<?php
declare(strict_types=1);

namespace PhiSYS\Domain\Model\Author\Exception;

final class AuthorAlreadyExistsException extends \DomainException
{
    // This should be unique per exception. Understand this is NOT the HTTP STATUS CODE (which would be infrastructure).
    const ERROR_CODE = 4;
}
