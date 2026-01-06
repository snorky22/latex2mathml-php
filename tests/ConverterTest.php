<?php

namespace Latex2MathML\Tests;

use PHPUnit\Framework\TestCase;
use Latex2MathML\Converter;

class ConverterTest extends TestCase
{
    /**
     * @dataProvider conversionProvider
     */
    public function testConvert(string $latex, array $expected_substrings)
    {
        $mathml = Converter::convert($latex);
        foreach ($expected_substrings as $substring) {
            $this->assertStringContainsString($substring, $mathml);
        }
    }

    public static function conversionProvider(): array
    {
        return [
            'single-identifier' => ['x', ['<mi>x</mi>']],
            'multiple-identifier' => ['xyz', ['<mi>x</mi>', '<mi>y</mi>', '<mi>z</mi>']],
            'single-number' => ['3', ['<mn>3</mn>']],
            'decimal-numbers' => ['12.34', ['<mn>12.34</mn>']],
            'single-operator' => ['+', ['<mo>+</mo>']],
            'over' => ['1 \over 2', ['<mfrac>', '<mn>1</mn>', '<mn>2</mn>']],
            'matrix' => [
                '\begin{matrix}a & b \\\\ c & d \end{matrix}',
                ['<mtable>', '<mtr>', '<mtd><mi>a</mi></mtd>', '<mtd><mi>b</mi></mtd>', '<mtd><mi>c</mi></mtd>', '<mtd><mi>d</mi></mtd>']
            ],
            'quadratic' => [
                'x = \frac{-b \pm \sqrt{b^2 - 4ac}}{2a}',
                ['<mi>x</mi>', '<mo>=</mo>', '<mfrac>', '<msqrt>', '<msup>', '<mi>b</mi>', '<mn>2</mn>']
            ],
            'limit' => [
                '\lim_{x \to \infty} f(x)',
                ['<msub>', '<mo>lim</mo>', '<mi>x</mi>', '→', '∞']
            ]
        ];
    }

    public function testPositionalDisplay()
    {
        $mathml = Converter::convert('x', 'block');
        $this->assertStringContainsString('display="block"', $mathml);
        $this->assertStringContainsString('xmlns="http://www.w3.org/1998/Math/MathML"', $mathml);
    }
}
