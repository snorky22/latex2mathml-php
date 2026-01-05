<?php

use PHPUnit\Framework\TestCase;
use Latex2MathML\Converter;

class ConverterTest extends TestCase
{
    public function test_simple_conversion()
    {
        $latex = 'a + b = c';
        $mathml = Converter::convert($latex);
        $this->assertStringContainsString('<mi>a</mi>', $mathml);
        $this->assertStringContainsString('<mo>+</mo>', $mathml);
        $this->assertStringContainsString('<mi>b</mi>', $mathml);
        $this->assertStringContainsString('<mo>=</mo>', $mathml);
        $this->assertStringContainsString('<mi>c</mi>', $mathml);
    }

    public function test_fraction()
    {
        $latex = '\frac{1}{2}';
        $mathml = Converter::convert($latex);
        $this->assertStringContainsString('<mfrac>', $mathml);
        $this->assertStringContainsString('<mn>1</mn>', $mathml);
        $this->assertStringContainsString('<mn>2</mn>', $mathml);
    }
    
    public function test_complex()
    {
        $latex = 'x = \frac{-b \pm \sqrt{b^2 - 4ac}}{2a}';
        $mathml = Converter::convert($latex);
        $this->assertStringContainsString('<mi>x</mi>', $mathml);
        $this->assertStringContainsString('<mfrac>', $mathml);
        $this->assertStringContainsString('<msqrt>', $mathml);
        $this->assertStringContainsString('<msup>', $mathml);
    }
}
