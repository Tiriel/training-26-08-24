<?php

namespace App\Tests\Task\Transformer;

use App\Dto\TaskRead;
use App\Entity\Category;
use App\Entity\Task;
use App\Task\Transformer\TaskToDTOTransformer;
use PHPUnit\Framework\TestCase;

class TaskToDTOTransformerTest extends TestCase
{
    protected static TaskToDTOTransformer $transformer;

    public static function setUpBeforeClass(): void
    {
        static::$transformer = new TaskToDTOTransformer();
    }

    public function testTransformerReturnsTaskReadInstance(): void
    {
        $task = $this->getTask();
        $result = static::$transformer->transform($task);

        $this->assertInstanceOf(TaskRead::class, $result);
    }

    public function testTransformerMapsFieldsFromEntityToDto(): void
    {
        $task = $this->getTask();
        $result = static::$transformer->transform($task);

        $this->assertSame('foo', $result->getName());
        $this->assertSame('Some description', $result->getDescription());
    }

    public function testTransformerThrowsWhenCategoryIsMissing(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Each task should be linked to a category');

        $task = $this->getTask(false);
        $result = static::$transformer->transform($task);
    }

    protected function getTask(bool $withCategory = true): Task
    {
        $task = (new Task())
            ->setName('foo')
            ->setDescription('Some description')
        ;

        if ($withCategory) {
            $task->setCategory(new Category());
        }

        return $task;
    }
}
