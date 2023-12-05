<?php

declare(strict_types=1);

namespace MetaDataTool\Crawlers;

use MetaDataTool\PageRegistry;
use Symfony\Component\DomCrawler\Crawler;

class MainPageCrawler
{
    public function __construct(private string $mainPage) {}

    public function run(): PageRegistry
    {
        $mainPage = $this->mainPage;
        $html = $this->fetchHtmlFromUrl($mainPage);

        $domCrawler = new Crawler();
        $domCrawler->clear();
        $domCrawler->add($html);
        $endpoints = $domCrawler->filterXPath('//*[@id="referencetable"]/tr/td[2]/a')
            ->each(function (Crawler $node) use ($mainPage): string {
                $script = substr($mainPage, strrpos($mainPage, '/') + 1);
                return str_replace($script, (string) $node->attr('href'), $mainPage);
            });


        return new PageRegistry(...$endpoints);
    }

    private function fetchHtmlFromUrl(string $url): string
    {
        $html = file_get_contents($url);

        if ($html === false) {
            throw new \RuntimeException('Unable to fetch html from ' . $url);
        }

        return $html;
    }
}
