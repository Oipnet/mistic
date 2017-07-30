<?php
/**
 * Created by PhpStorm.
 * User: arnaudp
 * Date: 30/07/17
 * Time: 10:28
 */

namespace Core\Utils\Collection;


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