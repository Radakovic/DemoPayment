<?php

namespace App\Tests\Unit\Twig;

use App\Twig\JsonDecodeFilter;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

class JsonDecodeFilterTest extends TestCase
{
    private JsonDecodeFilter $jsonDecodeFilter;
    protected function setUp(): void
    {
        $this->jsonDecodeFilter = new JsonDecodeFilter();
    }

    public function testGetFunctions(): void
    {
        $functions = $this->jsonDecodeFilter->getFunctions();
        $func = $functions[0];
        self::assertCount(1, $functions);
        self::assertInstanceOf(TwigFunction::class, $functions[0]);
        self::assertEquals('jsonDecode', $func->getName());
    }

    public function testJsonDecodeResult(): void
    {
        $array = [
            'hello' => 'world',
            'nice' => 'weather'
        ];
        $json = json_encode($array, JSON_THROW_ON_ERROR);

        $result = $this->jsonDecodeFilter->jsonDecode($json);

        self::assertEquals($array, $result);
    }
}
