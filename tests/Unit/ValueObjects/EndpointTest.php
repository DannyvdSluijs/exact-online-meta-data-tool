<?php

declare(strict_types=1);

namespace MetaDataTool\Tests\Unit\ValueObjects;

use MetaDataTool\ValueObjects\Endpoint;
use MetaDataTool\ValueObjects\HttpMethodMask;
use MetaDataTool\ValueObjects\PropertyCollection;
use MetaDataTool\Tests\Unit\TestCase;

class EndpointTest extends TestCase
{
    /**
     * @covers \MetaDataTool\ValueObjects\Endpoint
     */
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

    /**
     * @covers \MetaDataTool\ValueObjects\Endpoint
     */
    public function testPropertyCanBeCorrectlySerialised(): void
    {
        $endpoint = new Endpoint(
            $endpointUrl = $this->faker()->url,
            $documentationUrl = $this->faker()->url,
            $scope = $this->faker()->word,
            $url = $this->faker()->url,
            $methods = HttpMethodMask::all(),
            $exampleUrl = $this->faker()->url,
            $properties = new PropertyCollection(),
            $isDeprecated = $this->faker()->boolean()
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
                'deprecated' => $isDeprecated
            ]),
            json_encode($endpoint)
        );
    }

    /**
     * @covers \MetaDataTool\ValueObjects\Endpoint
     */
    public function testPropertyCanBeCorrectlyDeserialised(): void
    {
        $json = (string) json_encode(new Endpoint(
            $endpointUrl = $this->faker()->url,
            $documentationUrl = $this->faker()->url,
            $scope = $this->faker()->word,
            $url = $this->faker()->url,
            $methods = HttpMethodMask::all(),
            $exampleUrl = $this->faker()->url,
            $properties = new PropertyCollection()
        ));

        self::assertEquals(
            new Endpoint(
                $endpointUrl,
                $documentationUrl,
                $scope,
                $url,
                $methods,
                $exampleUrl,
                $properties
            ),
            Endpoint::jsonDeserialize(json_decode($json, false))
        );
    }
}
