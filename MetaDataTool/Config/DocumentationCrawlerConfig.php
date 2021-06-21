<?php

declare(strict_types=1);

namespace MetaDataTool\Config;

class DocumentationCrawlerConfig
{
    /** @var bool */
    private $queueDiscoveredLinks;

    public function __construct(bool $queueDiscoveredLinks)
    {
        $this->queueDiscoveredLinks = $queueDiscoveredLinks;
    }

    public function shouldQueueDiscoveredLinks(): bool
    {
        return $this->queueDiscoveredLinks;
    }
}