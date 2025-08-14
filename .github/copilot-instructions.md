# MTN MoMo API PHP Library

MTN MoMo API PHP is a Composer library that provides a PHP wrapper for the [MTN MoMo API](https://momodeveloper.mtn.com). It supports both standalone PHP applications and Laravel applications with service providers and facades for Collection, Disbursement, and Remittance operations.

Always reference these instructions first and fallback to search or bash commands only when you encounter unexpected information that does not match the info here.

## Working Effectively

Bootstrap, build, and test the repository:
- `composer install --no-interaction --prefer-dist` -- takes 5+ minutes due to network timeouts. NEVER CANCEL. Set timeout to 10+ minutes.
- `composer test` -- takes under 1 second. Tests are very fast.
- `vendor/bin/phpunit --testdox` -- runs tests with detailed output, also under 1 second.

Test the command line sandbox user provision tool:
- `php src/SandboxUserProvision.php -k "your-primary-key" -c "https://your-callback-domain.com"` -- requires actual MTN API credentials and network access to MTN endpoints.

## Validation

Always validate any changes to the library by testing basic functionality:
- Create an instance of `MtnConfig` with test credentials
- Instantiate `MtnCollection`, `MtnDisbursement`, and `MtnRemittance` services
- Verify that key methods exist: `requestToPay`, `transfer`, `getTransaction`, `getBalance`, `accountHolderActive`
- Run `composer test` to ensure all PHPUnit tests pass
- The tests include both unit tests and Laravel-specific integration tests

ALWAYS run the following validation scenario after making changes:
```php
use PatricPoba\MtnMomo\MtnConfig;
use PatricPoba\MtnMomo\MtnCollection;

$config = new MtnConfig([
    'baseUrl' => 'https://sandbox.momodeveloper.mtn.com',
    'currency' => 'EUR',
    'targetEnvironment' => 'sandbox',
    'collectionApiSecret' => 'test',
    'collectionPrimaryKey' => 'test', 
    'collectionUserId' => 'test'
]);

$collection = new MtnCollection($config);
// Should instantiate without errors
```

## Build and Test Timing

- **CRITICAL**: `composer install` takes 5+ minutes due to network timeouts when downloading packages. NEVER CANCEL this command. Many packages will fallback to source downloads due to GitHub API rate limits and network timeouts.
- **Tests run in under 1 second** - PHPUnit executes very quickly for this library
- **Coverage generation**: `composer test-coverage` works but requires Xdebug configuration

## Important Notes

- This is a **library, not an application** - there is no "running" the code, only testing it and using it in other applications
- **Network access required**: The sandbox user provision script and actual API operations require network access to `sandbox.momodeveloper.mtn.com`
- **PHP Compatibility**: Supports PHP 7.0+ and PHP 8.0+ 
- **PHPUnit Version**: Uses PHPUnit 10.x - ensure `setUp()` methods have `: void` return type
- **Laravel Support**: Includes service providers and facades for Laravel integration

## Code Quality

The project uses external code quality tools:
- **StyleCI** for automated code style enforcement (Laravel preset)
- **Scrutinizer** for code quality analysis  
- **Travis CI** and **Azure Pipelines** for continuous integration

No local linting tools are configured. StyleCI handles code formatting automatically on commits.

## Library Structure

Key directories and files:
- `src/` - Main library code
  - `MtnConfig.php` - Configuration management
  - `MtnCollection.php` - Collection service (payments)
  - `MtnDisbursement.php` - Disbursement service (transfers)  
  - `MtnRemittance.php` - Remittance service
  - `SandboxUserProvision.php` - Command line tool for creating sandbox users
  - `Facades/` - Laravel facade classes
  - `Http/` - HTTP client abstraction
- `tests/` - PHPUnit tests
  - `Unit/` - Unit tests for core classes
  - `LaravelMtnMomoTest.php` - Laravel integration tests
- `config/` - Configuration files

## Common Commands Reference

### Install Dependencies
```bash
composer install --no-interaction --prefer-dist
# Takes: 5+ minutes (NEVER CANCEL)
# Note: Network timeouts are normal, packages fallback to source downloads
```

### Run Tests  
```bash
composer test
# Takes: <1 second
# Or: vendor/bin/phpunit --testdox
```

### Generate Coverage
```bash  
composer test-coverage
# Takes: <1 second  
# Note: Requires Xdebug configuration for coverage generation
```

### Sandbox User Provision
```bash
php src/SandboxUserProvision.php -k "primary-key" -c "https://callback-url.com"
# Requires: Valid MTN API credentials and network access
# Purpose: Creates sandbox user credentials for testing
```

## Troubleshooting

**Composer Install Timeouts**: Normal behavior due to GitHub API limits. Composer automatically falls back to source downloads. Wait for completion.

**PHPUnit Compatibility**: If you see setUp() method errors, ensure test methods use `public function setUp(): void` syntax for PHPUnit 10 compatibility.

**Network Errors**: API operations and sandbox user provision require network access to MTN endpoints. In restricted environments, these will fail with connection errors.

**Coverage Reports**: Require Xdebug extension with coverage mode enabled. Without Xdebug, coverage generation will show warnings but tests still run.