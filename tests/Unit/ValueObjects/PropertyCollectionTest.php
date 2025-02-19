<?php

declare(strict_types=1);

namespace MetaDataTool\Tests\Unit\ValueObjects;

use MetaDataTool\Tests\Unit\TestCase;
use MetaDataTool\ValueObjects\HttpMethodMask;
use MetaDataTool\ValueObjects\Property;
use MetaDataTool\ValueObjects\PropertyCollection;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PropertyCollection::class)]
class PropertyCollectionTest extends TestCase
{
    public function testConstructorIsProtectedAgainstWrongType(): void
    {
        $this->expectException(\TypeError::class);
        new PropertyCollection(new \stdClass());
    }

    public function testObjectIsIterable(): void
    {
        self::assertIsIterable(new PropertyCollection());
    }

    public function testCollectionCanBeCorrectlySerialised(): void
    {
        $property = new Property(
            $name = $this->faker()->name,
            $type = $this->faker()->randomElement(['string', 'int', 'bool']),
            $description = $this->faker()->words(6, true),
            true,
            $methods = HttpMethodMask::all(),
            $hidden = true,
            $mandatory = true
        );
        $collection = new PropertyCollection($property);

        self::assertSame(
            json_encode([$property]),
            json_encode($collection, JSON_THROW_ON_ERROR)
        );
    }

    public function testCollectionCanBeCorrectlyDeserialised(): void
    {
        $json = (string) json_encode(new PropertyCollection(new Property(
            $name = $this->faker()->name,
            $type = $this->faker()->randomElement(['string', 'int', 'bool']),
            $description = $this->faker()->words(6, true),
            true,
            $methods = HttpMethodMask::all(),
            $hidden = true,
            $mandatory = true
        )), JSON_THROW_ON_ERROR);

        self::assertEquals(
            new PropertyCollection(new Property(
                $name,
                $type,
                $description,
                true,
                $methods,
                $hidden,
                $mandatory
            )),
            PropertyCollection::jsonDeserialize(json_decode($json, true, 512, JSON_THROW_ON_ERROR))
        );
    }

    public function testCollectionReturnsCorrectIterator(): void
    {
        $propertyOne = new Property(
            $this->faker()->name,
            $this->faker()->randomElement(['string', 'int', 'bool']),
            $this->faker()->words(6, true),
            true,
            HttpMethodMask::all(),
            false,
            true
        );
        $propertyTwo = new Property(
            $this->faker()->name,
            $this->faker()->randomElement(['string', 'int', 'bool']),
            $this->faker()->words(6, true),
            true,
            HttpMethodMask::all(),
            false,
            false
        );
        $collection = new PropertyCollection();
        $collection->add($propertyOne);
        $collection->add($propertyTwo);

        $iterator = $collection->getIterator();

        self::assertEquals([$propertyOne, $propertyTwo], (array) $iterator);
    }
}
