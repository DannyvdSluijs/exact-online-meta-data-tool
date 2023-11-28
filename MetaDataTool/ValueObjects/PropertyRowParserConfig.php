<?php

declare(strict_types=1);

namespace MetaDataTool\ValueObjects;

class PropertyRowParserConfig
{
    /** @var int */
    private $typeColumnIndex;
    /** @var int */
    private $documentationColumnIndex;
    /** @var int */
    private $mandatoryColumnIndex;

    public function __construct(
        $typeColumnIndex,
        $documentationColumnIndex,
        $mandatoryColumnIndex
    ) {
        $this->typeColumnIndex = $typeColumnIndex;
        $this->documentationColumnIndex = $documentationColumnIndex;
        $this->mandatoryColumnIndex = $mandatoryColumnIndex;
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
