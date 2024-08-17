# Playground: Lead Resource

[![Playground CI Workflow](https://github.com/gammamatrix/playground-lead-resource/actions/workflows/ci.yml/badge.svg?branch=develop)](https://raw.githubusercontent.com/gammamatrix/playground-lead-resource/testing/develop/testdox.txt)
[![Test Coverage](https://raw.githubusercontent.com/gammamatrix/playground-lead-resource/testing/develop/coverage.svg)](tests)
[![PHPStan Level 9](https://img.shields.io/badge/PHPStan-level%209-brightgreen)](.github/workflows/ci.yml#L120)

The Playground: Lead Resource package.

## Documentation

### Swagger

This application provides Swagger documentation: [swagger.json](swagger.json).
- The endpoint models support locks, trash with force delete, restoring, revisions and more.
- Index endpoints support advanced query filtering.

Swagger API Documentation is built with npm.
- npm is only needed to generate documentation and is not needed to operate the CMS API.

See [package.json](package.json) requirements.

Install npm.

```sh
npm install
```

Build the documentation to generate the [swagger.json](swagger.json) configuration.

```sh
npm run docs
```

Documentation
- Preview [swagger.json on the Swagger Editor UI.](https://editor.swagger.io/?url=https://raw.githubusercontent.com/gammamatrix/playground-lead-resource/develop/swagger.json)
- Preview [swagger.json on the Redocly Editor UI.](https://redocly.github.io/redoc/?url=https://raw.githubusercontent.com/gammamatrix/playground-lead-resource/develop/swagger.json)

## Installation

You can install the package via composer:

```bash
composer require gammamatrix/playground-lead-resource
```

## Configuration

All options are disabled by default.

See the contents of the published config file: [config/playground-lead-resource.php](config/playground-lead-resource.php)

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Playground\Lead\Resource\ServiceProvider" --tag="playground-config"
```

## Cloc

```sh
composer cloc
```

```
➜  playground-lead-resource git:(develop) ✗ composer cloc
> cloc --exclude-dir=node_modules,output,vendor .
     936 text files.
     732 unique files.
     205 files ignored.

github.com/AlDanial/cloc v 1.98  T=0.97 s (752.8 files/s, 87447.3 lines/s)
-------------------------------------------------------------------------------
Language                     files          blank        comment           code
-------------------------------------------------------------------------------
JSON                           250              0              0          39291
PHP                            325           2845           4033          17996
YAML                           112              5              0          13654
Blade                           38            235              7           6609
XML                              3              0              7            215
Markdown                         3             37              0             84
INI                              1              3              0             12
-------------------------------------------------------------------------------
SUM:                           732           3125           4047          77861
-------------------------------------------------------------------------------
```

## PHPStan

Tests at level 9 on:
- `config/`
- `routes/`
- `src/`
- `tests/Feature/`
- `tests/Unit/`

```sh
composer analyse
```

## Coding Standards

```sh
composer format
```

## Testing

```sh
composer test --parallel
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
