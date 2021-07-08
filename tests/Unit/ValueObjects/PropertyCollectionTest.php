<?php

declare(strict_types=1);

namespace MetaDataTool\Tests\Unit\ValueObjects;

use MetaDataTool\Tests\Unit\TestCase;
use MetaDataTool\ValueObjects\HttpMethodMask;
use MetaDataTool\ValueObjects\Property;
use MetaDataTool\ValueObjects\PropertyCollection;

class PropertyCollectionTest extends TestCase
{
    /**
     * @covers \MetaDataTool\ValueObjects\PropertyCollection
     */
    public function testConstructorIsProtectedAgainstWrongType(): void
    {
        $this->expectException(\TypeError::class);
        new PropertyCollection(new \stdClass());
    }

    /**
     * @covers \MetaDataTool\ValueObjects\PropertyCollection
     */
    public function testObjectIsIterable(): void
    {
        self::assertIsIterable(new PropertyCollection());
    }

    /**
     * @covers \MetaDataTool\ValueObjects\PropertyCollection
     */
    public function testCollectionCanBeCorrectlySerialised(): void
    {
        $property = new Property(
            $name = $this->faker()->name,
            $type = $this->faker()->randomElement(['string', 'int', 'bool']),
            $description = $this->faker()->words(6, true),
            true,
            $methods = HttpMethodMask::all()
        );
        $collection = new PropertyCollection($property);

        self::assertSame(
            json_encode([$property]),
            json_encode($collection)
        );
    }

    /**
     * @covers \MetaDataTool\ValueObjects\PropertyCollection
     */
    public function testCollectionCanBeCorrectlyDeserialised(): void
    {
        $json = (string) json_encode(new PropertyCollection(new Property(
            $name = $this->faker()->name,
            $type = $this->faker()->randomElement(['string', 'int', 'bool']),
            $description = $this->faker()->words(6, true),
            true,
            $methods = HttpMethodMask::all()
        )));

        self::assertEquals(
            new PropertyCollection(new Property(
                $name,
                $type,
                $description,
                true,
                $methods
            )),
            PropertyCollection::jsonDeserialize(json_decode($json, true))
        );
    }
}
