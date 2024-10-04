# CallMap

Map method and function calls to the methods or functions in which they happen.

## Purpose

This allows to create a mapping file that can be used to generate an overview of which method is called where.

Whether that is to generate a graphical overview or to check which Namespace boundaries are crossed is a
separate topic then.

The package itself is a plugin to pPHPStan.

## Installation

Install via composer

```bash
composer require --dev phpdepend/callmap
```

## Usage

Run via [PHPStan](https://phpstan.org)

```bash
./vendor/bin/phpstan analyse -c vendor/phpdepend/callmap/callmap.neon <path/to/your/sources>
```

This will create a JSON containing an array of objects that can be used to create a map of method-calls.
There will be one entry per call. If one method is called multiple times from a method, there will be
one entry per single call.

The Objects contain these attributes:

* callingClass
* callingMethod
* calledClass
* calledMethod

The file `callmap.json` is the callmap file for this plugin.
