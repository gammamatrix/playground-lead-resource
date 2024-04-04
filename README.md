# Playground Lead Resource

[![Playground CI Workflow](https://github.com/gammamatrix/playground-lead-resource/actions/workflows/ci.yml/badge.svg?branch=develop)](https://raw.githubusercontent.com/gammamatrix/playground-lead-resource/testing/develop/testdox.txt)
[![Test Coverage](https://raw.githubusercontent.com/gammamatrix/playground-lead-resource/testing/develop/coverage.svg)](tests)
[![PHPStan Level 9 src and tests](https://img.shields.io/badge/PHPStan-level%209-brightgreen)](.github/workflows/ci.yml#L120)

The `playground-lead-resource` Laravel package.

This package provides an Resource for interacting with the [Playground Lead](https://github.com/gammamatrix/playground-lead), a Campaign Sales Management tool.

If you need a UI, then use [Playground Lead Resource](https://github.com/gammamatrix/playground-lead-resource), which provides a Blade UI.

## Documentation

Read more on using [Playground Lead Resource at Read the Docs: Playground Documentation.](https://gammamatrix-playground.readthedocs.io/en/develop/components/lead.html)

<!-- ### Postman

A postman collection is provided in the repository: [postman-playground-lead-resource.json.](postman-playground-lead-resource.json)
- This same collection is viewable on the [Postman: GammaMatrix Playground workspace.](https://www.postman.com/gammamatrix/workspace/playground) -->

### Swagger

This application provides Swagger documentation: [swagger.json](swagger.json).
- The endpoint models support locks, trash with force delete, restoring, revisions and more.
- Index endpoints support advanced query filtering.

Swagger Resource Documentation is built with npm.
- npm is only needed to generate documentation and is not needed to operate the LEAD Resource.

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

## `artisan about`

Playground provides information in the `artisan about` command.

<!-- <img src="resources/docs/artisan-about-playground-lead-resource.png" alt="screenshot of artisan about command with Playground Lead Resource."> -->

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

## PHPStan

Tests at level 9 on:
- `config/`
- `database/`
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

## Tests

```sh
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.
