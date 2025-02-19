<?php

declare(strict_types=1);

namespace MetaDataTool\Tests\Unit\Config;

use MetaDataTool\Config\EndpointCrawlerConfig;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(EndpointCrawlerConfig::class)]
class EndpointCrawlerConfigTest extends TestCase
{
    public function testValueObjectHoldsAttributes(): void
    {
        $config = new EndpointCrawlerConfig(true);

        self::assertTrue($config->shouldQueueDiscoveredLinks());
    }
}