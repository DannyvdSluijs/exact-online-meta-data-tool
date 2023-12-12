<?php

declare(strict_types=1);

namespace MetaDataTool\ValueObjects;

use JsonSerializable;

class Endpoint implements JsonSerializable
{
    public function __construct(
        private string $endpoint,
        private string $documentation,
        private string $scope,
        private string $uri,
        private HttpMethodMask $supportedHttpMethods,
        private string $example,
        private PropertyCollection $properties,
        private bool $isDeprecated = false
    ) {}

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
