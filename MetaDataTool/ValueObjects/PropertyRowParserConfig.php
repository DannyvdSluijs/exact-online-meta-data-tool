<?php

declare(strict_types=1);

namespace MetaDataTool\ValueObjects;


class PropertyRowParserConfig
{
    /** @var int */
    private $typeColumnIndex;
    /** @var int */
    private $documentationColumnIndex;

    public function __construct(
        $typeColumnIndex,
        $documentationColumnIndex
    ) {
        $this->typeColumnIndex = $typeColumnIndex;
        $this->documentationColumnIndex = $documentationColumnIndex;
    }

    public function getTypeColumnIndex(): int
    {
        return $this->typeColumnIndex;
    }

    public function getDocumentationColumnIndex(): int
    {
        return $this->documentationColumnIndex;
    }
}