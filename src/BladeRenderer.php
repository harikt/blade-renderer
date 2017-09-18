<?php
namespace Harikt\Blade;

use Illuminate\Contracts\View\Factory;
use ReflectionProperty;
use Zend\Expressive\Template\ArrayParametersTrait;
use Zend\Expressive\Template\Exception;
use Zend\Expressive\Template\TemplatePath;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * Template implementation bridging illuminate/view
 */
class BladeRenderer implements TemplateRendererInterface
{
    use ArrayParametersTrait;

    /**
     * @var Factory
     */
    private $template;

    public function __construct(Factory $template)
    {
        $this->template = $template;
    }

    /**
     * Render
     *
     * @param string $name
     * @param array|object $params
     * @return string
     */
    public function render($name, $params = [])
    {
        $params = $this->normalizeParams($params);
        return $this->template->make($name, $params)->render();
    }

    /**
     * Add a path for template
     *
     * @param string $path
     * @param string $namespace
     * @return void
     */
    public function addPath($path, $namespace = null)
    {
        if (! $namespace) {
            $this->template->addLocation($path);
            return;
        }

        $this->template->addNamespace($path, $namespace);
    }

    /**
     * Get the template directories
     *
     * @return TemplatePath[]
     */
    public function getPaths()
    {
        $templatePaths = [];

        $paths = $this->template->getFinder()->getPaths();
        $hints = $this->template->getFinder()->getHints();

        foreach ($paths as $path) {
            $templatePaths[] = new TemplatePath($path);
        }

        foreach ($hints as $namespace => $path) {
            $templatePaths[] = new TemplatePath($namespace, $path);
        }

        return $templatePaths;
    }

    /**
     *
     * {@inheritDoc}
     */
    public function addDefaultParam($templateName, $param, $value)
    {
        if (! is_string($param) || empty($param)) {
            throw new Exception\InvalidArgumentException(sprintf(
                '$param must be a non-empty string; received %s',
                is_object($param) ? get_class($param) : gettype($param)
            ));
        }

        $this->template->share($param, $value);
    }
}
