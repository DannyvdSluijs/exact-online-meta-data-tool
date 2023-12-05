<?php

declare(strict_types=1);

namespace MetaDataTool\Config;

class EndpointCrawlerConfig
{
    public function __construct(private bool $queueDiscoveredLinks)
    {
    }

    public function shouldQueueDiscoveredLinks(): bool
    {
        return $this->queueDiscoveredLinks;
    }
}
