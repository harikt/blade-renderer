Hello {{ $name }}

@php
    echo $urlHelper('article_show', ['id' => '3'], ['foo' => 'bar'], 'fragment');
@endphp

@php
    echo $serverUrlHelper('/hello/world');
@endphp
