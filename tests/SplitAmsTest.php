<?php

namespace Latex2MathML\Tests;

use PHPUnit\Framework\TestCase;
use Latex2MathML\Converter;

class SplitAmsTest extends TestCase
{
    public function testSplitInEquation()
    {
        $latex = <<<'EOD'
\begin{equation}
\begin{split}
a &= b \\
c &= d
\end{split}
\end{equation}
EOD;
        $mathml = Converter::convert($latex);
        
        // Check for equation wrapper (mrow with displaystyle)
        $this->assertStringContainsString('<mrow displaystyle="true">', $mathml);
        
        // Check for split mtable
        $this->assertStringContainsString('<mtable displaystyle="true" columnspacing="0em" rowspacing="3pt">', $mathml);
        
        // Check for cells with rl alignment
        $this->assertStringContainsString('<mtd columnalign="right"><mi>a</mi></mtd>', $mathml);
        $this->assertStringContainsString('<mtd columnalign="left"><mi/><mo>=</mo><mi>b</mi></mtd>', $mathml);
        $this->assertStringContainsString('<mtd columnalign="right"><mi>c</mi></mtd>', $mathml);
        $this->assertStringContainsString('<mtd columnalign="left"><mi/><mo>=</mo><mi>d</mi></mtd>', $mathml);
    }

    public function testSplitStandalone()
    {
        $latex = <<<'EOD'
\begin{split}
x & y \\
z & w
\end{split}
EOD;
        $mathml = Converter::convert($latex);
        
        $this->assertStringContainsString('<mtable displaystyle="true" columnspacing="0em" rowspacing="3pt">', $mathml);
        $this->assertStringContainsString('<mtd columnalign="right"><mi>x</mi></mtd>', $mathml);
        $this->assertStringContainsString('<mtd columnalign="left"><mi/><mi>y</mi></mtd>', $mathml);
    }
}
