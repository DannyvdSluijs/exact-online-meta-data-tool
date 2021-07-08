<?php

declare(strict_types=1);

namespace MetaDataTool\ValueObjects;

use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use stdClass;

class EndpointCollection implements IteratorAggregate, JsonSerializable
{
    /** @var Endpoint[] */
    private $endpoints;

    public function __construct(Endpoint ...$endpoints)
    {
        $this->endpoints = $endpoints;
    }

    public function add(Endpoint $resource): void
    {
        $this->endpoints[] = $resource;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->endpoints);
    }

    public function jsonSerialize(): array
    {
        $endpoints = [];
        foreach ($this->endpoints as $endpoint) {
            $endpoints[$endpoint->getEndpoint()] = $endpoint;
        }

        return $endpoints;
    }

    public static function jsonDeserialize(\stdClass $jsonCollection): self
    {
        $collection = new self();

        foreach ($jsonCollection as $jsonEndpoint) {
            $collection->add(Endpoint::jsonDeserialize($jsonEndpoint));
        }

        return $collection;
    }
}
