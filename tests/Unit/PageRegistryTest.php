<?php

declare(strict_types=1);

namespace MetaDataTool\Tests\Unit;

use MetaDataTool\Exception\Exception;
use MetaDataTool\PageRegistry;
use PHPUnit\Framework\TestCase;

class PageRegistryTest extends TestCase
{
    /**
     * @covers \MetaDataTool\PageRegistry
     */
    public function testHasPageReturnsFalseWithNewRegistry(): void
    {
        $registry = new PageRegistry();

        self::assertFalse($registry->hasPage('www.example.org'));
    }

    /**
     * @covers \MetaDataTool\PageRegistry
     */
    public function testHasPageReturnsTrueWithPageAdded(): void
    {
        $page = 'https://www.example.org';
        $registry = new PageRegistry();
        $registry->add($page);

        self::assertTrue($registry->hasPage($page));
    }

    /**
     * @covers \MetaDataTool\PageRegistry
     */
    public function testHasAnyReturnsFalseWithNewRegistry(): void
    {
        $registry = new PageRegistry();

        self::assertFalse($registry->hasAny());
    }

    /**
     * @covers \MetaDataTool\PageRegistry
     */
    public function testHasAnyReturnsTrueWithPageAdded(): void
    {
        $page = 'https://www.example.org';
        $registry = new PageRegistry();
        $registry->add($page);

        self::assertTrue($registry->hasAny());
    }

    /**
     * @covers \MetaDataTool\PageRegistry
     */
    public function testNextReturnsPageWithPageAdded(): void
    {
        $page = 'https://www.example.org';
        $registry = new PageRegistry();
        $registry->add($page);

        self::assertEquals($page, $registry->next());
    }

    /**
     * @covers \MetaDataTool\PageRegistry
     */
    public function testNextThrowsExceptionWithNewRegistry(): void
    {
        $registry = new PageRegistry();

        $this->expectException(Exception::class);
        $registry->next();
    }
}
