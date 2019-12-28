<?php

declare(strict_types=1);

namespace MetaDataTool\Tests\ValueObjects;

use MetaDataTool\ValueObjects\Endpoint;
use MetaDataTool\ValueObjects\HttpMethodMask;
use MetaDataTool\ValueObjects\PropertyCollection;
use MetaDataTool\Tests\TestCase;

/**
 * @coversDefaultClass \MetaDataTool\ValueObjects\Endpoint
 */
class EndpointTest extends TestCase
{
    public function testValueObjectHoldsAttributes(): void
    {
        $endpoint = new Endpoint(
            $endpointUrl = $this->faker()->url,
            $documentationUrl = $this->faker()->url,
            $scope = $this->faker()->word,
            $url = $this->faker()->url,
            $methods = HttpMethodMask::all(),
            $exampleUrl = $this->faker()->url,
            $properties = new PropertyCollection()
        );

        self::assertSame($endpointUrl, $endpoint->getEndpoint());
        self::assertSame($documentationUrl, $endpoint->getDocumentation());
        self::assertSame($scope, $endpoint->getScope());
        self::assertSame($url, $endpoint->getUri());
        self::assertEquals($methods, $endpoint->getSupportedHttpMethods());
        self::assertSame($exampleUrl, $endpoint->getExample());
        self::assertEquals($properties, $endpoint->getProperties());
    }

    public function testPropertyCanBeCorrectlySerialised(): void
    {
        $endpoint = new Endpoint(
            $endpointUrl = $this->faker()->url,
            $documentationUrl = $this->faker()->url,
            $scope = $this->faker()->word,
            $url = $this->faker()->url,
            $methods = HttpMethodMask::all(),
            $exampleUrl = $this->faker()->url,
            $properties = new PropertyCollection()
        );

        self::assertSame(
            json_encode([
                'endpoint' => $endpointUrl,
                'documentation' => $documentationUrl,
                'scope' => $scope,
                'uri' => $url,
                'supportedMethods' => $methods,
                'example' => $exampleUrl,
                'properties' => $properties,
            ]),
            json_encode($endpoint)
        );
    }
}
