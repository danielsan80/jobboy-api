<?php
declare(strict_types=1);

namespace JobBoy\Process\Api\Response;

class Status
{
    private const OK = 'ok';
    private const ERROR = 'error';
    private const UNAUTHORIZED = 'unauthorized';

    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function ok(): self
    {
        return new self(self::OK);
    }

    public static function error(): self
    {
        return new self(self::ERROR);
    }

    public static function unauthorized(): self
    {
        return new self(self::UNAUTHORIZED);
    }

    public function isOk():bool
    {
        return $this->value === self::OK;
    }

    public function isError(): bool
    {
        return $this->value === self::ERROR;
    }

    public function isUnauthorized(): bool
    {
        return $this->value === self::UNAUTHORIZED;
    }

    public function __toString()
    {
        return $this->value;
    }
}
