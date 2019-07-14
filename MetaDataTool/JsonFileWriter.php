<?php declare(strict_types=1);

namespace MetaDataTool;

use MetaDataTool\ValueObjects\Endpoint;
use MetaDataTool\ValueObjects\EndpointCollection;

class JsonFileWriter
{
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

    public function write(EndpointCollection $endpointCollection): void
    {
        /** @var Endpoint $endpoint */
        foreach ($endpointCollection as $endpoint) {
            $subPath = str_replace(['/api/v1/{division}', '/api/v1/current'], '', $endpoint->getUri());
            $subPath = ucwords($subPath, '/');
            $filename = $this->path . $subPath . '.json';
            $this->ensureDirectoryAvailability(dirname($filename));

            file_put_contents($filename, json_encode($endpoint, JSON_PRETTY_PRINT));
        }
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
