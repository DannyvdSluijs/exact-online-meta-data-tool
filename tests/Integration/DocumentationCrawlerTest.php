<?php

declare(strict_types=1);

namespace MetaDataTool\Tests\Integration;

use MetaDataTool\Config\DocumentationCrawlerConfig;
use MetaDataTool\DocumentationCrawler;
use MetaDataTool\PageRegistry;
use MetaDataTool\ValueObjects\Endpoint;
use MetaDataTool\ValueObjects\Property;
use PHPUnit\Framework\TestCase;

class DocumentationCrawlerTest extends TestCase
{
    /**
     * @covers \MetaDataTool\DocumentationCrawler
     */
    public function testItCanParseCrmAccountPage(): void
    {
        $config = new DocumentationCrawlerConfig(false);
        $registry = new PageRegistry();
        $registry->add('https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=CrmAccounts');
        $crawler = new DocumentationCrawler($config, $registry);

        $result = $crawler->run();
        $endpoints = $result->getIterator()->getArrayCopy();

        self::assertContainsOnlyInstancesOf(Endpoint::class, $endpoints);

        /** @var Endpoint $account */
        $account = array_shift($endpoints);
        $propertyNames = array_map(
            static function (Property $p) { return $p->getName(); },
            $account->getProperties()->getIterator()->getArrayCopy()
        );
        self::assertEquals('Accounts', $account->getEndpoint());
        self::assertEquals('Crm accounts', $account->getScope());
        self::assertEquals('/api/v1/{division}/crm/Accounts', $account->getUri());
        self::assertContains('Accountant', $propertyNames);
        self::assertContains('BSN', $propertyNames);
    }
}
