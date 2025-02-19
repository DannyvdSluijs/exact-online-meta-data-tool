<?php

declare(strict_types=1);

namespace MetaDataTool\Tests\Integration;

use MetaDataTool\Config\EndpointCrawlerConfig;
use MetaDataTool\Crawlers\EndpointCrawler;
use MetaDataTool\PageRegistry;
use MetaDataTool\ValueObjects\Endpoint;
use MetaDataTool\ValueObjects\Property;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(EndpointCrawler::class)]
class EndpointCrawlerTest extends TestCase
{
    public function testItCanParseCrmAccountPage(): void
    {
        $config = new EndpointCrawlerConfig(false);
        $registry = new PageRegistry();
        $registry->add('https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=CrmAccounts');
        $crawler = new EndpointCrawler($config, $registry);

        $result = $crawler->run();
        $endpoints = $result->getIterator()->getArrayCopy();

        self::assertContainsOnlyInstancesOf(Endpoint::class, $endpoints);

        /** @var Endpoint $account */
        $account = array_shift($endpoints);
        $propertyNames = array_map(
            static fn(Property $p) => $p->getName(),
            $account->getProperties()->getIterator()->getArrayCopy()
        );
        self::assertEquals('Accounts', $account->getEndpoint());
        self::assertEquals('Crm accounts', $account->getScope());
        self::assertEquals('/api/v1/{division}/crm/Accounts', $account->getUri());
        self::assertContains('Accountant', $propertyNames);
        self::assertContains('BSN', $propertyNames);
    }

    public function testItCanDetectHiddenIsSerialNumberProperty(): void
    {
        $config = new EndpointCrawlerConfig(false);
        $registry = new PageRegistry();
        $registry->add('https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=LogisticsItems');
        $crawler = new EndpointCrawler($config, $registry);

        $result = $crawler->run();
        $endpoints = $result->getIterator()->getArrayCopy();

        /** @var Endpoint $itemEndpoint */
        $itemEndpoint = array_shift($endpoints);
        /** @var Property[] $properties */
        $properties = $itemEndpoint->getProperties()->getIterator()->getArrayCopy();
        $matches = array_filter($properties, fn($prop) => $prop->getName() === 'IsSerialNumberItem');
        $isSerialNumberProperty = array_shift($matches);

        self::assertNotNull($isSerialNumberProperty);
        self::assertTrue($isSerialNumberProperty->isHidden());
    }

    public function testInvokesCallbackOnEndpointDiscovery(): void
    {
        $documentation = 'https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=LogisticsItems';
        $config = new EndpointCrawlerConfig(false);
        $registry = new PageRegistry();
        $registry->add($documentation);
        $crawler = new EndpointCrawler($config, $registry);
        $pointer = null;

        $crawler->run(static function (?Endpoint $endpoint) use (&$pointer) {
            $pointer = $endpoint;
        });

        self::assertNotNull($pointer);
        self::assertEquals('Items', $pointer->getEndpoint());
    }
}
