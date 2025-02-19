<?php

declare(strict_types=1);

namespace MetaDataTool\Tests\Unit\ValueObjects;

use MetaDataTool\Tests\Unit\TestCase;
use MetaDataTool\ValueObjects\HttpMethodMask;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(HttpMethodMask::class)]
class HttpMethodMaskTest extends TestCase
{
    public function testNamedConstructorAllSupportsAll(): void
    {
        $methods = HttpMethodMask::all();

        $this->assertTrue($methods->supportsGet());
        $this->assertTrue($methods->supportsPost());
        $this->assertTrue($methods->supportsPut());
        $this->assertTrue($methods->supportsDelete());
    }

    public function testNamedConstructorNoneSupportsNone(): void
    {
        $methods = HttpMethodMask::none();

        $this->assertFalse($methods->supportsGet());
        $this->assertFalse($methods->supportsPost());
        $this->assertFalse($methods->supportsPut());
        $this->assertFalse($methods->supportsDelete());
    }

    public function testAddGetReturnsMethodAsSupported(): void
    {
        $methods = HttpMethodMask::none()->addGet();

        $this->assertTrue($methods->supportsGet());
    }

    public function testAddPostReturnsMethodAsSupported(): void
    {
        $methods = HttpMethodMask::none()->addPost();

        $this->assertTrue($methods->supportsPost());
    }

    public function testAddPutReturnsMethodAsSupported(): void
    {
        $methods = HttpMethodMask::none()->addPut();

        $this->assertTrue($methods->supportsPut());
    }

    public function testAddDeleteReturnsMethodAsSupported(): void
    {
        $methods = HttpMethodMask::none()->addDelete();

        $this->assertTrue($methods->supportsDelete());
    }

    public function testPropertyCanBeCorrectlySerialised(): void
    {
        $methods = HttpMethodMask::all();

        self::assertSame(
            json_encode([
                'get' => true,
                'post' => true,
                'put' => true,
                'delete' => true,
            ]),
            json_encode($methods, JSON_THROW_ON_ERROR)
        );
    }

    public function testPropertyCanBeCorrectlyDeserialised(): void
    {
        $json = (string) json_encode(HttpMethodMask::all(), JSON_THROW_ON_ERROR);

        self::assertEquals(
            HttpMethodMask::all(),
            HttpMethodMask::jsonDeserialize(json_decode($json, false, 512, JSON_THROW_ON_ERROR))
        );
    }
}
