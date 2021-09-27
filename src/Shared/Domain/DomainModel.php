<?php
declare(strict_types=1);

namespace PhiSYS\Shared\Domain;

abstract class DomainModel implements \JsonSerializable
{
    protected array $events = [];

    public function recordThat(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    public function events(): array
    {
        return $this->events;
    }
}
