<?php declare(strict_types=1);

namespace MetaDataTool\Tests\ValueObjects;

use MetaDataTool\ValueObjects\Property;
use PHPUnit\Framework\TestCase;

class PropertyTest extends TestCase
{
    /**
     * @covers \MetaDataTool\ValueObjects\Property
     */
    public function testValueObjectHoldsAttributes(): void
    {
        $property = new Property('name', 'type', 'description', true);

        self::assertSame('name', $property->getName());
        self::assertSame('type', $property->getType());
        self::assertSame('description', $property->getDescription());
        self::assertTrue($property->isPrimaryKey());
    }

    /**
     * @covers \MetaDataTool\ValueObjects\Property
     */
    public function testPropertyCanBeCorrectlySerialised(): void
    {
        $property = new Property('name', 'type', 'description', true);

        self::assertJsonStringEqualsJsonString(
            '{"name":"name","type":"type","description":"description","primaryKey":true}',
            json_encode($property)
        );
    }

}
