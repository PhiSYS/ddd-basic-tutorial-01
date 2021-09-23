<?php
declare(strict_types=1);

namespace PhiSYS\Shared\ValueObject;

abstract class StringValueObject implements ValueObject
{
    private string $value;

    protected function __construct(string $value)
    {
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equalTo(StringValueObject $other): bool
    {
        return static::class === $other::class && $this->value === $other->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    public static function from(string $value)
    {
        return new static($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
