<?php

require_once __DIR__ . '/vendor/autoload.php';

use Latex2MathML\Converter;

// 1. Find the first .tex file in the tests directory
$testsDir = __DIR__ . '/tests';
$texFiles = glob($testsDir . '/*.tex');

if (empty($texFiles)) {
    die("No .tex files found in $testsDir");
}

$texFile = $texFiles[0];
$content = file_get_contents($texFile);

// 2. Extract math contents delimited by $$ or $
// We use a regex that matches $$...$$ first to avoid matching $...$ inside it
// Pattern explanation:
// (\$\$.*?\$\$) matches double dollar expressions
// (\$.*?\$) matches single dollar expressions
// s modifier allows . to match newlines
$pattern = '/(\$\$.*?\$\$|\$.*?\$)/s';
preg_match_all($pattern, $content, $matches);

$expressions = $matches[0];
$results = [];

foreach ($expressions as $expr) {
    $isDisplay = str_starts_with($expr, '$$');
    // Remove delimiters
    if ($isDisplay) {
        $latex = trim(substr($expr, 2, -2));
        $display = 'block';
    } else {
        $latex = trim(substr($expr, 1, -1));
        $display = 'inline';
    }

    try {
        $mathml = Converter::convert($latex, display: $display);
        $results[] = [
            'latex' => $expr,
            'mathml' => $mathml
        ];
    } catch (\Exception $e) {
        $results[] = [
            'latex' => $expr,
            'error' => $e->getMessage()
        ];
    }
}

// 3. Output as a web page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LaTeX to MathML Conversion</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; max-width: 800px; margin: 0 auto; }
        .result-item { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .latex { background: #f4f4f4; padding: 5px; font-family: monospace; }
        .mathml { margin-top: 10px; background: #fff; border: 1px inset #eee; padding: 10px; overflow-x: auto; }
        .error { color: red; font-weight: bold; }
        h1 { border-bottom: 2px solid #333; }
        h2 { font-size: 1.1em; color: #555; }
    </style>
</head>
<body>
    <h1>LaTeX to MathML Conversion</h1>
    <p>Reading file: <code><?php echo htmlspecialchars(basename($texFile)); ?></code></p>

    <?php if (empty($results)): ?>
        <p>No LaTeX expressions found in the file.</p>
    <?php else: ?>
        <?php foreach ($results as $item): ?>
            <div class="result-item">
                <h2>LaTeX Input:</h2>
                <div class="latex"><?php echo htmlspecialchars($item['latex']); ?></div>
                
                <h2>MathML Output:</h2>
                <div class="mathml">
                    <?php if (isset($item['error'])): ?>
                        <div class="error">Error: <?php echo htmlspecialchars($item['error']); ?></div>
                    <?php else: ?>
                        <?php echo $item['mathml']; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
