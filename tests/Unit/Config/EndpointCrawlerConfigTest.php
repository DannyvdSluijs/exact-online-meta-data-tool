<?php

declare(strict_types=1);

namespace MetaDataTool\Tests\Unit\Config;

use MetaDataTool\Config\EndpointCrawlerConfig;
use PHPUnit\Framework\TestCase;

class EndpointCrawlerConfigTest extends TestCase
{
    /**
     * @covers \MetaDataTool\Config\EndpointCrawlerConfig
     */
    public function testValueObjectHoldsAttributes(): void
    {
        $config = new EndpointCrawlerConfig(true);

        self::assertTrue($config->shouldQueueDiscoveredLinks());
    }
}