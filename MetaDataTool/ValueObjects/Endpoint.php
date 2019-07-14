<?php declare(strict_types=1);

namespace MetaDataTool\ValueObjects;

use JsonSerializable;

class Endpoint implements JsonSerializable
{
    /** @var string */
    private $endpoint;
    /** @var string */
    private $scope;
    /** @var string */
    private $uri;
    /** @var HttpMethodMask */
    private $supportedHttpMethods;
    /** @var string */
    private $example;
    /** @var PropertyCollection */
    private $properties;

    public function __construct(
        string $endpoint,
        string $scope,
        string $uri,
        HttpMethodMask $supportedHttpMethods,
        string $example,
        PropertyCollection $properties
    ) {
        $this->endpoint = $endpoint;
        $this->scope = $scope;
        $this->uri = $uri;
        $this->supportedHttpMethods = $supportedHttpMethods;
        $this->example = $example;
        $this->properties = $properties;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function getScope(): string
    {
        return $this->scope;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getSupportedHttpMethods(): HttpMethodMask
    {
        return $this->supportedHttpMethods;
    }

    public function getExample(): string
    {
        return $this->example;
    }


    public function getProperties(): PropertyCollection
    {
        return $this->properties;
    }

    public function jsonSerialize(): array
    {
        return [
            'endpoint' => $this->endpoint,
            'scope' => $this->scope,
            'uri' => $this->uri,
            'supportedMethods' => $this->supportedHttpMethods,
            'example' => $this->example,
            'properties' => $this->properties,
        ];
    }
}
