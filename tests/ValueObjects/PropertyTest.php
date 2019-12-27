<?php declare(strict_types=1);

namespace MetaDataTool\Tests\ValueObjects;

use MetaDataTool\ValueObjects\HttpMethodMask;
use MetaDataTool\ValueObjects\Property;
use MetaDataTool\Tests\TestCase;

class PropertyTest extends TestCase
{
    /**
     * @covers \MetaDataTool\ValueObjects\Property
     * @covers \MetaDataTool\ValueObjects\HttpMethodMask
     */
    public function testValueObjectHoldsAttributes(): void
    {
        $property = new Property('name', 'type', 'description', true, HttpMethodMask::all());

        self::assertSame('name', $property->getName());
        self::assertSame('type', $property->getType());
        self::assertSame('description', $property->getDescription());
        self::assertTrue($property->isPrimaryKey());
    }

    /**
     * @covers \MetaDataTool\ValueObjects\Property
     * @covers \MetaDataTool\ValueObjects\HttpMethodMask
     */
    public function testPropertyCanBeCorrectlySerialised(): void
    {
        $property = new Property('name', 'type', 'description', true, HttpMethodMask::all());

        self::assertJsonStringEqualsJsonString(
            '{"name":"name","type":"type","description":"description","primaryKey":true,"supportedMethods":{"get":true,"post":true,"put":true,"delete":true} }',
            json_encode($property)
        );
    }

}
