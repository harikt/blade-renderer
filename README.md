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

    'templates' => [
        'paths' => [
            'app' => __DIR__ . '/views/app',
        ]
    ]

    'blade' => [
        'cache_dir'      => '/cache/path',
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
