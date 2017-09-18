# Illuminate View

[![Build Status](https://travis-ci.org/harikt/blade-renderer.png?branch=master)](https://travis-ci.org/harikt/blade-renderer)

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

## Helper functions

You can make use of zend expressive provided url helper functions with the shared variable `$urlHelper` and `$serverUrlHelper`.

Example usage.

```
Hello {{ $name }}

{{ $urlHelper('article_show', ['id' => '3'], ['foo' => 'bar'], 'fragment') }}

{{ $serverUrlHelper('/hello/world') }}
```
