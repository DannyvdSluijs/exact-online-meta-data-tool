<?php

declare(strict_types=1);

namespace MetaDataTool\Tests\Integration;

use MetaDataTool\Config\EndpointCrawlerConfig;
use MetaDataTool\Crawlers\EndpointCrawler;
use MetaDataTool\Crawlers\MainPageCrawler;
use MetaDataTool\DocumentationCrawler;
use MetaDataTool\PageRegistry;
use MetaDataTool\ValueObjects\Endpoint;
use MetaDataTool\ValueObjects\Property;
use PHPUnit\Framework\TestCase;

class MainPageCrawlerCrawlerTest extends TestCase
{
    /**
     * @covers \MetaDataTool\Crawlers\MainPageCrawler
     */
    public function testitCanCrawlMainPage(): void
    {
        $crawler = new MainPageCrawler('https://start.exactonline.nl/docs/HlpRestAPIResources.aspx');

        $result = $crawler->run();

        self::assertTrue($result->hasAny());
        $next = $result->next();
        self::assertStringStartsWith('https://start.exactonline.nl/docs', $next);

    }
}
