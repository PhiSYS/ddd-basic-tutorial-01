<?php
declare(strict_types=1);

namespace PhiSYS\Shared\Domain;

abstract class DomainEvent implements \JsonSerializable
{
    protected \DateTimeImmutable $occurredOn;

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    protected function getOcurredOnString(): string
    {
        return $this->occurredOn->format(\DATE_ATOM);
    }
}
