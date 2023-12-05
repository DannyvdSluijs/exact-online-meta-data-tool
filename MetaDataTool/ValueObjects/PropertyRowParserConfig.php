<?php

declare(strict_types=1);

namespace MetaDataTool\ValueObjects;

class PropertyRowParserConfig
{
    public function __construct(
        private int $typeColumnIndex,
        private int $documentationColumnIndex,
        private int $mandatoryColumnIndex
    ) {}

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
