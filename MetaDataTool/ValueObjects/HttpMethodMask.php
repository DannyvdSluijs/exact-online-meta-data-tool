<?php declare(strict_types=1);


namespace MetaDataTool\ValueObjects;

use JsonSerializable;

class HttpMethodMask implements JsonSerializable
{
    private const NONE = 0;
    private const GET = 1;
    private const POST = 2;
    private const PUT = 4;
    private const DELETE = 8;
    private const ALL = 15;

    /** @var int */
    private $mask;

    private function __construct(int $mask)
    {
        $this->mask = $mask;
    }

    public static function none(): self
    {
        return new static(self::NONE);
    }

    public static function all(): self
    {
        return new static(self::ALL);
    }

    public function addGet(): self
    {
        return new self($this->mask | self::GET);
    }

    public function addPost(): self
    {
        return new self($this->mask | self::POST);
    }

    public function addPut(): self
    {
        return new self($this->mask | self::PUT);
    }

    public function addDelete(): self
    {
        return new self($this->mask | self::DELETE);
    }

    public function supportsGet(): bool
    {
        return $this->supports(self::GET);
    }

    public function supportsPost(): bool
    {
        return $this->supports(self::POST);
    }

    public function supportsPut(): bool
    {
        return $this->supports(self::PUT);
    }

    public function supportsDelete(): bool
    {
        return $this->supports(self::DELETE);
    }

    private function supports(int $support): bool
    {
        return ($this->mask & $support) > 0;
    }

    public function jsonSerialize()
    {
        return [
            'get' => $this->supportsGet(),
            'post' => $this->supportsPost(),
            'put' => $this->supportsPut(),
            'delete' => $this->supportsDelete(),
        ];
    }
}
