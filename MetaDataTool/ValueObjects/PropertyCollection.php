<?php

declare(strict_types=1);

namespace MetaDataTool\ValueObjects;

use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;

class PropertyCollection implements IteratorAggregate, JsonSerializable
{
    /** @var Property[] */
    private $properties;

    public function __construct(Property ...$properties)
    {
        $this->properties = $properties;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->properties);
    }

    public function jsonSerialize(): array
    {
        return $this->properties;
    }
}
