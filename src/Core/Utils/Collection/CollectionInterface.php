<?php

namespace Core\Utils\Collection;

/**
 * Interface CollectionInterface
 * @package Core\Utils\Collection
 */
interface CollectionInterface
{

    /**
     * @return array|null
     */
    public function all(): ?array;

    /**
     * @return int
     */
    public function count(): int;


    /**
     * @param CollectionInterface $collection
     * @return CollectionInterface
     */
    public function merge(CollectionInterface $collection): CollectionInterface;

    /**
     * @param CollectionInterface|object $collection
     * @return CollectionInterface
     */
    public function add($collection): CollectionInterface;
}
