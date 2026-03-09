<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Latex2MathML\Converter;

// Example LaTeX equation with stretchy delimiters
$latex = '\frac{1}{\sqrt{-g}}\bigg[ \partial_t \left(\sqrt{-g}T^{\mu 0}\right) + \partial_j\left( \sqrt{-g}T^{\mu j} \right) \bigg] = -\Gamma^{\mu}_{\nu\delta}T^{\delta\nu}';

echo "LaTeX Input:\n";
echo "============\n";
echo $latex . "\n\n";

// Convert to MathML (standard web format)
echo "MathML Output (for web browsers):\n";
echo "==================================\n";
$mathml = Converter::convert($latex, 'inline');
echo $mathml . "\n\n";

// Convert to OMML (for Microsoft Word)
echo "OMML Output (for Microsoft Word):\n";
echo "==================================\n";
$omml = Converter::convertToOmml($latex, 'inline');

// Pretty print the OMML
$dom = new DOMDocument();
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($omml);
echo $dom->saveXML();

echo "\n\nHow to use with PHPDocX:\n";
echo "========================\n";
echo "1. Use Converter::convertToOmml() to generate OMML\n";
echo "2. Insert the OMML into your Word document using PHPDocX methods\n";
echo "3. The delimiters will automatically stretch to fit the content\n";

// Example for PHPDocX integration (pseudo-code)
echo "\nExample PHPDocX integration:\n";
echo "----------------------------\n";
echo <<<'PHP'
$omml = Converter::convertToOmml($latexEquation, 'inline');
// Insert $omml into your Word document using PHPDocX
// The OMML contains proper stretchy delimiters using <m:d> elements
PHP;
