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

    /**
     * Attribute constructor.
     * @param string $name
     * @param string $type
     * @param string $description
     */
    public function __construct(string $name, string $type, string $description)
    {
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,
        ];
    }
}
