<?php

namespace Latex2MathML\Tests;

use PHPUnit\Framework\TestCase;
use Latex2MathML\Converter;

class NestedEnvironmentsTest extends TestCase
{
    public function testSplitInEquation()
    {
        $latex = '\begin{equation}
	\label{auxhamiltonian}
	\begin{split}
 H_{1}(y,v,q) & = \frac{1}{2}\left(H_{0xx}y^{2} + 2 H_{0xu}yv + 
	H_{0uu}v^{2}\right) \\
 & + \tilde{p_{1}}\left(f_{0x}y +f_{0u}v\right)
 +q_{1}\left[f_{0x}(y+\tilde{x_{1}}) + f_{0u}v\right]
 \end{split}
\end{equation}';
        
        $mathml = Converter::convert($latex, display: 'block');
        
        $this->assertStringContainsString('<mtable', $mathml);
        $this->assertStringContainsString('columnspacing="0em"', $mathml);
        $this->assertStringContainsString('<mtr>', $mathml);
        $this->assertStringContainsString('<mtd columnalign="right">', $mathml);
        $this->assertStringContainsString('<mtd columnalign="left">', $mathml);
        // Ensure \label is ignored
        $this->assertStringNotContainsString('auxhamiltonian', $mathml);
        $this->assertStringNotContainsString('\label', $mathml);
    }
}
