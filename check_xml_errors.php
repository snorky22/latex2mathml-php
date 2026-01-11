<?php
/**
 * check_xml_errors.php
 * 
 * Improved XML validation script that parses a .txt file for <math> expressions,
 * validates each individually, and outputs a detailed HTML report.
 */

require_once __DIR__ . '/vendor/autoload.php';

// 1. Find the first .txt file in the tests directory
$testsDir = __DIR__ . '/tests';
$txtFiles = glob($testsDir . '/*.txt');

if (empty($txtFiles)) {
    die("No .txt files found in $testsDir\n");
}

$txtFile = $txtFiles[0];
$content = file_get_contents($txtFile);

if ($content === false) {
    die("Could not read file: $txtFile\n");
}

// 2. Extract <math> expressions
// Pattern matches <math ...> ... </math> including across multiple lines (s modifier)
$pattern = '/<math\b[^>]*>.*?<\/math>/s';
preg_match_all($pattern, $content, $matches);
$expressions = $matches[0];

$results = [];
$stats = [
    'total' => count($expressions),
    'valid' => 0,
    'invalid' => 0,
];

libxml_use_internal_errors(true);

foreach ($expressions as $expr) {
    $dom = new DOMDocument();
    // Add a wrapper to ensure it's a single root if needed, but <math> should be fine
    $success = $dom->loadXML($expr);
    
    if ($success) {
        $stats['valid']++;
        $results[] = [
            'xml' => $expr,
            'status' => 'valid',
            'errors' => []
        ];
    } else {
        $stats['invalid']++;
        $errors = libxml_get_errors();
        $errorDetails = [];
        foreach ($errors as $error) {
            $errorDetails[] = [
                'line' => $error->line,
                'column' => $error->column,
                'message' => trim($error->message),
                'code' => $error->code,
                'level' => $error->level, // 1: warning, 2: error, 3: fatal
            ];
        }
        $results[] = [
            'xml' => $expr,
            'status' => 'invalid',
            'errors' => $errorDetails
        ];
        libxml_clear_errors();
    }
}

// 3. Output as a web page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>XML Validation Report</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; max-width: 1000px; margin: 0 auto; background-color: #f9f9f9; }
        h1 { color: #333; border-bottom: 2px solid #333; }
        .stats { background: #fff; padding: 15px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .stats b { font-size: 1.2em; }
        .valid-count { color: green; }
        .invalid-count { color: red; }
        
        .expression-item { background: #fff; border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .expression-item.invalid { border-left: 5px solid red; }
        .expression-item.valid { border-left: 5px solid green; display: none; } /* Hide valid ones by default to focus on errors */
        
        .xml-code { background: #f4f4f4; padding: 10px; font-family: monospace; overflow-x: auto; white-space: pre-wrap; border: 1px inset #eee; }
        .error-list { margin-top: 10px; background: #fff0f0; border: 1px solid #ffcccc; padding: 10px; border-radius: 3px; }
        .error-item { color: #b30000; margin-bottom: 5px; font-size: 0.9em; }
        .error-help { font-style: italic; color: #666; margin-left: 20px; display: block; }
        
        .toggle-valid { margin-bottom: 10px; }
    </style>
    <script>
        function toggleValid() {
            var items = document.getElementsByClassName('valid');
            for (var i = 0; i < items.length; i++) {
                items[i].style.display = (items[i].style.display === 'none' || items[i].style.display === '') ? 'block' : 'none';
            }
        }
    </script>
</head>
<body>
    <h1>XML Validation Report</h1>
    <p>File checked: <code><?php echo htmlspecialchars(basename($txtFile)); ?></code></p>

    <div class="stats">
        <b>Statistics:</b><br>
        Total Expressions: <?php echo $stats['total']; ?><br>
        <span class="valid-count">Valid: <?php echo $stats['valid']; ?></span><br>
        <span class="invalid-count">Invalid: <?php echo $stats['invalid']; ?></span>
    </div>

    <div class="toggle-valid">
        <button onclick="toggleValid()">Show/Hide Valid Expressions</button>
    </div>

    <?php if ($stats['invalid'] === 0): ?>
        <p style="color: green; font-weight: bold;">Great! No XML errors found in any &lt;math&gt; expression.</p>
    <?php endif; ?>

    <?php foreach ($results as $index => $item): ?>
        <div class="expression-item <?php echo $item['status']; ?>">
            <strong>Expression #<?php echo $index + 1; ?> (<?php echo strtoupper($item['status']); ?>)</strong>
            <div class="xml-code"><?php echo htmlspecialchars($item['xml']); ?></div>
            
            <?php if (!empty($item['errors'])): ?>
                <div class="error-list">
                    <strong>Errors found:</strong>
                    <?php foreach ($item['errors'] as $error): ?>
                        <div class="error-item">
                            Line <?php echo $error['line']; ?>, Column <?php echo $error['column']; ?>: 
                            <?php echo htmlspecialchars($error['message']); ?>
                            
                            <span class="error-help">
                                <?php 
                                    // Provide some context-specific help
                                    if (str_contains($error['message'], 'EntityRef: expecting \';\'')) {
                                        echo "Help: This usually means an ampersand '&' is not followed by a semicolon or it should be escaped as '&amp;'.";
                                    } elseif (str_contains($error['message'], 'xmlParseEntityRef: no name')) {
                                        echo "Help: An ampersand '&' was found that doesn't start a valid entity. Use '&amp;' instead of '&'.";
                                    } elseif (str_contains($error['message'], 'Opening and ending tag mismatch')) {
                                        echo "Help: One of your tags (like <mrow>) is not closed correctly or closed in the wrong order.";
                                    } elseif (str_contains($error['message'], 'Premature end of data in tag')) {
                                        echo "Help: The XML ends before all tags were closed.";
                                    }
                                ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

</body>
</html>
