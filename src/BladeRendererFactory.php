<?php
namespace Harikt\Blade;

use Psr\Container\ContainerInterface;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\Contracts\View\Factory;

class BladeRendererFactory
{
    /**
     * @param ContainerInterface $container
     * @return BladeRenderer
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->has('config') ? $container->get('config') : [];
        $config = isset($config['templates']) ? $config['templates'] : [];

        // Create the engine instance:
        $viewFactory = $this->createView($container);

        // Inject engine
        $blade = new BladeRenderer($viewFactory);
        // Add template paths
        $allPaths = isset($config['paths']) && is_array($config['paths']) ? $config['paths'] : [];
        foreach ($allPaths as $namespace => $paths) {
            $namespace = is_numeric($namespace) ? null : $namespace;
            foreach ((array) $paths as $path) {
                $blade->addPath($namespace, $path);
            }
        }

        return $blade;
    }

    private function createView(ContainerInterface $container)
    {
        if ($container->has(Factory::class)) {
            return $container->get(Factory::class);
        }

        $bladeViewFactory = new BladeViewFactory();

        return $bladeViewFactory($container);
    }
}
