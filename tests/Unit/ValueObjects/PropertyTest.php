<?php

declare(strict_types=1);

namespace MetaDataTool\Tests\Unit\ValueObjects;

use MetaDataTool\ValueObjects\HttpMethodMask;
use MetaDataTool\ValueObjects\Property;
use MetaDataTool\Tests\Unit\TestCase;

class PropertyTest extends TestCase
{
    /**
     * @covers \MetaDataTool\ValueObjects\Property
     */
    public function testValueObjectHoldsAttributes(): void
    {
        $property = new Property(
            $name = $this->faker()->name,
            $type = $this->faker()->randomElement(['string', 'int', 'bool']),
            $description = $this->faker()->words(6, true),
            true,
            $methods = HttpMethodMask::all()
        );

        self::assertEquals($name, $property->getName());
        self::assertEquals($type, $property->getType());
        self::assertEquals($description, $property->getDescription());
        self::assertTrue($property->isPrimaryKey());
        self::assertSame($methods, $property->getSupportedHttpMethods());
    }

    /**
     * @covers \MetaDataTool\ValueObjects\Property
     */
    public function testPropertyCanBeCorrectlySerialised(): void
    {
        $property = new Property(
            $name = $this->faker()->name,
            $type = $this->faker()->randomElement(['string', 'int', 'bool']),
            $description = $this->faker()->words(6, true),
            true,
            $methods = HttpMethodMask::all()
        );

        self::assertSame(
            json_encode([
                'name' => $name,
                'type' => $type,
                'description' => $description,
                'primaryKey' => true,
                'supportedMethods' => $methods,
            ]),
            json_encode($property)
        );
    }

    /**
     * @covers \MetaDataTool\ValueObjects\Property
     */
    public function testPropertyCanBeCorrectlyDeserialised(): void
    {
        $json = (string) json_encode(new Property(
            $name = $this->faker()->name,
            $type = $this->faker()->randomElement(['string', 'int', 'bool']),
            $description = $this->faker()->words(6, true),
            true,
            $methods = HttpMethodMask::all()
        ));

        self::assertEquals(
            new Property(
                $name,
                $type,
                $description,
                true,
                $methods
            ),
            Property::jsonDeserialize(json_decode($json, false))
        );
    }
}
