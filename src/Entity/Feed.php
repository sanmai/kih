<?php declare(strict_types=1);

namespace KiH\Entity;

use ArrayIterator;
use Iterator;
use IteratorAggregate;

final class Feed implements IteratorAggregate
{
    /**
     * @var Item[]
     */
    private $files;

    /**
     * @param Item[] $files
     */
    public function __construct(array $files)
    {
        $this->files = $files;
    }

    /**
     * @return Iterator|Item[]
     */
    public function getIterator() : Iterator
    {
        return new ArrayIterator($this->files);
    }
}
