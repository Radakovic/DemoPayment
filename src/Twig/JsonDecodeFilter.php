<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class JsonDecodeFilter extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('jsonDecode', [$this, 'jsonDecode']),
        ];
    }

    public function jsonDecode($string): array
    {
        return json_decode($string, true);
    }
}
