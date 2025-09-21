# Playground: Lead Resource

[![Playground CI Workflow](https://github.com/gammamatrix/playground-lead-resource/actions/workflows/ci.yml/badge.svg?branch=develop)](https://raw.githubusercontent.com/gammamatrix/playground-lead-resource/testing/develop/testdox.txt)
[![Test Coverage](https://raw.githubusercontent.com/gammamatrix/playground-lead-resource/testing/develop/coverage.svg)](tests)
[![PHPStan Level 10](https://img.shields.io/badge/PHPStan-level%2010-brightgreen)](.github/workflows/ci.yml#L128)

Playground: Lead Resource

This package provides an API and a Blade UI for interacting with the [Playground: Lead](https://github.com/gammamatrix/playground-lead), a model package for Laravel.

If you need a JSON API without a UI, then have a look at [Playground: Lead API.](https://github.com/gammamatrix/playground-lead-api)

## Documentation

Read more on using [Playground: Lead Resource at Read the Docs: Playground Documentation](https://gammamatrix-playground.readthedocs.io/en/develop/built-components/lead.html)

### Postman

A postman collection is provided in the repository: [postman-playground-lead-resource.json.](postman-playground-lead-resource.json)
- This same collection is viewable on the [.]()

### OpenAPI

This application provides OpenAPI documentation: [openapi.yaml](openapi.yaml).
- The endpoint models support locks, trash with force delete, restoring, revisions and more.
- Index endpoints support advanced query filtering.

OpenAPI API Documentation is built with npm using Redocly.
- npm is only needed to generate documentation and is not needed to operate the Playground: Lead Resource API.

See [package.json](package.json) requirements.

Install npm.

```sh
npm install
```

Build the documentation to generate the [openapi.yaml](openapi.yaml) configuration.

```sh
npm run docs
```

Documentation
- Preview [openapi.yaml on the Redocly Editor UI.](https://redocly.github.io/redoc/?url=https://raw.githubusercontent.com/gammamatrix/playground-lead-resource/develop/openapi.yaml)

## Installation

You can install the package via composer:

```bash
composer require gammamatrix/playground-lead-resource
```

## `artisan about`

Playground provides information in the `artisan about` command.

<!-- <img src="resources/docs/artisan-about-playground-lead-resource.png" alt="screenshot of artisan about command with Playground: Lead Resource."> -->

## Configuration

You can publish the config file with:

```bash
php artisan vendor:publish --provider="Playground\Lead\Resource\ServiceProvider" --tag="playground-config"
```

All routes are enabled by default. They may be disabled via enviroment variable or the configuration.

See the contents of the published config file: [config/playground-lead-resource.php](config/playground-lead-resource.php)

You can publish the routes file with:
```bash
php artisan vendor:publish --provider="Playground\Lead\Resource\ServiceProvider" --tag="playground-routes"
```
- The routes while be published in a folder at `routes/playground-lead-resource`

### Environment Variables

If you are unable or do not want to publish [configuration files for this package](config/playground-lead-resource.php),
you may override the options via system environment variables.

Information on [environment variables is available on the wiki for this package](https://github.com/gammamatrix/playground-lead-resource/wiki/Environment-Variables)

## Migrations

This package requires the migrations in [playground-lead](https://github.com/gammamatrix/playground-lead) a Laravel package.

## Cloc

```sh
composer cloc
```

```
➜  playground-lead-resource git:(develop) ✗ composer cloc
     869 text files.
     852 unique files.
     176 files ignored.

github.com/AlDanial/cloc v 2.06  T=0.33 s (2608.0 files/s, 270056.6 lines/s)
-------------------------------------------------------------------------------
Language                     files          blank        comment           code
-------------------------------------------------------------------------------
YAML                           114              5              0          31453
JSON                           349              0              0          21450
PHP                            324           3459           4188          17133
Blade                           49            314              0           9371
XML                             12              0              7            644
Markdown                         3             55              1            129
INI                              1              3              0             12
-------------------------------------------------------------------------------
SUM:                           852           3836           4196          80192
-------------------------------------------------------------------------------
```

## PHPStan

Tests at level 10 on:
- `config/`
- `resources/views/`
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

Run unit tests:
```sh
composer test
```

Run unit and feature tests:
```sh
composer test-dev
```

Run unit and feature tests in parallel:
```sh
composer test-parallel
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
