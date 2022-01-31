<?php

declare(strict_types=1);

namespace MetaDataTool\Tests\Unit;

use MetaDataTool\JsonFileWriter;
use MetaDataTool\ValueObjects\EndpointCollection;

class JsonFileWriterTest extends TestCase
{
    /**
     * @covers \MetaDataTool\JsonFileWriter
     */
    public function testCanWriteToAnExistingDirectory(): void
    {
        $writer = new JsonFileWriter(sys_get_temp_dir());
        $writer->write(new EndpointCollection());

        $this->assertFileExists(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'meta-data.json');
    }

    /**
     * @covers \MetaDataTool\JsonFileWriter
     */
    public function testCanWriteToNewDirectory(): void
    {
        $subDir = $this->faker()->domainWord;
        $writer = new JsonFileWriter(sys_get_temp_dir() . DIRECTORY_SEPARATOR . $subDir);
        $writer->write(new EndpointCollection());

        $this->assertFileExists(
            sys_get_temp_dir() . DIRECTORY_SEPARATOR . $subDir . DIRECTORY_SEPARATOR . 'meta-data.json'
        );
    }

    /**
     * @covers \MetaDataTool\JsonFileWriter
     */
    public function testThrowsExceptionOnUncreatableDirectory(): void
    {
        $this->expectException(\RuntimeException::class);
        new JsonFileWriter('/bla/bla/bla');
    }
}
