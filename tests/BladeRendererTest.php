<?php
namespace Harikt\Blade;

use Illuminate\Container\Container;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Response;
use Zend\Expressive\Router\FastRouteRouterFactory;
use Zend\Expressive\Router\Route;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Helper\ServerUrlHelper;

class BladeRendererTest extends TestCase
{
    protected $renderer;

    public function setUp()
    {
        $container = new Container();

        $container->config = [
            'blade' => [
                'cache_dir' => sys_get_temp_dir(),
            ],
            'templates' => [
                'paths' => [
                    'app' => __DIR__ . '/views/app',
                ]
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
        $container->bind(ServerUrlHelper::class, function () {
            return new ServerUrlHelper();
        }, true);

        $rendererFactory = new BladeRendererFactory();
        $this->renderer = $rendererFactory($container);
    }

    public function testRender()
    {
        $result = <<<'EOD'
Hello Hari

/article/show/3?foo=bar#fragment

/hello/world

EOD;
        $this->assertSame($result, $this->renderer->render('app::hello', [
            'name' => 'Hari'
        ]));
    }

    public function testInstanceOfBladeRenderer()
    {
        $this->assertInstanceOf(BladeRenderer::class, $this->renderer);
    }
}
