<?php

namespace App\Task\Transformer;

use App\Dto\TaskRead;
use App\Entity\Task;

class TaskToDTOTransformer
{
    public function transform(Task $task): TaskRead
    {
        if (null === $task->getCategory()) {
            throw new \InvalidArgumentException("Each task should be linked to a category");
        }

        return new TaskRead(
            $task->getId(),
            $task->getName(),
            $task->getPriority(),
            $task->getDescription(),
            $task->getCategory()->getName(),
            $task->getCreatedAt(),
            $task->getDueAt(),
            $task->getStartAt(),
            $task->getEndAt(),
            $task->isFinished(),
        );
    }
}
