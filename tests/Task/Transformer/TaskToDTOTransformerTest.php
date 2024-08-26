<?php

namespace App\Tests\Task\Transformer;

use App\Dto\TaskRead;
use App\Entity\Category;
use App\Entity\Task;
use App\Task\Transformer\TaskToDTOTransformer;
use PHPUnit\Framework\TestCase;

class TaskToDTOTransformerTest extends TestCase
{
    public function testTransformerReturnsTaskReadInstance(): void
    {
        $task = (new Task())
            ->setName('foo')
            ->setCategory(new Category())
        ;
        $transformer = new TaskToDTOTransformer();
        $result = $transformer->transform($task);

        $this->assertInstanceOf(TaskRead::class, $result);
    }

    public function testTransformerMapsFieldsFromEntityToDto(): void
    {
        $task = (new Task())
            ->setName('foo')
            ->setDescription('Some description')
            ->setCategory(new Category())
        ;
        $transformer = new TaskToDTOTransformer();
        $result = $transformer->transform($task);

        $this->assertSame('foo', $result->getName());
        $this->assertSame('Some description', $result->getDescription());
    }

    public function testTransformerThrowsWhenCategoryIsMissing(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Each task should be linked to a category');

        $task = (new Task())
            ->setName('foo')
            ->setDescription('Some description')
        ;
        $transformer = new TaskToDTOTransformer();
        $result = $transformer->transform($task);
    }
}
