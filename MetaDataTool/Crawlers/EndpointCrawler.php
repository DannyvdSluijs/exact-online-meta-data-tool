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
    private PageRegistry $pagesToVisit;
    private PageRegistry $visitedPages;
    private ?Crawler $domCrawler = null;

    public function __construct(private EndpointCrawlerConfig $config, ?PageRegistry $pagesToVisit = null)
    {
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
        } catch (\Exception) {
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

        $columns = array_map(static fn($n) => explode(' ', $n->nodeValue)[0], $header->children()->getIterator()->getArrayCopy());

        $propertyRowParserConfig = new PropertyRowParserConfig(
            array_search('Type', $columns, true) + 1,
            array_search('Description', $columns, true) + 1,
            array_search('Mandatory', $columns, true) + 1
        );

        $properties = $this->domCrawler->filterXpath(self::ATTRIBUTE_ROWS_XPATH)
            ->each($this->getPropertyRowParser($propertyRowParserConfig));

        $goodToKnows = $this->domCrawler->filterXPath('//*[@id="goodToKnow"]');
        $deprecationMessage = 'This endpoint is redundant and is going to be removed.';
        $isDeprecated = $goodToKnows->count() > 0 && str_contains($goodToKnows->first()->text(), $deprecationMessage);
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
        [,$basename] = explode('=', $url);
        $filename = sys_get_temp_dir() . '/exact-online-meta-data-tool-' . strtolower($basename) . '.html';

        if (!file_exists($filename)) {
            $html = file_get_contents($url);
            file_put_contents($filename, $html);
        } else {
            $html = file_get_contents($filename);
        }

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
            if (!str_contains($class, 'hideput') && !str_contains($class, 'showget')) {
                $httpMethods = $httpMethods->addPut();
            }
            if (!str_contains($class, 'hidepost') && !str_contains($class, 'showget')) {
                $httpMethods = $httpMethods->addPost();
            }
            if ($name === 'ID') {
                $httpMethods = HttpMethodMask::all();
            }
            $hidden = str_contains($node->attr('class') ?? '', 'hiderow');
            $mandatory = strtolower(trim($node->filterXpath("//td[{$config->getMandatoryColumnIndex()}]")->text())) === 'true';

            return new Property($name, $type, $description, $primaryKey, $httpMethods, $hidden, $mandatory);
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
