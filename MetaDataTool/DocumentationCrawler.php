<?php declare(strict_types=1);

namespace MetaDataTool;

use MetaDataTool\Enum\KnownEntities;
use MetaDataTool\ValueObjects\HttpMethodMask;
use MetaDataTool\ValueObjects\Property;
use MetaDataTool\ValueObjects\PropertyCollection;
use MetaDataTool\ValueObjects\Endpoint;
use MetaDataTool\ValueObjects\EndpointCollection;
use Symfony\Component\DomCrawler\Crawler;

class DocumentationCrawler
{
    private const BASE_URL = 'https://start.exactonline.nl/docs/';
    private const ATTRIBUTE_HEADER_HAS_WEBHOOK_XPATH = '//table[@id="referencetable"]/tr[1]/th[text()="Webhook"]';
    private const ATTRIBUTE_ROWS_XPATH = '//table[@id="referencetable"]/tr[position()>1]';

    /** @var string[] */
    private $toVisitPages = [];
    /** @var string[] */
    private $visitedPages = [];
    /** @var Crawler */
    private $domCrawler;
    /** @var EndpointCollection */
    private $endpoints;

    public function run(): EndpointCollection
    {
        $this->domCrawler = new Crawler();
        $this->endpoints = new EndpointCollection();

        foreach (KnownEntities::keys() as $entity) {
            $this->toVisitPages[] = strtolower(self::BASE_URL . 'HlpRestAPIResourcesDetails.aspx?name=' . $entity);
        }

        while (count($this->toVisitPages)) {
            /** @var string $page */
            $page = array_shift($this->toVisitPages);

            if (in_array($page, $this->visitedPages, true)) {
                continue;
            }

            $this->endpoints->add($this->crawlWebPage($page));
        }

        return $this->endpoints;
    }

    private function crawlWebPage(string $url): Endpoint
    {
        $html = $this->fetchHtmlFromUrl($url);
        $this->domCrawler->clear();
        $this->domCrawler->add($html);

        $hasWebhookColumn = $this->domCrawler->filterXPath(self::ATTRIBUTE_HEADER_HAS_WEBHOOK_XPATH)->count() === 1;

        $endpoint = $this->domCrawler->filterXPath('//*[@id="endpoint"]')->first()->text();
        $scope = $this->domCrawler->filterXPath('//*[@id="scope"]')->first()->text();
        $uri = $this->domCrawler->filterXPath('//*[@id="serviceUri"]')->first()->text();
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

        $properties = $this->domCrawler->filterXpath(self::ATTRIBUTE_ROWS_XPATH)
            ->each($this->getPropertyRowParser($hasWebhookColumn));

        return new Endpoint($endpoint, $url, $scope, $uri, $httpMethods, $example, new PropertyCollection(...$properties));
    }

    private function fetchHtmlFromUrl(string $url): string
    {
        printf('Fetching "%s"' . PHP_EOL, $url);
        $html = file_get_contents($url);
        $this->visitedPages[] = $url;

        if ($html === false) {
            throw new \RuntimeException('Unable to fetch html from ' . $url);
        }

        return $html;
    }

    private function getPropertyRowParser(bool $hasWebhookColumn): \Closure
    {
        return function (Crawler $node) use ($hasWebhookColumn): Property {

            if ($node->filterXpath('//td[2]/a')->count() === 1) {
                $this->processDiscoveredUrl(self::BASE_URL . $node->filterXpath('//td[2]/a')->attr('href'));
            }
            $name = trim($node->filterXpath('//td[2]')->text());
            $type = trim($node->filterXpath('//td[5]')->text());
            $description = trim($node->filterXpath($hasWebhookColumn ? '//td[7]' : '//td[6]')->text());
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

            return new Property($name, $type, $description, $primaryKey, $httpMethods);
        };
    }

    private function processDiscoveredUrl(string $url): void
    {
        $url = strtolower($url);

        if (in_array($url, $this->visitedPages, true)) {
            return;
        }

        if (in_array($url, $this->toVisitPages, true)) {
            return;
        }

        $this->toVisitPages[] = $url;
    }
}
