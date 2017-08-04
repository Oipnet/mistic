<?php

namespace Core\Utils\Collection;

use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * Class ArrayCollection
 * @package Core\Utils\Collection
 */
class ArrayCollection implements CollectionInterface, IteratorAggregate, JsonSerializable
{
    /**
     * @var array
     */
    protected $items;

    /**
     * ArrayCollection constructor.
     * @param array $items
     */
    public function __construct($items = [])
    {
        $this->items = $items;
    }

    /**
     * @return array|null
     */
    public function all(): ?array
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @param CollectionInterface $collection
     * @return CollectionInterface
     */
    public function merge(CollectionInterface $collection): CollectionInterface
    {
        return $this->add($collection);
    }

    /**
     * @param CollectionInterface|object $items
     * @return CollectionInterface
     */
    public function add($items): CollectionInterface
    {
        if ($items instanceof CollectionInterface) {
            $this->items = array_merge($this->items, $items->all());
        } else {
            $this->items[] = $items;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->all();
    }
}
