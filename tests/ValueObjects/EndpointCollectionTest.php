<?php

declare(strict_types=1);

namespace MetaDataTool\Tests\ValueObjects;

use MetaDataTool\Tests\TestCase;
use MetaDataTool\ValueObjects\Endpoint;
use MetaDataTool\ValueObjects\EndpointCollection;
use MetaDataTool\ValueObjects\HttpMethodMask;
use MetaDataTool\ValueObjects\PropertyCollection;

class EndpointCollectionTest extends TestCase
{
    /**
     * @covers \MetaDataTool\ValueObjects\EndpointCollection
     */
    public function testConstructorIsProtectedAgainstWrongType(): void
    {
        $this->expectException(\TypeError::class);
        new EndpointCollection(new \stdClass());
    }

    /**
     * @covers \MetaDataTool\ValueObjects\EndpointCollection
     */
    public function testCanAddEndpointToCollection(): void
    {
        $collection = new EndpointCollection();
        $endpoint = new Endpoint(
            $endpointUrl = $this->faker()->url,
            $documentationUrl = $this->faker()->url,
            $scope = $this->faker()->word,
            $url = $this->faker()->url,
            $methods = HttpMethodMask::all(),
            $exampleUrl = $this->faker()->url,
            $properties = new PropertyCollection()
        );
        $collection->add($endpoint);

        self::assertCount(1, $collection);
        self::assertContains($endpoint, $collection);
    }

    /**
     * @covers \MetaDataTool\ValueObjects\EndpointCollection
     */
    public function testObjectIsIterable(): void
    {
        self::assertIsIterable(new EndpointCollection());
    }

    /**
     * @covers \MetaDataTool\ValueObjects\EndpointCollection
     */
    public function testCollectionCanBeCorrectlySerialised(): void
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
        $collection = new EndpointCollection($endpoint);

        self::assertSame(
            json_encode([$endpoint->getEndpoint() => $endpoint]),
            json_encode($collection)
        );
    }
}
