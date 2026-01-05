<?php

namespace Latex2MathML\Tests;

use Latex2MathML\Walker;
use PHPUnit\Framework\TestCase;

class WalkerTest extends TestCase
{
    /**
     * @dataProvider walkerProvider
     */
    public function testWalk(string $latex, array $expected): void
    {
        $this->assertEquals($expected, Walker::walk($latex, "inline"));
    }

    public static function walkerProvider(): array
    {
        $n = fn($t, $c = null) => new \Latex2MathML\Node($t, $c);
        return [
            'alphabets' => [
                'abc',
                [$n('a'), $n('b'), $n('c')]
            ],
            'empty-group' => [
                '{{}}',
                [$n('{}', [$n('{}', [])])]
            ],
            'numbers' => ["123", [$n("123")]],
            'decimals' => ["12.56", [$n("12.56")]],
            'numbers-and-alphabets' => ["5x", [$n("5"), $n("x")]],
            'symbols-appended-with-number' => [
                '\frac2x',
                [$n('\frac', [$n("2"), $n("x")])]
            ],
            'single-group' => ['{a}', [$n('{}', [$n("a")])]],
            'subscript-1' => ["a_b", [$n("_", [$n("a"), $n("b")])]],
            'superscript-1' => ["a^b", [$n("^", [$n("a"), $n("b")])]],
            'subscript-and-superscript-1' => [
                "a_b^c",
                [$n("_^", [$n("a"), $n("b"), $n("c")])]
            ],
            'root' => [
                '\sqrt[3]{2}',
                [$n('\root', [$n('{}', [$n("2")]), $n("3")])]
            ],
        ];
    }
}
