<?php
declare(strict_types=1);

namespace JobBoy\Process\Api\Response;

interface Response
{
    public function normalize(): array;
}
