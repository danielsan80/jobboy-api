<?php
declare(strict_types=1);

namespace JobBoy\Process\Api\Response;

class Status
{
    private const OK = 'ok';
    private const ERROR = 'error';

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

    public function isOk():bool
    {
        return $this->value === self::OK;
    }

    public function isError(): bool
    {
        return $this->value === self::ERROR;
    }

    public function __toString()
    {
        return $this->value;
    }
}
