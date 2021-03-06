<?php
namespace Harikt\Blade;

use Illuminate\Contracts\View\Factory;
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
    public function render(string $name, $params = []) : string
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
    public function addPath(string $path, string $namespace = null) : void
    {
        if (! $namespace) {
            $this->template->addLocation($path);
            return;
        }

        $this->template->addNamespace($namespace, $path);
    }

    /**
     * Get the template directories
     *
     * @return TemplatePath[]
     */
    public function getPaths() : array
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
    public function addDefaultParam(string $templateName, string $param, $value) : void
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
