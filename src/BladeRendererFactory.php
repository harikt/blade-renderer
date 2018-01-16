<?php
namespace Harikt\Blade;

use Illuminate\Contracts\View\Factory;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\ServerUrlHelper;
use Zend\Expressive\Helper\UrlHelperFactory;

class BladeRendererFactory
{
    /**
     * @param ContainerInterface $container
     * @return BladeRenderer
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->has('config') ? $container->get('config') : [];
        $configTemplates = isset($config['templates']) ? $config['templates'] : [];

        // Create the engine instance:
        $viewFactory = $this->createView($container);

        $urlHelperFactory = new UrlHelperFactory();
        $urlHelper = $urlHelperFactory($container);
        $serverUrlHelper = $container->get(ServerUrlHelper::class);
        $viewFactory->share([
            'urlHelper' => $urlHelper,
            'serverUrlHelper' => $serverUrlHelper,
        ]);

        // Inject view
        $blade = new BladeRenderer($viewFactory);
        // Add template paths
        $allPaths = isset($configTemplates['paths']) && is_array($configTemplates['paths']) ? $configTemplates['paths'] : [];
        foreach ($allPaths as $namespace => $paths) {
            $namespace = is_numeric($namespace) ? null : $namespace;
            foreach ((array) $paths as $path) {
                $blade->addPath($path, $namespace);
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
