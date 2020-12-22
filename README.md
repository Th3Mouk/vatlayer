VatLayer API PHP wrapper
========================

This PHP library is a wrapper of vatlayer.com respecting PSR18/PSR17/PSR7.

[![Latest Stable Version](https://poser.pugx.org/th3mouk/vatlayer/v/stable)](https://packagist.org/packages/th3mouk/vatlayer)
[![Latest Unstable Version](https://poser.pugx.org/th3mouk/vatlayer/v/unstable)](https://packagist.org/packages/th3mouk/vatlayer)
[![Total Downloads](https://poser.pugx.org/th3mouk/vatlayer/downloads)](https://packagist.org/packages/th3mouk/vatlayer)
[![License](https://poser.pugx.org/th3mouk/vatlayer/license)](https://packagist.org/packages/th3mouk/vatlayer)

![CI](https://github.com/th3Mouk/vatlayer/workflows/ci/badge.svg?branch=feature-1)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/th3mouk/vatlayer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/th3mouk/vatlayer/?branch=master)
[![.gitattributes](https://poser.pugx.org/th3mouk/vatlayer/gitattributes)](//packagist.org/packages/th3mouk/vatlayer)

## Installation

`composer require th3mouk/vatlayer`

## Usage

```php
use Th3Mouk\VatLayer\Psr18Wrapper;

// Client respecting PSR18
// @see https://packagist.org/providers/psr/http-client-implementation
$http_client = WhateverYouWant();

// Request factory respecting PSR17
// @see https://packagist.org/providers/psr/http-factory-implementation
$request_factory = WhateverYouWantToo();

// Or automatically take an http client with
// composer require php-http/discovery
$http_client = Psr18ClientDiscovery::find();
$request_factory = Psr17FactoryDiscovery::findRequestFactory();

$wrapper = new Psr18Wrapper('access_key', $http_client, $request_factory);
$response = $wrapper->validate('vat_number');
```

## Endpoints coverage

[Original documentation](https://vatlayer.com/documentation)

- [x] VAT Number Validation
- [ ] Get VAT Rates via Country Code
- [ ] Get VAT Rates via IP Address
- [ ] Get VAT Rates via Client IP
- [ ] Retrieve all EU VAT Rates
- [ ] VAT Compliant Price Calculation
