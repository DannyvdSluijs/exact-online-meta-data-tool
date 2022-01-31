<?php

declare(strict_types=1);

namespace MetaDataTool;

use JetBrains\PhpStorm\Internal\TentativeType;
use MetaDataTool\Exception\Exception;
use Traversable;

class PageRegistry implements \Countable, \IteratorAggregate
{
    /** @var string[] */
    private $pages = [];

    public function __construct(string ...$pages)
    {
        $this->pages = $pages;
    }

    public function hasPage(string $pageName): bool
    {
        return array_key_exists($this->sanitizePageName($pageName), $this->pages);
    }

    public function hasAny(): bool
    {
        return count($this->pages) > 0;
    }

    public function add(string $pageName): void
    {
        $this->pages[$this->sanitizePageName($pageName)] = $pageName;
    }

    public function next(): string
    {
        if (count($this->pages) === 0) {
            throw new Exception('Page registry is empty');
        }

        return array_shift($this->pages);
    }

    private function sanitizePageName(string $pageName): string
    {
        return strtolower($pageName);
    }

    public function count(): int
    {
        return count($this->pages);
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->pages);
    }
}
