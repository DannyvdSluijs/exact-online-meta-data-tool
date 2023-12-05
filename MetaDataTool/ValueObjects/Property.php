<?php

declare(strict_types=1);

namespace MetaDataTool\ValueObjects;

use JsonSerializable;
use stdClass;

class Property implements JsonSerializable
{
    public function __construct(
        private string $name,
        private string $type,
        private string $description,
        private bool $primaryKey,
        private HttpMethodMask $supportedHttpMethods,
        private bool $hidden,
        private bool $mandatory
    ) {}

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

    public function isHidden(): bool
    {
        return $this->hidden;
    }

    public function isMandatory(): bool
    {
        return $this->mandatory;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,
            'primaryKey' => $this->primaryKey,
            'supportedMethods' => $this->supportedHttpMethods,
            'hidden' => $this->hidden,
            'mandatory' => $this->mandatory,
        ];
    }

    public static function jsonDeserialize(stdClass $jsonProperty): self
    {
        return new self(
            $jsonProperty->name,
            $jsonProperty->type,
            $jsonProperty->description,
            $jsonProperty->primaryKey,
            HttpMethodMask::jsonDeserialize((object) $jsonProperty->supportedMethods),
            $jsonProperty->hidden,
            $jsonProperty->mandatory
        );
    }
}
