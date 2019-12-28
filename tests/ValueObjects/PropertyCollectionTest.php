<?php

declare(strict_types=1);

namespace MetaDataTool\Tests\ValueObjects;

use MetaDataTool\Tests\TestCase;
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
     * @covers \MetaDataTool\ValueObjects\EndpointCollection
     */
    public function testObjectIsIterable(): void
    {
        self::assertIsIterable(new PropertyCollection());
    }

    /**
     * @covers \MetaDataTool\ValueObjects\EndpointCollection
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
}
