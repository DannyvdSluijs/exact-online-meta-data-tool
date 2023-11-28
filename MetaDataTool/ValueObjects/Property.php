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
    /** @var boolean */
    private $hidden;
    /** @var boolean */
    private $mandatory;

    public function __construct(
        string $name,
        string $type,
        string $description,
        bool $primaryKey,
        HttpMethodMask $supportedHttpMethods,
        bool $hidden,
        bool $mandatory
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->primaryKey = $primaryKey;
        $this->supportedHttpMethods = $supportedHttpMethods;
        $this->hidden = $hidden;
        $this->mandatory = $mandatory;
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
