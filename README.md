# MTN MoMo API


[![Latest Version on Packagist](https://img.shields.io/packagist/v/patricpoba/mtn-momo-api-php.svg?style=flat-square)](https://packagist.org/packages/patricpoba/mtn-momo-api-php)
[![GitHub license](https://img.shields.io/github/license/patricpoba/mtn-momo-api-php?style=flat-square)](https://github.com/patricpoba/mtn-momo-api-php/blob/master/LICENSE.md)
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
$ php ./vendor/patricpoba/mtn-momo-api-php/src/SandboxUserProvision.php -k '9cc70894a5d24dba8a8a50fcecbc0568' -c 'https://yourdomain.com' 
```

The option `c` is your callback host and the option `k` is the primary key or `Ocp-Apim-Subscription-Key` for the specific product to which you are subscribed. The `API Key` is unique to the product and you will need an `API Key` for each product you use. You should get a response similar to the following:

```bash 
Your Sandbox credentials :
Ocp-Apim-Subscription-Key: f1d075127844476fa3c4636593e60cf8
UserId (X-Reference-Id)  : 89ce5960-3f68-4da4-bd69-0584073870b8
ApiKey (ApiSecret)       : 46b9302a8ae444c8a7a956bb4c7f2c05
Callback host            : https://yourdomain.com
```


## Configuration

We have to setup up the package to utilise the our momodeveloper credentials by creating an instance of the `MtnConfig`
and pass it to the constructor of the class of the product (collection, disbursement or remittance) we want to use as 
demonstrated below. The configuration can be overriden a product instance by calling the  `->setConfig($config)` method and passing the new config instance.
 


```php
use PatricPoba\MtnMomo\MtnConfig;

 
$config = new MtnConfig([ 
    // mandatory credentials
    'baseUrl'               => 'https://sandbox.momodeveloper.mtn.com', 
    'currency'              => 'EUR', 
    'targetEnvironment'     => 'sandbox', 

    // product specific blocks
    "collectionApiSecret"   => '3463953c31064e6e8ae634cd94f13c8c', 
    "collectionPrimaryKey"  => 'aadb6f286e95415db9024c7a4e2c6025', 
    "collectionUserId"      => 'b4d4019f-8617-4843-a4b8-ed90941747a3',
 
    "disbursementApiSecret" => '3463953c31064e6e8ae634cd94f13c8c', 
    "disbursementPrimaryKey"=> 'aadb6f286e95415db9024c7a4e2c6025', 
    "disbursementUserId"    => 'b4d4019f-8617-4843-a4b8-ed90941747a3',
 
    "remittanceApiSecret"   => '3463953c31064e6e8ae634cd94f13c8c', 
    "remittancePrimaryKey"  => 'aadb6f286e95415db9024c7a4e2c6025', 
    "remittanceUserId"      => 'b4d4019f-8617-4843-a4b8-ed90941747a3'
]);
```


## Collection
 
Collections is used for requesting a payment from a customer (Payer) and checking status of transactions.
[Read more on Momo Collection](https://momodeveloper.mtn.com/docs/services/collection/operations/requesttopay-POST)
 
* `collectionPrimaryKey`: Primary Key for the `Collection` product on the developer portal.
* `collectionUserId`    : For development environment, use the sandbox credentials else use the one on the `developer portal`.
* `collectionApiSecret` : For development environment, use the sandbox credentials else use the one on the `developer portal`.


```php
use PatricPoba\MtnMomo\MtnConfig;

 
$config = new MtnConfig([ 
    // mandatory credentials
    'baseUrl'               => 'https://sandbox.momodeveloper.mtn.com', 
    'currency'              => 'EUR', 
    'targetEnvironment'     => 'sandbox', 

    // collection credentials
    "collectionApiSecret"   => '3463953c31064e6e8ae634cd94f13c8c', 
    "collectionPrimaryKey"  => 'aadb6f286e95415db9024c7a4e2c6025', 
    "collectionUserId"      => 'b4d4019f-8617-4843-a4b8-ed90941747a3'
]);


$collection = new MtnCollection($config); 

$params = [
    "mobileNumber"      => '233540000000', 
    "amount"            => '100', 
    "externalId"        => '774747234',
    "payerMessage"      => 'some note',
    "payeeNote"         => '1212'
];

$transactionId = $collection->requestToPay($params);

$transaction = $collection->getTransaction($transactionId);
```

### Collection Methods

1. `requestToPay`: This operation is used to request a payment from a consumer (Payer). The payer will be asked to authorize the payment. The transaction is executed once the payer has authorized the payment. The transaction will be in status PENDING until it is authorized or declined by the payer or it is timed out by the system. Status of the transaction can be validated by checking the `status` field on the result of `getTransaction()` method.

```php
   $transactionId = $collection->requestToPay($params);
```

2. `getTransaction`: Retrieve transaction information using the `transactionId` returned by `requestToPay`. You can invoke it at intervals until the transaction fails or succeeds. 

```php
   $transaction = $collection->getTransaction($transactionId);
```

3. `getBalance`: Get the balance of the account.

```php
   $transaction = $collection->getBalance();
```

4. `accountHolderActive`: check if an account holder is registered and active in the system.

```php
   $transaction = $collection->accountHolderActive($mobileNumber);
```



## Disbursement
 
Disbursement is used for transferring money from the provider account to a customer.
[Read more on Momo Disbursement](https://momodeveloper.mtn.com/docs/services/disbursement/operations/token-POST) 
 
* `disbursementPrimaryKey`: Primary Key for the `Disbursement` product on the developer portal.
* `disbursementUserId`    : For development environment, use the sandbox credentials else use the one on the `developer portal`.
* `disbursementApiSecret` : For development environment, use the sandbox credentials else use the one on the `developer portal`.




```php 
use PatricPoba\MtnMomo\MtnConfig;
use PatricPoba\MtnMomo\MtnDisbursement;

 
$config = new MtnConfig([ 
    // mandatory credentials
    'baseUrl'               => 'https://sandbox.momodeveloper.mtn.com', 
    'currency'              => 'EUR', 
    'targetEnvironment'     => 'sandbox', 

    // disbursement credentials
    "disbursementApiSecret"   => '3463953c31064e6e8ae634cd94f13c8c', 
    "disbursementPrimaryKey"  => 'aadb6f286e95415db9024c7a4e2c6025', 
    "disbursementUserId"      => 'b4d4019f-8617-4843-a4b8-ed90941747a3'
]);

/**
 * setup disbursement config
 */
$disbursement = new MtnDisbursement($config); 

$params = [
    "mobileNumber"      => '233540000000', 
    "amount"            => '100', 
    "externalId"        => '774747234',
    "payerMessage"      => 'some note',
    "payeeNote"         => '1212'
];

/**
 * Transfer() is used to request a payment from a consumer (Payer). The payer will be asked to authorize the payment. The transaction is executed once the payer has authorized the payment. The transaction will be in status PENDING until it is authorized or declined by the payer or it is timed out by the system. 
 */
$transactionId = $disbursement->transfer($params);

/**
 * Status of the transaction can be validated by checking the `status` 
 * field on the result of `getTransaction()` method.
 */
$transaction = $disbursement->getTransaction($transactionId);
```

### Disbursement Methods


1. `transfer`: This operation is used to request a payment from a consumer (Payer). The payer will be asked to authorize the payment. The transaction is executed once the payer has authorized the payment. The transaction will be in status PENDING until it is authorized or declined by the payer or it is timed out by the system. Status of the transaction can be validated by checking the `status` field on the result of `getTransaction()` method.

```php
   $transactionId = $disbursement->transfer($params);
```

2. `getTransaction`: Retrieve transaction information using the `transactionId` returned by `requestToPay`. You can invoke it at intervals until the transaction fails or succeeds. 

```php
   $transaction = $disbursement->getTransaction($transactionId);
```

3. `getBalance`: Get the balance of your disbursement account.

```php
   $transaction = $disbursement->getBalance();
```

4. `accountHolderActive`: check if an account holder is registered and active in the system.

```php
   $transaction = $disbursement->accountHolderActive($mobileNumber);
```


## Remittance

Transfer operation is used to transfer an amount from the own account to a payee account.

* `disbursementPrimaryKey`: Primary Key for the `Disbursement` product on the developer portal.
* `disbursementUserId`    : For development environment, use the sandbox credentials else use the one on the `developer portal`.
* `disbursementApiSecret` : For development environment, use the sandbox credentials else use the one on the `developer portal`.
  
 
```php 
use PatricPoba\MtnMomo\MtnConfig;
use PatricPoba\MtnMomo\MtnRemittance;

 
$config = new MtnConfig([ 
    // mandatory credentials
    'baseUrl'               => 'https://sandbox.momodeveloper.mtn.com', 
    'currency'              => 'EUR', 
    'targetEnvironment'     => 'sandbox', 

    // disbursement credentials
    "remittanceApiSecret"   => '3463953c31064e6e8ae634cd94f13c8c', 
    "remittancePrimaryKey"  => 'aadb6f286e95415db9024c7a4e2c6025', 
    "remittanceUserId"      => 'b4d4019f-8617-4843-a4b8-ed90941747a3'
]);

/**
 * setup remittance config
 */
$remittance = new MtnRemittance($config); 

$params = [
    "mobileNumber"      => '233540000000', 
    "amount"            => '100', 
    "externalId"        => '774747234',
    "payerMessage"      => 'some note',
    "payeeNote"         => '1212'
];

/**  
 * Transfer operation is used to transfer an amount from the own account to a payee account.
 * Status of the transaction can validated by using the GET /transfer/{referenceId}
 */
$transactionId = $remittance->transfer($params);


/**
 * This operation is used to get the status of a transfer. X-Reference-Id 
 * that was passed in the post is used as reference to the request.
 */
$transaction = $remittance->getTransaction($transactionId);
  
  
/**
 * Get the balance of your disbursement account.
 */
$transaction = $disbursement->getBalance();
 

/**
 * Operation is used to check if an account holder is registered and active in the system.
 */
$transaction = $disbursement->accountHolderActive($mobileNumber);

```

 
### Api Responses

All api calls return the PatricPoba\MtnMomo\Http\ApiResponse object which is described below:

``` bash
/**
* Data in api response can also be accessed directly from the object.
*/
$response->description // 'description' is in api response.

/**
* Get array format of api response
* @return array
*/
$response->toArray() 

/**
* Get json format of api response
* @return string
*/
$response->toJson() 

/**
* Get the status code of the response
* @return numeric
*/
$response->getStatusCode() 

/**
* Get the headers the response 
*/
$response->getHeaders() 

/**
* Checks if api call was successful ie 200, 201 etc
* return bool
*/
$response->isSuccess() 
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
 




