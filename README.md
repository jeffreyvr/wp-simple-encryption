# WP Simple Encryption

A simple package to encrypt and decrypt strings in WordPress.

## Installation

```bash
composer require jeffreyvanrossum/wp-simple-encryption
```

## How it works

This package attempts to write a constant to your site's `wp-config.php` where the secret key is stored.

You may define the name of this constant when instantiating the `WPSimpleEncryption` class.

This package utilizes [php-encryption](https://github.com/defuse/php-encryption/) for the encryption and decryption.

## Usage

```php
use Jeffreyvr\WPSimpleEncryption\WPSimpleEncryption;

$wp_simple_encryption = new WPSimpleEncryption('YOUR_SECRET_KEY_CONSTANT');

$wp_simple_encryption->encrypt('some unencrypted string');

$wp_simple_encryption->decrypt('some encrypted string');
```

## Contributors
* [Jeffrey van Rossum](https://github.com/jeffreyvr)
* [All contributors](https://github.com/jeffreyvr/wp-simple-encryption/graphs/contributors)

## License
MIT. Please see the [License File](/LICENSE) for more information.
