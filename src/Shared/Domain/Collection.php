<?php declare (strict_types=1);

namespace App\Shared\Domain;

use ArrayIterator;
use Countable;
use IteratorAggregate;

abstract class Collection implements Countable, IteratorAggregate
{
    private array $items;

    /**
     * @throws InvalidCollectionObjectException
     */
    public function __construct(array $items)
    {
        if (!empty($items)) {
            foreach ($items as $object) {
                if (false === is_a($object, $this->type())) {
                    throw new InvalidCollectionObjectException($object, $this->type());
                }
            }
        }

        $this->items = $items;
    }

    abstract protected function type(): string;

    public function getCollection(): array
    {
        return $this->items;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items());
    }

    public function count(): int
    {
        return count($this->items());
    }

    protected function items(): array
    {
        return $this->items;
    }

    public function add($element): void
    {
        $this->items[] = $element;
    }
}