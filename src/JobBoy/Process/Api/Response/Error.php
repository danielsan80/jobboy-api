<?php
declare(strict_types=1);

namespace JobBoy\Process\Api\Response;

class Error implements Response
{

    private $message;
    private $info;

    public function __construct(string $message, array $info)
    {
        $this->message = $message;
        $this->info = $info;
    }

    public function normalize(): array
    {
        return [
            'status' => (string)Status::error(),
            'message' => $this->message,
            'info' => $this->info
        ];
    }
}
