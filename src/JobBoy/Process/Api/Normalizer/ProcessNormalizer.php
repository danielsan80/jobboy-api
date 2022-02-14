<?php
declare(strict_types=1);

namespace JobBoy\Process\Api\Normalizer;

use JobBoy\Process\Application\DTO\Process;

class ProcessNormalizer
{
    const DATE_FORMAT = 'Y-m-d\TH:i:sO';

    public function normalize(Process $process): array
    {
        return [
            'id' => $process->id(),
            'code' => $process->code(),
            'parameters' => $process->parameters(),
            'status' => $process->status(),
            'created_at' => $process->createdAt()->format(self::DATE_FORMAT),
            'updated_at' => $process->updatedAt()->format(self::DATE_FORMAT),
            'started_at' => $process->startedAt()?$process->startedAt()->format(self::DATE_FORMAT):null,
            'ended_at' => $process->endedAt()?$process->endedAt()->format(self::DATE_FORMAT):null,
            'handled_at' => $process->handledAt()?$process->handledAt()->format(self::DATE_FORMAT):null,
            'killed_at' => $process->killedAt()?$process->killedAt()->format(self::DATE_FORMAT):null,
            'store' => $process->store(),
            'reports' => $process->reports(),
            'is_started' => $process->isStarted(),
            'is_ended' => $process->isEnded(),
            'is_done' => $process->isDone(),
            'is_failed' => $process->isFailed(),
            'is_handled' => $process->isHandled(),
            'is_killed' => $process->isKilled(),
            'is_active' => $process->isActive(),
            'is_evolving' => $process->isEvolving(),
        ];

    }

}
