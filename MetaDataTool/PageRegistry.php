<?php

declare(strict_types=1);

namespace MetaDataTool;

use MetaDataTool\Exception\Exception;

class PageRegistry
{
    /** @var string[] */
    private $pages = [];

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
}
