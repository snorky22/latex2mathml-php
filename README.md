# Latex2MathML-PHP

Pure PHP 8.4 library for LaTeX to MathML conversion. This is a port of the Python library [latex2mathml](https://github.com/roniemartinez/latex2mathml).

## Features

- No external dependencies (only PHP 8.4+ and `ext-dom`).
- Supports a wide range of LaTeX commands and symbols.
- Outputs clean MathML.
- Supports inline and block display modes.

## Installation

```bash
composer require latex2mathml/latex2mathml
```

## Usage

### Simple conversion

```php
use Latex2MathML\Converter;

$latex = 'a^2 + b^2 = c^2';
$mathml = Converter::convert($latex);
echo $mathml;
```

### Block display

```php
$mathml = Converter::convert($latex, display: 'block');
```

### Custom XML namespace

```php
$mathml = Converter::convert($latex, xmlns: 'http://custom-namespace.org');
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
