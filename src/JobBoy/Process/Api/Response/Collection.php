<?php
declare(strict_types=1);

namespace JobBoy\Process\Api\Response;

class Collection implements Response
{
    private $items;
    private $total;
    private $start;
    private $length;

    public function __construct(array $items, int $total, int $start = 0, ?int $length = null)
    {
        $this->items = $items;
        $this->total = $total;
        $this->start = $start;
        $this->length = $length;
    }

    public function normalize(): array
    {
        return [
            'status' => (string)Status::ok(),
            'data' => $this->items,
            'pagination' => [
                'total' => $this->total,
                'start' => $this->start,
                'length' => $this->length,
            ],
        ];
    }

}
