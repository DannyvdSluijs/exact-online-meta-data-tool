<?php

declare(strict_types=1);

namespace MetaDataTool\Tests\Unit\Config;

use MetaDataTool\Config\DocumentationCrawlerConfig;
use PHPUnit\Framework\TestCase;

class DocumentationCrawlerConfigTest extends TestCase
{
    /**
     * @covers \MetaDataTool\ValueObjects\Endpoint
     */
    public function testValueObjectHoldsAttributes(): void
    {
        $config = new DocumentationCrawlerConfig(true);

        self::assertTrue($config->shouldQueueDiscoveredLinks());
    }
}