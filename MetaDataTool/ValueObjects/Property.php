<?php

declare(strict_types=1);

namespace MetaDataTool\ValueObjects;

use JsonSerializable;
use stdClass;

class Property implements JsonSerializable
{
    /** @var string */
    private $name;
    /** @var string */
    private $type;
    /** @var string */
    private $description;
    /** @var bool */
    private $primaryKey;
    /** @var HttpMethodMask */
    private $supportedHttpMethods;

    public function __construct(
        string $name,
        string $type,
        string $description,
        bool $primaryKey,
        HttpMethodMask $supportedHttpMethods
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->primaryKey = $primaryKey;
        $this->supportedHttpMethods = $supportedHttpMethods;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isPrimaryKey(): bool
    {
        return $this->primaryKey;
    }

    public function getSupportedHttpMethods(): HttpMethodMask
    {
        return $this->supportedHttpMethods;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,
            'primaryKey' => $this->primaryKey,
            'supportedMethods' => $this->supportedHttpMethods,
        ];
    }

    public static function jsonDeserialize(stdClass $jsonProperty): self
    {
        return new self(
            $jsonProperty->name,
            $jsonProperty->type,
            $jsonProperty->description,
            $jsonProperty->primaryKey,
            HttpMethodMask::jsonDeserialize($jsonProperty->supportedMethods)
        );
    }
}
