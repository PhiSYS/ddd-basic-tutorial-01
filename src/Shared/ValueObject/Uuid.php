<?php
declare(strict_types=1);

namespace PhiSYS\Shared\ValueObject;

use Ramsey\Uuid\Uuid as RamseyUuid;
use Ramsey\Uuid\UuidInterface;

class Uuid extends StringValueObject
{
    protected UuidInterface $uuid;

    final protected function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;

        parent::__construct($uuid->toString());
    }

    /** @return static */
    final public static function from(string $value)
    {
        return new static(RamseyUuid::fromString($value));
    }

    /** @return static */
    final public static function fromBinary(string $binaryUuid)
    {
        return new static(RamseyUuid::fromBytes($binaryUuid));
    }

    /** @return static */
    final public static function v4()
    {
        return new static(RamseyUuid::uuid4());
    }

    public function valueBinary(): string
    {
        return $this->uuid->getBytes();
    }

    public function compareTo(UuidInterface $other): bool
    {
        return 0 === $this->uuid->compareTo($other);
    }
}
