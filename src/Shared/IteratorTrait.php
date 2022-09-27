<?php
declare(strict_types=1);

namespace PhiSYS\Shared;

trait IteratorTrait
{
    private $items;
    private $current;

    public function current(): mixed
    {
        return $this->items[$this->current];
    }

    public function next(): void
    {
        ++$this->current;
    }

    public function key(): int
    {
        return $this->current;
    }

    public function valid(): bool
    {
        return array_key_exists($this->current, $this->items);
    }

    public function rewind(): void
    {
        $this->current = 0;
    }
}
