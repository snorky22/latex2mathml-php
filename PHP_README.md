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
