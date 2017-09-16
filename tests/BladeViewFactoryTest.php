<?php
namespace Harikt\Blade;

use Acclimate\Container\ContainerAcclimator;
use Illuminate\Container\Container;
use Illuminate\View\Factory as ViewFactory;
use PHPUnit\Framework\TestCase;

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
