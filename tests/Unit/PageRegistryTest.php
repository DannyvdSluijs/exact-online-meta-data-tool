<?php

declare(strict_types=1);

namespace MetaDataTool\Tests\Unit;

use MetaDataTool\Exception\Exception;
use MetaDataTool\PageRegistry;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PageRegistry::class)]
class PageRegistryTest extends TestCase
{
    public function testHasPageReturnsFalseWithNewRegistry(): void
    {
        $registry = new PageRegistry();

        self::assertFalse($registry->hasPage('www.example.org'));
    }

    public function testHasPageReturnsTrueWithPageAdded(): void
    {
        $page = 'https://www.example.org';
        $registry = new PageRegistry();
        $registry->add($page);

        self::assertTrue($registry->hasPage($page));
    }

    public function testHasAnyReturnsFalseWithNewRegistry(): void
    {
        $registry = new PageRegistry();

        self::assertFalse($registry->hasAny());
    }

    public function testHasAnyReturnsTrueWithPageAdded(): void
    {
        $page = 'https://www.example.org';
        $registry = new PageRegistry();
        $registry->add($page);

        self::assertTrue($registry->hasAny());
    }

    public function testNextReturnsPageWithPageAdded(): void
    {
        $page = 'https://www.example.org';
        $registry = new PageRegistry();
        $registry->add($page);

        self::assertEquals($page, $registry->next());
    }

    public function testNextThrowsExceptionWithNewRegistry(): void
    {
        $registry = new PageRegistry();

        $this->expectException(Exception::class);
        $registry->next();
    }

    public function testCountReturnsCorrectValue(): void
    {
        $registry = new PageRegistry();
        $registry->add('https://www.example.org');
        $registry->add('https://www.example.org/1');
        $registry->add('https://www.example.org/2');

        self::assertEquals(3, $registry->count());
    }

    public function testGetIteratorReturnCorrectIterator(): void
    {
        $pages = [
            'https://www.example.org',
            'https://www.example.org/1',
            'https://www.example.org/2',
        ];
        $registry = new PageRegistry(...$pages);

        self::assertEquals($pages, (array) $registry->getIterator());
    }
}
