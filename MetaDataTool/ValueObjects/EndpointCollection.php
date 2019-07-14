<?php declare(strict_types=1);


namespace MetaDataTool\ValueObjects;

use ArrayIterator;
use IteratorAggregate;

class EndpointCollection implements IteratorAggregate
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

    public function getIterator()
    {
        return new ArrayIterator($this->endpoints);
    }
}
