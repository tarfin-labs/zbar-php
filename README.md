# zbar-php

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tarfin-labs/zbar-php.svg?style=flat-square)](https://packagist.org/packages/tarfin-labs/zbar-php)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/tarfin-labs/zbar-php/tests?label=tests)
[![Quality Score](https://img.shields.io/scrutinizer/g/tarfin-labs/zbar-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/tarfin-labs/zbar-php)
[![Total Downloads](https://img.shields.io/packagist/dt/tarfin-labs/zbar-php.svg?style=flat-square)](https://packagist.org/packages/tarfin-labs/zbar-php)

## Introduction
zbar-php is a php package that provides an interface to the zbar bar-code reading library.

## Requirements

You should have [zbar](http://zbar.sourceforge.net/) and [imagemagick](https://imagemagick.org/) installed.

## Installation

You can install the package via composer:

```bash
composer require tarfin-labs/zbar-php
```

## Usage

Scanning bar-code or qr-code with zbar is simple.

``` php
$zbar = new \TarfinLabs\ZbarPhp\Zbar($imagePath);
$code = $zbar->scan();
```

Supported file formats: `pdf`, `jpeg`, `jpg`, `svg` and `gif`.

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email faruk.can@tarfin.com instead of using the issue tracker.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information about what has changed recently.

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update the tests as appropriate.

### Security

If you discover any security-related issues, please email development@tarfin.com instead of using the issue tracker.

## Credits

- [Faruk Can](https://github.com/frkcn)
- [Yunus Emre Deligöz](https://github.com/deligoez)
- [Hakan Özdemir](https://github.com/hozdemir)
- [Turan Karatuğ](https://github.com/tkaratug)
- [All Contributors](../../contributors)

### License
zbar-php is open-sourced software licensed under the MIT license.
