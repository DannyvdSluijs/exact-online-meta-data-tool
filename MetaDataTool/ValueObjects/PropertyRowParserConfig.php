<?php

declare(strict_types=1);

namespace MetaDataTool\ValueObjects;

class PropertyRowParserConfig
{
    /**
     * @param int $typeColumnIndex
     * @param int $documentationColumnIndex
     * @param int $mandatoryColumnIndex
     */
    public function __construct(private $typeColumnIndex, private $documentationColumnIndex, private $mandatoryColumnIndex)
    {
    }

    public function getTypeColumnIndex(): int
    {
        return $this->typeColumnIndex;
    }

    public function getDocumentationColumnIndex(): int
    {
        return $this->documentationColumnIndex;
    }

    public function getMandatoryColumnIndex(): int
    {
        return $this->mandatoryColumnIndex;
    }
}
