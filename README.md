# MTN MoMo API


[![Latest Version on Packagist](https://img.shields.io/packagist/v/patricpoba/mtn-momo-api-php.svg?style=flat-square)](https://packagist.org/packages/patricpoba/mtn-momo-api-php)
[![Build Status](https://img.shields.io/travis/patricpoba/mtn-momo-api-php/master.svg?style=flat-square)](https://travis-ci.org/patricpoba/mtn-momo-api-php)
[![Quality Score](https://img.shields.io/scrutinizer/g/patricpoba/mtn-momo-api-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/patricpoba/mtn-momo-api-php)
[![Total Downloads](https://img.shields.io/packagist/dt/patricpoba/mtn-momo-api-php.svg?style=flat-square)](https://packagist.org/packages/patricpoba/mtn-momo-api-php)

This package helps you integrate the [MTN MOMO API](https://momodeveloper.mtn.com) into your Php or Laravel application. Its wrapper around the MTN Open API to provide you with a much simpler API to work with.

## Installation

You are required to have PHP 7.0 or later. You can install the package via composer:

```bash
composer require patricpoba/mtn-momo-api-php
```

# Usage
 
In production, the needed credentials are provided for you on the MTN OVA management dashboard after KYC requirements are met.
But in a testing environment, a sanbox user would have to be created by you using the API, which this package can do for you. 

## Creating a sandbox environment API user 

We need to get the `User ID` and `User Secret` and to do this we shall need to use the Primary Key for the Product to which we are subscribed, as well as specify a host. The library ships with a commandline application that helps to create sandbox credentials. It assumes you have created an account on `https://momodeveloper.mtn.com` and have your `Ocp-Apim-Subscription-Key` (primaryKey) located at `https://momodeveloper.mtn.com/developer`. 

```bash
## On the command line, at the root of your project, run 
$ ./vendor/mtn-momo-api-php/src/SandboxUserProvision.php -k '9cc70894a5d24dba8a8a50fcecbc0568' -c 'https://yourdomain.com' 
```

The option `c` is your callback host and the option `k` is the primary key or `Ocp-Apim-Subscription-Key` for the specific product to which you are subscribed. The `API Key` is unique to the product and you will need an `API Key` for each product you use. You should get a response similar to the following:

```bash 
Your Sandbox credentials :
Ocp-Apim-Subscription-Key: f1d075127844476fa3c4636593e60cf8
UserId (X-Reference-Id)  : 89ce5960-3f68-4da4-bd69-0584073870b8
ApiKey (ApiSecret)       : 46b9302a8ae444c8a7a956bb4c7f2c05
Callback host            : https://yourdomain.com
```

 
### Testing

``` bash
./vendor/bin/phpunit
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email poba.dev@outlook.com instead of using the issue tracker.

## Credits

- [Patric Poba](https://github.com/patricpoba)
- [All Contributors](../../contributors)
- [PHP Package Boilerplate](https://laravelpackageboilerplate.com).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
 




