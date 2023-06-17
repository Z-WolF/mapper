<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\SerializerRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class SerializerExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('jsonld', [SerializerRuntime::class, 'serializeToJsonLd'], ['is_safe' => ['html']]),
            new TwigFilter('json', [SerializerRuntime::class, 'serializeToJson'], ['is_safe' => ['html']]),
        ];
    }

//    public function getFunctions(): array
//    {
//        return [
//            new TwigFunction('function_name', [SerializerRuntime::class, 'doSomething']),
//        ];
//    }
}
