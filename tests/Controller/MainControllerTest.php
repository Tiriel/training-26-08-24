<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @group smoke
 */
class MainControllerTest extends WebTestCase
{
    protected static KernelBrowser $client;

    public static function setUpBeforeClass(): void
    {
        static::$client = static::createClient();
    }

    /**
     * @dataProvider provideMethodAndUris
     */
    public function testPublicHomepageIsOK(string $method, string $uri): void
    {
        static::$client->request($method, $uri);
        $repo = static::getContainer()->get(UserRepository::class);
        $user = $repo->findOneBy(['username' => 'test']);
        static::$client->loginUser($user);

        if (\in_array(static::$client->getResponse()->getStatusCode(), [301, 302, 307, 308])) {
            static::$client->followRedirect();
        }
        $response = static::$client->getResponse();

        $this->assertSame(200, $response->getStatusCode());
    }

    public function provideMethodAndUris(): iterable
    {
        $router = static::getContainer()->get(RouterInterface::class);
        $collection = $router->getRouteCollection();
        static::ensureKernelShutdown();

        foreach ($collection as $routeName => $route) {
            /** @var Route $route */
            $variables = $route->compile()->getVariables();
            if (count(array_diff($variables, array_keys($route->getDefaults()))) > 0) {
                continue;
            }
            if ([] === $methods = $route->getMethods()) {
                $methods[] = 'GET';
            }
            foreach ($methods as $method) {
                $path = $router->generate($routeName);
                yield "$method $path" => [$method, $path];
            }
        }
    }
}
