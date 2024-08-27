<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group functional
 */
class TaskControllerTest extends WebTestCase
{
    public function testTaskIndex(): void
    {
        $client = static::createClient();
        $repo = static::getContainer()->get(UserRepository::class);
        $user = $repo->findOneBy(['username' => 'test']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/task/');
        $client->clickLink('Create new');

        $this->assertResponseIsSuccessful();
    }
}
