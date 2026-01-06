# Latex2MathML-PHP

Pure PHP 8.4 library for LaTeX to MathML conversion. This is a port of the Python library [latex2mathml](https://github.com/roniemartinez/latex2mathml).

## Features

- No external dependencies (only PHP 8.4+ and `ext-dom`).
- Supports a wide range of LaTeX commands and symbols.
- Outputs clean MathML.
- Supports inline and block display modes.

## Installation

```bash
composer require latex2mathml/latex2mathml-php
```

## Usage

### Simple conversion

By default, the converter produces inline MathML.

```php
use Latex2MathML\Converter;

$latex = 'a^2 + b^2 = c^2';
$mathml = Converter::convert($latex);
echo $mathml;
// <math xmlns="http://www.w3.org/1998/Math/MathML" display="inline">...</math>
```

### Display Mode

You can specify the display mode (`inline` or `block`) as the second argument. For clarity and to ensure future compatibility, **using named arguments (PHP 8.0+) is recommended**.

```php
// Recommended: Named argument (PHP 8.0+)
$mathml = Converter::convert($latex, display: 'block');

// Also possible: Positional argument
$mathml = Converter::convert($latex, 'block');
```

### Custom XML namespace

The third argument allows you to specify a custom XML namespace. It defaults to the standard MathML namespace (`http://www.w3.org/1998/Math/MathML`). Pass an empty string if you don't want a namespace attribute. Again, **named arguments are recommended**.

```php
// Recommended: Named argument
$mathml = Converter::convert($latex, xmlns: 'http://custom-namespace.org');

// Also possible: Positional arguments
$mathml = Converter::convert($latex, 'inline', 'http://custom-namespace.org');
```

### Integration with DOMDocument

If you are already working with a `DOMDocument`, you can use `convert_to_element` to get a `DOMElement` instead of a string.

```php
$dom = new DOMDocument();
$mathElement = Converter::convert_to_element($latex, $dom, display: 'block');
$dom->appendChild($mathElement);
echo $dom->saveXML();
```

## Visualization Script

A helper script `display_math.php` is included to visualize LaTeX conversion from `.tex` files.

1. Place one or more `.tex` files in the `tests/` directory.
2. Run the script and redirect the output to an HTML file:
   ```bash
   php display_math.php > output.html
   ```
3. Open `output.html` in your web browser to see the rendered MathML.

The script automatically:
- Finds the first `.tex` file in the `tests/` directory.
- Extracts math expressions delimited by `$`, `$$`, or various LaTeX environments (e.g., `equation`, `align`, `split`).
- Converts them to MathML and displays them in a side-by-side view.

## Architecture

The project is structured as follows:

- `Latex2MathML\Converter`: Main entry point for conversion.
- `Latex2MathML\Tokenizer`: Breaks LaTeX string into tokens.
- `Latex2MathML\Walker`: Parses tokens into a tree of `Node` objects.
- `Latex2MathML\SymbolsParser`: Handles conversion of LaTeX symbols to Unicode MathML entities using `unimathsymbols.txt`.
- `Latex2MathML\Commands`: Contains definitions and maps for LaTeX commands.

## Requirements

- PHP 8.4 or higher.
- `dom` extension.

## License

MIT
