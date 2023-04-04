<?php

declare(strict_types=1);

namespace MetaDataTool\ValueObjects;

use JsonSerializable;

class Endpoint implements JsonSerializable
{
    /** @var string */
    private $endpoint;
    /** @var string */
    private $documentation;
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
    /** @var bool */
    private $isDeprecated;

    public function __construct(
        string $endpoint,
        string $documentation,
        string $scope,
        string $uri,
        HttpMethodMask $supportedHttpMethods,
        string $example,
        PropertyCollection $properties,
        bool $isDeprecated = false
    ) {
        $this->endpoint = $endpoint;
        $this->documentation = $documentation;
        $this->scope = $scope;
        $this->uri = $uri;
        $this->supportedHttpMethods = $supportedHttpMethods;
        $this->example = $example;
        $this->properties = $properties;
        $this->isDeprecated = $isDeprecated;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function getDocumentation(): string
    {
        return $this->documentation;
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

    public function isDeprecated(): bool
    {
        return $this->isDeprecated;
    }

    public function jsonSerialize(): array
    {
        return [
            'endpoint' => $this->endpoint,
            'documentation' => $this->documentation,
            'scope' => $this->scope,
            'uri' => $this->uri,
            'supportedMethods' => $this->supportedHttpMethods,
            'example' => $this->example,
            'properties' => $this->properties,
            'deprecated' => $this->isDeprecated,
        ];
    }

    public static function jsonDeserialize(\stdClass $jsonEndpoint): self
    {
        return new self(
            $jsonEndpoint->endpoint,
            $jsonEndpoint->documentation,
            $jsonEndpoint->scope,
            $jsonEndpoint->uri,
            HttpMethodMask::jsonDeserialize($jsonEndpoint->supportedMethods),
            $jsonEndpoint->example,
            PropertyCollection::jsonDeserialize($jsonEndpoint->properties),
            $jsonEndpoint->deprecated
        );
    }
}
