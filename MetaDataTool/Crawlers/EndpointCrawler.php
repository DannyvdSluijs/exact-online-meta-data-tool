<?php

declare(strict_types=1);

namespace MetaDataTool\Crawlers;

use MetaDataTool\Config\EndpointCrawlerConfig;
use MetaDataTool\Enum\KnownEntities;
use MetaDataTool\PageRegistry;
use MetaDataTool\ValueObjects\HttpMethodMask;
use MetaDataTool\ValueObjects\Property;
use MetaDataTool\ValueObjects\PropertyCollection;
use MetaDataTool\ValueObjects\Endpoint;
use MetaDataTool\ValueObjects\EndpointCollection;
use MetaDataTool\ValueObjects\PropertyRowParserConfig;
use Symfony\Component\DomCrawler\Crawler;

class EndpointCrawler
{
    private const BASE_URL = 'https://start.exactonline.nl/docs/';
    private const ATTRIBUTE_HEADER_XPATH = '//table[@id="referencetable"]/tr[1]';
    private const ATTRIBUTE_ROWS_XPATH = '//table[@id="referencetable"]/tr[position()>1]';

    /** @var EndpointCrawlerConfig */
    private $config;
    /** @var PageRegistry */
    private $pagesToVisit;
    /** @var PageRegistry */
    private $visitedPages;
    /** @var Crawler */
    private $domCrawler;

    public function __construct(EndpointCrawlerConfig $config, ?PageRegistry $pagesToVisit = null)
    {
        $this->config = $config;
        $this->pagesToVisit = $pagesToVisit ?? $this->createDefaultPagesToVisit();
        $this->visitedPages = new PageRegistry();
    }

    private function createDefaultPagesToVisit(): PageRegistry
    {
        $registry = new PageRegistry();
        foreach (KnownEntities::keys() as $entity) {
            $registry->add(self::BASE_URL . 'HlpRestAPIResourcesDetails.aspx?name=' . $entity);
        }

        return $registry;
    }

    public function run(?callable $fn = null): EndpointCollection
    {
        $this->domCrawler = new Crawler();
        $endpoints = new EndpointCollection();

        while ($this->pagesToVisit->hasAny()) {
            $page = $this->pagesToVisit->next();

            if ($this->visitedPages->hasPage($page)) {
                continue;
            }

            $endpoint = $this->crawlWebPage($page);
            if (is_null($endpoint)) {
                continue;
            }
            $endpoints->add($endpoint);

            if (! \is_null($fn)) {
                $fn($endpoint);
            }
        }

        return $endpoints;
    }

    private function crawlWebPage(string $url): ?Endpoint
    {
        $html = $this->fetchHtmlFromUrl($url);
        $this->domCrawler->clear();
        $this->domCrawler->add($html);

        $endpoint = $this->domCrawler->filterXPath('//*[@id="endpoint"]')->first()->text();
        $scope = $this->domCrawler->filterXPath('//*[@id="scope"]')->first()->text();
        try {
            $uri = $this->domCrawler->filterXPath('//*[@id="serviceUri"]')->first()->text();
        } catch (\Exception $exception) {
            return null;
        }
        $supportedMethodsCrawler = $this->domCrawler->filterXPath('//input[@name="supportedmethods"]');
        $httpMethods = HttpMethodMask::none();
        if ($supportedMethodsCrawler->filterXPath('input[@value="GET"]')->count() === 1) {
            $httpMethods = $httpMethods->addGet();
        }
        if ($supportedMethodsCrawler->filterXPath('input[@value="POST"]')->count() === 1) {
            $httpMethods = $httpMethods->addPost();
        }
        if ($supportedMethodsCrawler->filterXPath('input[@value="PUT"]')->count() === 1) {
            $httpMethods = $httpMethods->addPut();
        }
        if ($supportedMethodsCrawler->filterXPath('input[@value="DELETE"]')->count() === 1) {
            $httpMethods = $httpMethods->addDelete();
        }
        $example = $this->domCrawler->filterXPath('//*[@id="exampleGetUri"]')->first()->text();

        $header = $this->domCrawler->filterXPath(self::ATTRIBUTE_HEADER_XPATH);
        if ($header->count() === 0) {
            return null;
        }

        $columns = array_map(static function ($n) {
            return explode(' ', $n->nodeValue)[0];
        }, $header->children()->getIterator()->getArrayCopy());

        $propertyRowParserConfig = new PropertyRowParserConfig(
            array_search('Type', $columns, true) + 1,
            array_search('Description', $columns, true) + 1
        );
        $properties = $this->domCrawler->filterXpath(self::ATTRIBUTE_ROWS_XPATH)
            ->each($this->getPropertyRowParser($propertyRowParserConfig));

        $goodToKnows = $this->domCrawler->filterXPath('//*[@id="goodToKnow"]');
        $deprecationMessage = 'This endpoint is redundant and is going to be removed.';
        $isDeprecated = $goodToKnows->count() > 0 && strpos($goodToKnows->first()->text(), $deprecationMessage) === 0;

        return new Endpoint(
            $endpoint,
            $url,
            $scope,
            $uri,
            $httpMethods,
            $example,
            new PropertyCollection(...$properties),
            $isDeprecated
        );
    }

    private function fetchHtmlFromUrl(string $url): string
    {
        $html = file_get_contents($url);
        $this->visitedPages->add($url);

        if ($html === false) {
            throw new \RuntimeException('Unable to fetch html from ' . $url);
        }

        return $html;
    }

    private function getPropertyRowParser(PropertyRowParserConfig $config): \Closure
    {
        return function (Crawler $node) use ($config): Property {
            if ($node->filterXpath('//td[2]/a')->count() === 1) {
                $this->processDiscoveredUrl(self::BASE_URL . $node->filterXpath('//td[2]/a')->attr('href'));
            }
            $name = trim($node->filterXpath('//td[2]')->text());
            $type = trim($node->filterXpath("//td[{$config->getTypeColumnIndex()}]")->text());
            $description = trim($node->filterXpath("//td[{$config->getDocumentationColumnIndex()}]")->text());
            if ($node->filterXpath("//td[{$config->getDocumentationColumnIndex()}]/a")->count() === 1) {
                $description = $node->filterXpath("//td[{$config->getDocumentationColumnIndex()}]/a")->attr('href');
            }
            $primaryKey = $node->filterXpath('//td[2]/img[@title="Key"]')->count() === 1;

            $httpMethods = HttpMethodMask::none()->addGet();
            $class = (string) $node->attr('class');
            if ($name === 'ID') {
                $httpMethods = $httpMethods->addDelete();
            }
            if (strpos($class, 'hideput') === false && strpos($class, 'showget') === false) {
                $httpMethods = $httpMethods->addPut();
            }
            if (strpos($class, 'hidepost') === false && strpos($class, 'showget') === false) {
                $httpMethods = $httpMethods->addPost();
            }
            if ($name === 'ID') {
                $httpMethods = HttpMethodMask::all();
            }
            $hidden = strpos($node->attr('class') ?? '', 'hiderow') !== false;

            return new Property($name, $type, $description, $primaryKey, $httpMethods, $hidden);
        };
    }

    private function processDiscoveredUrl(string $url): void
    {
        if (!$this->config->shouldQueueDiscoveredLinks()) {
            return;
        }
        if ($this->visitedPages->hasPage($url) || $this->pagesToVisit->hasPage($url)) {
            return;
        }

        $this->pagesToVisit->add($url);
    }
}
