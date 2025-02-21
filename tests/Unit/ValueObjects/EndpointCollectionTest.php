<?php

declare(strict_types=1);

namespace MetaDataTool\Tests\Unit\ValueObjects;

use MetaDataTool\Tests\Unit\TestCase;
use MetaDataTool\ValueObjects\Endpoint;
use MetaDataTool\ValueObjects\EndpointCollection;
use MetaDataTool\ValueObjects\HttpMethodMask;
use MetaDataTool\ValueObjects\PropertyCollection;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(EndpointCollection::class)]
class EndpointCollectionTest extends TestCase
{
    public function testConstructorIsProtectedAgainstWrongType(): void
    {
        $this->expectException(\TypeError::class);
        new EndpointCollection(new \stdClass());
    }

    public function testCanAddEndpointToCollection(): void
    {
        $collection = new EndpointCollection();
        $endpoint = new Endpoint(
            $this->faker()->url,
            $this->faker()->url,
            $this->faker()->word,
            $this->faker()->url,
            HttpMethodMask::all(),
            $this->faker()->url,
            new PropertyCollection()
        );
        $collection->add($endpoint);

        self::assertCount(1, $collection);
        self::assertContains($endpoint, $collection);
    }

    public function testObjectIsIterable(): void
    {
        self::assertIsIterable(new EndpointCollection());
    }

    public function testCollectionCanBeCorrectlySerialised(): void
    {
        $endpoint = new Endpoint(
            $this->faker()->url,
            $this->faker()->url,
            $this->faker()->word,
            $this->faker()->url,
            HttpMethodMask::all(),
            $this->faker()->url,
            new PropertyCollection(),
            $this->faker()->boolean()
        );
        $collection = new EndpointCollection($endpoint);

        self::assertSame(
            json_encode([$endpoint->getUri() => $endpoint], JSON_THROW_ON_ERROR),
            json_encode($collection, JSON_THROW_ON_ERROR)
        );
    }

    public function testCollectionCanBeCorrectlyDeserialised(): void
    {
        $json = (string) json_encode(new EndpointCollection(new Endpoint(
            $endpointUrl = $this->faker()->url,
            $documentationUrl = $this->faker()->url,
            $scope = $this->faker()->word,
            $url = $this->faker()->url,
            $methods = HttpMethodMask::all(),
            $exampleUrl = $this->faker()->url,
            $properties = new PropertyCollection(),
            $deprecated = $this->faker()->boolean()
        )), JSON_THROW_ON_ERROR);

        self::assertEquals(
            new EndpointCollection(new Endpoint(
                $endpointUrl,
                $documentationUrl,
                $scope,
                $url,
                $methods,
                $exampleUrl,
                $properties,
                $deprecated
            )),
            EndpointCollection::jsonDeserialize(json_decode($json, false, 512, JSON_THROW_ON_ERROR))
        );
    }
}
