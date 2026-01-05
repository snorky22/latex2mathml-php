<?php

namespace Latex2MathML\Tests;

use Latex2MathML\SymbolsParser;
use PHPUnit\Framework\TestCase;

class SymbolsParserTest extends TestCase
{
    /**
     * @dataProvider symbolProvider
     */
    public function testConvertSymbol(string $latex, string $expected): void
    {
        $this->assertEquals($expected, SymbolsParser::convert_symbol($latex));
    }

    public static function symbolProvider(): array
    {
        return [
            'operator-plus' => ['+', '0002B'],
            'alias-command' => ['\to', '02192'],
        ];
    }
}
