<?php

namespace Test\Core\Utils\Collection;

use Core\Utils\Collection\ArrayCollection;
use PHPUnit\Framework\TestCase;

/**
 * Class ArrayCollectionTest
 * @package Test\Core\Utils\Collection
 */
class ArrayCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function emptyCollectionReturnNoItems()
    {
        $collection = new ArrayCollection;

        $this->assertEmpty($collection->all());
    }

    /**
     * @test
     */
    public function allMethodReturnAllElementsOfCollection()
    {
        $collection = new ArrayCollection([
            'one', 'two', 'three'
        ]);

        $this->assertCount(3, $collection->all());
        $this->assertEquals($collection->all()[0], 'one');
        $this->assertEquals($collection->all()[1], 'two');
        $this->assertEquals($collection->all()[2], 'three');
    }

    /**
     * @test
     */
    public function count_is_zero_when_collection_is_empty()
    {
        $collection = new ArrayCollection;

        $this->assertEquals($collection->count(), 0);
    }

    /**
     * @test
     */
    public function count_is_correct_for_items_passed_in()
    {
        $collection = new ArrayCollection([
            'one', 'two', 'three'
        ]);

        $this->assertEquals($collection->count(), 3);
    }

    /**
     * @test
     */
    public function collection_is_instance_of_iterator_aggregate()
    {
        $collection = new ArrayCollection([
            'one', 'two', 'three'
        ]);

        $this->assertInstanceOf(ArrayCollection::class, $collection);
    }

    /**
     * @test
     */
    public function collection_can_be_iterated()
    {
        $collection = new ArrayCollection([
            'one', 'two', 'three'
        ]);

        $items = [];

        foreach ($collection as $item) {
            $items[] = $item;
        }

        $this->assertCount(3, $items);
        $this->assertInstanceOf(\ArrayIterator::class, $collection->getIterator());
    }

    /**
     * @test
     */
    public function collection_can_be_merged()
    {
        $collection1 = new ArrayCollection([
            'one', 'two'
        ]);
        $collection2 = new ArrayCollection([
            'three', 'four'
        ]);

        $collection1->merge($collection2);

        $this->assertCount(4, $collection1);
    }

    /**
     * @test
     */
    public function can_add_collection_to_existing_collection()
    {
        $collection1 = new ArrayCollection([
            'one', 'two'
        ]);
        $collection2 = new ArrayCollection([
            'three', 'four'
        ]);

        $collection1->add($collection2);
        $collection1->add('five');

        $this->assertCount(5, $collection1);
        $this->assertEquals($collection1->all()[4], 'five');
    }

    /**
     * @test
     */
    public function return_json_encoded_items()
    {
        $collection = new ArrayCollection([
            [ 'username' => 'alex'],
            [ 'username' => 'billy'],
        ]);

        $this->assertInternalType('string', $collection->toJson());
        $this->assertEquals(json_encode([
            [ 'username' => 'alex'],
            [ 'username' => 'billy'],
        ]), $collection->toJson());
    }
}
