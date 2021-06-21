<?php declare(strict_types=1);

namespace MetaDataTool\Tests\Unit\ValueObjects;

use MetaDataTool\Tests\Unit\TestCase;
use MetaDataTool\ValueObjects\HttpMethodMask;

class HttpMethodMaskTest extends TestCase
{
    /**
     * @covers \MetaDataTool\ValueObjects\HttpMethodMask
     */
    public function testNamedConstructorAllSupportsAll(): void
    {
        $methods = HttpMethodMask::all();

        $this->assertTrue($methods->supportsGet());
        $this->assertTrue($methods->supportsPost());
        $this->assertTrue($methods->supportsPut());
        $this->assertTrue($methods->supportsDelete());
    }

    /**
     * @covers \MetaDataTool\ValueObjects\HttpMethodMask
     */
    public function testNamedConstructorNoneSupportsNone(): void
    {
        $methods = HttpMethodMask::none();

        $this->assertFalse($methods->supportsGet());
        $this->assertFalse($methods->supportsPost());
        $this->assertFalse($methods->supportsPut());
        $this->assertFalse($methods->supportsDelete());
    }

    /**
     * @covers \MetaDataTool\ValueObjects\HttpMethodMask
     */
    public function testAddGetReturnsMethodAsSupported(): void
    {
        $methods = HttpMethodMask::none()->addGet();

        $this->assertTrue($methods->supportsGet());
    }

    /**
     * @covers \MetaDataTool\ValueObjects\HttpMethodMask
     */
    public function testAddPostReturnsMethodAsSupported(): void
    {
        $methods = HttpMethodMask::none()->addPost();

        $this->assertTrue($methods->supportsPost());
    }

    /**
     * @covers \MetaDataTool\ValueObjects\HttpMethodMask
     */
    public function testAddPutReturnsMethodAsSupported(): void
    {
        $methods = HttpMethodMask::none()->addPut();

        $this->assertTrue($methods->supportsPut());
    }

    /**
     * @covers \MetaDataTool\ValueObjects\HttpMethodMask
     */
    public function testAddDeleteReturnsMethodAsSupported(): void
    {
        $methods = HttpMethodMask::none()->addDelete();

        $this->assertTrue($methods->supportsDelete());
    }

    /**
     * @covers \MetaDataTool\ValueObjects\HttpMethodMask
     */
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
            json_encode($methods)
        );
    }
}
