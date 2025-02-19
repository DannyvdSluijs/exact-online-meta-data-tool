<?php

declare(strict_types=1);

namespace MetaDataTool\Tests\Unit\ValueObjects;

use MetaDataTool\ValueObjects\HttpMethodMask;
use MetaDataTool\ValueObjects\Property;
use MetaDataTool\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Property::class)]
class PropertyTest extends TestCase
{
    public function testValueObjectHoldsAttributes(): void
    {
        $property = new Property(
            $name = $this->faker()->name,
            $type = $this->faker()->randomElement(['string', 'int', 'bool']),
            $description = $this->faker()->words(6, true),
            true,
            $methods = HttpMethodMask::all(),
            $hidden = false,
            $mandatory = true
        );

        self::assertEquals($name, $property->getName());
        self::assertEquals($type, $property->getType());
        self::assertEquals($description, $property->getDescription());
        self::assertTrue($property->isPrimaryKey());
        self::assertSame($methods, $property->getSupportedHttpMethods());
        self::assertFalse($property->isHidden());
        self::assertTrue($property->isMandatory());
    }

    public function testPropertyCanBeCorrectlySerialised(): void
    {
        $property = new Property(
            $name = $this->faker()->name,
            $type = $this->faker()->randomElement(['string', 'int', 'bool']),
            $description = $this->faker()->words(6, true),
            true,
            $methods = HttpMethodMask::all(),
            $hidden = false,
            $mandatory = true
        );

        self::assertSame(
            json_encode([
                'name' => $name,
                'type' => $type,
                'description' => $description,
                'primaryKey' => true,
                'supportedMethods' => $methods,
                'hidden' => $hidden,
                'mandatory' => $mandatory
            ], JSON_THROW_ON_ERROR),
            json_encode($property, JSON_THROW_ON_ERROR)
        );
    }

    public function testPropertyCanBeCorrectlyDeserialised(): void
    {
        $json = (string) json_encode(new Property(
            $name = $this->faker()->name,
            $type = $this->faker()->randomElement(['string', 'int', 'bool']),
            $description = $this->faker()->words(6, true),
            true,
            $methods = HttpMethodMask::all(),
            $hidden = false,
            $mandatory = true
        ), JSON_THROW_ON_ERROR);

        self::assertEquals(
            new Property(
                $name,
                $type,
                $description,
                true,
                $methods,
                $hidden,
                $mandatory
            ),
            Property::jsonDeserialize(json_decode($json, false, 512, JSON_THROW_ON_ERROR))
        );
    }
}
