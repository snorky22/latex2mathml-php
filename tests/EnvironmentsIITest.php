<?php

namespace Latex2MathML\Tests;

use PHPUnit\Framework\TestCase;
use Latex2MathML\Converter;

class EnvironmentsIITest extends TestCase
{
    public function testSubequations()
    {
        $latex = '\begin{subequations}\begin{equation}a=b\end{equation}\begin{equation}c=d\end{equation}\end{subequations}';
        $mathml = Converter::convert($latex);
        $this->assertStringContainsString('<mrow>', $mathml);
        $this->assertStringContainsString('<mi>a</mi>', $mathml);
        $this->assertStringContainsString('<mi>c</mi>', $mathml);
    }

    public function testMultline()
    {
        $latex = '\begin{multline}a+b \\\\ c+d \\\\ e+f\end{multline}';
        $mathml = Converter::convert($latex);
        $this->assertStringContainsString('<mtable', $mathml);
        $this->assertStringContainsString('columnalign="left"', $mathml);
        $this->assertStringContainsString('columnalign="center"', $mathml);
        $this->assertStringContainsString('columnalign="right"', $mathml);
    }

    public function testAlignat()
    {
        // alignat{2} - the {2} should be consumed and ignored
        $latex = '\begin{alignat}{2} a &= b & c &= d \end{alignat}';
        $mathml = Converter::convert($latex);
        $this->assertStringContainsString('<mtable', $mathml);
        $this->assertStringContainsString('<mi>a</mi>', $mathml);
        $this->assertStringContainsString('<mi>c</mi>', $mathml);
        // Should have 4 columns (2 pairs)
        $this->assertEquals(4, substr_count($mathml, '<mtd'));
    }

    public function testTag()
    {
        $latex = 'a=b \tag{1.1}';
        $mathml = Converter::convert($latex);
        $this->assertStringContainsString('<mi>a</mi>', $mathml);
        $this->assertStringNotContainsString('1.1', $mathml); // Currently we just ignore \tag
    }
}
