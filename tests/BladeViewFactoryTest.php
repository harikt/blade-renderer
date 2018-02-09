<?php
namespace Harikt\Blade;

use Illuminate\Container\Container;
use Illuminate\View\Factory as ViewFactory;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PHPUnit\Framework\TestCase;
use Zend\Expressive\Router\FastRouteRouterFactory;
use Zend\Expressive\Router\Route;
use Zend\Expressive\Router\RouterInterface;

class BladeViewFactoryTest extends TestCase
{
    protected $bladeViewFactory;

    protected $container;

    public function setUp()
    {
        $container = new Container();

        $container->config = [
            'blade' => [
                'cache_dir' => sys_get_temp_dir(),
            ]
        ];

        $routerFactory = new FastRouteRouterFactory();
        $router = $routerFactory($container);
        $router->addRoute(new Route('/article/show/{id}', new class implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                $response = new Response('php://memory');
                $response->getBody()->write("Middleware");
                return $response;
            }
        }, Route::HTTP_METHOD_ANY, 'article_show'));

        $container->instance(RouterInterface::class, $router);

        $this->container = $container;
        $this->bladeViewFactory = new BladeViewFactory();
    }

    public function test__Invoke()
    {
        $viewFactory = $this->bladeViewFactory->__invoke($this->container);
        $this->assertInstanceOf(ViewFactory::class, $viewFactory);
    }

    public function testCreateViewFactory()
    {
        $viewFactory = $this->bladeViewFactory->createViewFactory($this->container);
        $this->assertInstanceOf(ViewFactory::class, $viewFactory);
    }
}
