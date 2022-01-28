<?php

declare(strict_types=1);

namespace MetaDataTool;

use MetaDataTool\ValueObjects\Endpoint;
use MetaDataTool\ValueObjects\EndpointCollection;

class JsonFileWriter
{
    private const FILE_NAME = '/meta-data.json';

    /** @var string */
    private $path;

    /**
     * JsonFileWriter constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->ensureDirectoryAvailability($path);
    }

    public function write(EndpointCollection $endpoints): void
    {
        file_put_contents($this->getFullFileName(), json_encode($endpoints, JSON_PRETTY_PRINT));
    }

    public function getFullFileName(): string
    {
        return $this->path . self::FILE_NAME;
    }

    private function ensureDirectoryAvailability(string $path): void
    {
        if (! is_readable($path)) {
            if (! mkdir($path, 0777, true) && ! is_dir($path)) {
                throw new \RuntimeException(sprintf('Unable to create "%s"', $path));
            }
        }
    }
}
