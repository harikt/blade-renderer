# Illuminate View

```
composer require harikt/blade-renderer
```

In your `config/autoload/templates.global.php` use something as below.

```php
<?php

use Zend\Expressive\Template\TemplateRendererInterface;
use Harikt\Blade\BladeRendererFactory;

return [
    'dependencies' => [
        'factories' => [
            TemplateRendererInterface::class => BladeRendererFactory::class,
        ],
    ],

    // currently not supported
    // 'templates' => [
    //     'extension' => [
    //         'blade.php' => 'blade',
    //         'php' => 'php',
    //         'css' => 'file',
    //     ],
    // ],

    'blade' => [
        'cache_dir'      => '/cache/path',
        // Need to Discuss with Taylor whether blade extensions are called composer.
        'extensions'     => [
            // extensions
        ],
    ],
];
```
