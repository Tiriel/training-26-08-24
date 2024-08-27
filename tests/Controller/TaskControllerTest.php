<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group smoke
 */
class TaskControllerTest extends WebTestCase
{
    public function testTaskIndex(): void
    {
        $this->markTestSkipped();
        $client = static::createClient();
        $crawler = $client->request('GET', '/task/');

        $this->assertResponseIsSuccessful();
    }
}
