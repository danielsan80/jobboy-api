<?php
declare(strict_types=1);

namespace JobBoy\Process\Api\Response;

class Item implements Response
{

    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function normalize(): array
    {
        return [
            'status' => (string)Status::ok(),
            'data' => $this->data
        ];
    }
}
