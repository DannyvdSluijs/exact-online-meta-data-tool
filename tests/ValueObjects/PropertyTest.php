<?php declare(strict_types=1);

namespace MetaDataTool\Tests\ValueObjects;

use MetaDataTool\ValueObjects\HttpMethodMask;
use MetaDataTool\ValueObjects\Property;
use MetaDataTool\Tests\TestCase;

/**
 * @coversDefaultClass \MetaDataTool\ValueObjects\Property
 */
class PropertyTest extends TestCase
{
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

}
