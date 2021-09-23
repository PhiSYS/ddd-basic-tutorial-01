<?php
declare(strict_types=1);

namespace PhiSYS\Shared;

trait CountableTrait
{
    private $items;

    /**
     * Count elements of an object.
     *
     * @see http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     *             </p>
     *             <p>
     *             The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->items);
    }
}
