<?php
namespace Harikt\Blade;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\View\Factory;
use Illuminate\Events\Dispatcher as EventDispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\View\FileViewFinder;
use Illuminate\View\ViewFinderInterface;
use Psr\Container\ContainerInterface;

class BladeViewFactory
{
    public function __invoke(ContainerInterface $container)
    {
        if ($container->has(Factory::class)) {
            return $container->get(Factory::class);
        }

        return $this->createViewFactory($container);
    }

    public function createViewFactory($container)
    {
        $viewResolver = new EngineResolver();

        $config = $container->has('config') ? $container->get('config') : [];
        if (! isset($config['blade'])) {
            throw new \Exception("Need blade['cache_dir'] in config");
        }

        $cachePath = $config['blade']['cache_dir'];

        $viewResolver->register('blade', function () use ($cachePath) {
            return new CompilerEngine(
                new BladeCompiler(
                    new Filesystem(),
                    $cachePath
                )
            );
        });

        $viewResolver->register('php', function () {
            return new PhpEngine();
        });

        $finder = $this->getViewFinder($container);
        $dispatcher = $this->getEventDispatcher($container);

        return new ViewFactory($viewResolver, $finder, $dispatcher);
    }

    protected function getViewFinder($container)
    {
        if ($container->has(ViewFinderInterface::class)) {
            return $container->get(ViewFinderInterface::class);
        }

        return new FileViewFinder(new Filesystem, []);
    }

    protected function getEventDispatcher($container)
    {
        if ($container->has(Dispatcher::class)) {
            return $container->get(Dispatcher::class);
        }

        return new EventDispatcher();
    }
}
