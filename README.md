# RawRequest - A Simple Http Request Interface Class for PHP Applications

## Package Features
- Provides an interface to the current HTTP request
- Help with creating relative and absolute site urls

## Installation

### Composer
RawRequest is available via [Composer/Packagist](https://packagist.org/packages/rawphp/raw-request).

Add `"rawphp/raw-request": "0.*@dev"` to the require block in your composer.json and then run `composer install`.

```json
{
        "require": {
            "rawphp/raw-request": "0.*@dev"
        }
}
```

You can also simply run the following from the command line:

```sh
composer require rawphp/raw-request "0.*@dev"
```

### Tarball
Alternatively, just copy the contents of the RawRequest folder into somewhere that's in your PHP `include_path` setting. If you don't speak git or just want a tarball, click the 'zip' button at the top of the page in GitHub.

## Basic Usage

```php
<?php

defined( 'BASE_URL' ) || define( 'BASE_URL', 'http://rawphp.org/' );

use RawPHP\RawRequest\Request;

// create new request instance
$request = new Request( );

// initialise request
$request->init( );

// get current route and params
$route = $request->route;
$params = $request->params;

// create a new relative url
$url = $request->createUrl( 'users/get', array( 1 ) );

// or absolute url
$url = $request->createUrl( 'users/get', array( 1 ), TRUE );

```

## License
This package is licensed under the [MIT](https://github.com/rawphp/RawRequest/blob/master/LICENSE). Read LICENSE for information on the software availability and distribution.

## Contributing

Please submit bug reports, suggestions and pull requests to the [GitHub issue tracker](https://github.com/rawphp/RawRequest/issues).

## Changelog

#### 12-09-2014
- Initial Code Commit
