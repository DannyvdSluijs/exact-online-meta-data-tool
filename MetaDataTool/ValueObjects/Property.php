<?php declare(strict_types=1);

namespace MetaDataTool\ValueObjects;

use JsonSerializable;

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

    public function __construct(string $name, string $type, string $description, bool $primaryKey = false)
    {
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->primaryKey = $primaryKey;
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

    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,
            'primaryKey' => $this->primaryKey,
        ];
    }
}
