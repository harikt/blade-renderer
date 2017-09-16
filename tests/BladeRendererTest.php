<?php
namespace Harikt\Blade;

use Acclimate\Container\ContainerAcclimator;
use Illuminate\Container\Container;
use Illuminate\View\Factory as ViewFactory;
use PHPUnit\Framework\TestCase;

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

        $rendererFactory = new BladeRendererFactory();
        $this->renderer = $rendererFactory($container);
    }

    public function testRender()
    {
        $this->assertSame('Hello Hari', $this->renderer->render('app::hello', [
            'name' => 'Hari'
        ]));
    }
}
