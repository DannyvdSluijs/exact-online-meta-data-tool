<?php

declare(strict_types=1);

namespace MetaDataTool\Tests\Unit\ValueObjects;

use MetaDataTool\Tests\Unit\TestCase;
use MetaDataTool\ValueObjects\PropertyRowParserConfig;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PropertyRowParserConfig::class)]
class PropertyRowParserConfigTest extends TestCase
{
    public function testValueObjectHoldsAttributes(): void
    {
        $config = new PropertyRowParserConfig(
            1,
            5,
            4
        );

        self::assertSame(1, $config->getTypeColumnIndex());
        self::assertSame(5, $config->getDocumentationColumnIndex());
        self::assertSame(4, $config->getMandatoryColumnIndex());
    }
}