<?php

namespace PhiSYS\Shared;

trait IteratorTrait
{
    private $items;
    private $current;

    public function current()
    {
        return $this->items[$this->current];
    }

    public function next()
    {
        ++$this->current;
    }

    public function key()
    {
        return $this->current;
    }

    public function valid()
    {
        return array_key_exists($this->current, $this->items);
    }

    public function rewind()
    {
        $this->current = 0;
    }
}
