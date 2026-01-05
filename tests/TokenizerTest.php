<?php

namespace Latex2MathML\Tests;

use Latex2MathML\Tokenizer;
use PHPUnit\Framework\TestCase;

class TokenizerTest extends TestCase
{
    /**
     * @dataProvider tokenizerProvider
     */
    public function testTokenize(string $latex, array $expected): void
    {
        $this->assertEquals($expected, Tokenizer::tokenize($latex));
    }

    public static function tokenizerProvider(): array
    {
        return [
            'single-backslash' => ["\\", ["\\"]],
            'alphabets' => [
                'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
                str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
            ],
            'numbers' => ["1234567890", ["1234567890"]],
            'backslash-after-number' => ["123\\", ["123", "\\"]],
            'double-backslash-after-number' => ["123\\\\", ["123", "\\\\"]],
            'decimal' => ["12.56", ["12.56"]],
            'incomplete-decimal' => ["12.\\\\", ["12", ".", "\\\\"]],
            'numbers-and-alphabets' => ["5x", ["5", "x"]],
            'decimals-and-alphabets' => ["5.8x", ["5.8", "x"]],
            'string-with-spaces' => ["3 x", ["3", "x"]],
            'operators' => ["+-*/=()[]_^{}", str_split("+-*/=()[]_^{}")],
            'numbers-alphabets-and-operators' => [
                "3 + 5x - 5y = 7", ["3", "+", "5", "x", "-", "5", "y", "=", "7"]
            ],
            'symbols' => ['\alpha\beta', ['\alpha', '\beta']],
            'symbols-appended-with-number' => ['\frac2x', ['\frac', "2", "x"]],
            'matrix' => [
                '\begin{matrix}a & b \\\\ c & d \end{matrix}',
                ['\begin{matrix}', "a", "&", "b", '\\\\', "c", "&", "d", '\end{matrix}']
            ],
            'matrix-with-alignment' => [
                '\begin{matrix*}[r]a & b \\\\ c & d \end{matrix*}',
                [
                    '\begin{matrix*}',
                    "[",
                    "r",
                    "]",
                    "a",
                    "&",
                    "b",
                    '\\\\',
                    "c",
                    "&",
                    "d",
                    '\end{matrix*}',
                ]
            ],
            'matrix-with-negative-sign' => [
                '\begin{matrix}-a & b \\\\ c & d \end{matrix}',
                ['\begin{matrix}', "-", "a", "&", "b", '\\\\', "c", "&", "d", '\end{matrix}']
            ],
            'simple-array' => [
                '\begin{array}{cc} 1 & 2 \\\\ 3 & 4 \end{array}',
                [
                    '\begin{array}',
                    "{",
                    "c",
                    "c",
                    "}",
                    "1",
                    "&",
                    "2",
                    '\\\\',
                    "3",
                    "&",
                    "4",
                    '\end{array}',
                ]
            ],
            'subscript' => ["a_{2,n}", ["a", "_", "{", "2", ",", "n", "}"]],
            'superscript-with-curly-braces' => ["a^{i+1}_3", ["a", "^", "{", "i", "+", "1", "}", "_", "3"]],
            'issue-51' => ['\mathbb{R}', ["&#x0211D;"]],
            'issue-55' => [
                '\begin{array}{rcl}ABC&=&a\\\\A&=&abc\end{array}',
                [
                    '\begin{array}',
                    "{",
                    "r",
                    "c",
                    "l",
                    "}",
                    "A",
                    "B",
                    "C",
                    "&",
                    "=",
                    "&",
                    "a",
                    '\\\\',
                    "A",
                    "&",
                    "=",
                    "&",
                    "a",
                    "b",
                    "c",
                    '\end{array}',
                ]
            ],
            'issue-60' => ['\mathrm{...}', ['\mathrm', "{", ".", ".", ".", "}"]],
            'issue-108-1' => ['\max \{a, b, c\}', ['\max', '\{', "a", ",", "b", ",", "c", '\}']],
            'issue-109-operatorname' => ['\operatorname{sn}x', ['\operatorname{sn}', "x"]],
            'issue-109-text' => [
                '\text{Let}\ x=\text{number of cats}.',
                ['\text', "Let", '\ ', "x", "=", '\text', "number of cats", "."]
            ],
            'quadratic-equation' => [
                'x = {-b \pm \sqrt{b^2-4ac} \over 2a}',
                [
                    "x",
                    "=",
                    "{",
                    "-",
                    "b",
                    '\pm',
                    '\sqrt',
                    "{",
                    "b",
                    "^",
                    "2",
                    "-",
                    "4",
                    "a",
                    "c",
                    "}",
                    '\over',
                    "2",
                    "a",
                    "}"
                ]
            ],
            'comments' => [
                "% this is hidden\n100\%! 100% this is hidden, too\n\\test% this is another hidden line",
                ["100", '\%', "!", "100", '\test']
            ],
            'fbox' => ['\fbox{E=mc^2}', ['\fbox', "E=mc^2"]],
            'empty-color' => ['\color{}ab', ['\color', "", "a", "b"]],
            'issue-391' => ['\begin {cases} \end {cases}', ['\begin{cases}', '\end{cases}']],
            'issue-391-operatorname' => ['\operatorname { s n } x', ['\operatorname{sn}', "x"]],
        ];
    }
}
