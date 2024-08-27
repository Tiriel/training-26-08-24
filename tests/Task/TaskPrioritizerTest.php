<?php

namespace App\Tests\Task;

use App\Entity\Category;
use App\Entity\Task;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\TaskRepository;
use App\Task\TaskPrioritizer;
use PHPUnit\Framework\TestCase;

class TaskPrioritizerTest extends TestCase
{
    public function testGetprioritizedtasksReturnsArray(): void
    {
        $taskRepoMock = $this->createMock(TaskRepository::class);
        $catRepoMock = $this->createMock(CategoryRepository::class);
        $prioritizer = new TaskPrioritizer($taskRepoMock, $catRepoMock);

        $result = $prioritizer->getPrioritizedTasks($this->createMock(User::class));

        $this->assertIsArray($result);
    }

    public function testGetprioritizedtasksReturnsArraySortedByCategoryPriority(): void
    {
        $taskRepoMock = $this->getMockBuilder(TaskRepository::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->onlyMethods(['findBy'])
            ->getMock();
        $taskRepoMock->expects($this->once())
            ->method('findBy')
            ->willReturn($this->getTasks());

        $catRepoMock = $this->createMock(CategoryRepository::class);
        $prioritizer = new TaskPrioritizer($taskRepoMock, $catRepoMock);

        $result = $prioritizer->getPrioritizedTasks($this->createMock(User::class));

        $this->assertIsArray($result);
        $this->assertSame(100, $result[0]->getCategory()->getPriority());
    }

    private function getTasks(): array
    {
        return [
            (new Task())->setCategory((new Category())->setPriority(50)),
            (new Task())->setCategory((new Category())->setPriority(75)),
            (new Task())->setCategory((new Category())->setPriority(25)),
            (new Task())->setCategory((new Category())->setPriority(100)),
            (new Task())->setCategory((new Category())->setPriority(10)),
        ];
    }
}
