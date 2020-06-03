# filestorage
A blazing fast and light-weight flat-file storage designed for storing array data to keys.

[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.3-informational)](https://php.net/)
[![PHPStan](https://img.shields.io/badge/PHPStan-passing-success)](https://phpstan.org/)
[![PHP Unittest](https://img.shields.io/badge/tests-15-blue)](https://packagist.org/packages/phpunit/phpunit)
[![Twitter](https://img.shields.io/twitter/url?style=social&url=https%3A%2F%2Fgithub.com%2Funclexo%2Ffilestorage)](https://twitter.com/intent/tweet?text=Wow:&url=https%3A%2F%2Fgithub.com%2Funclexo%2Ffilestorage)

## Sample Array Data

```php
<?php

$data = [
    'facebook' => [
        'clientId'      => 'facebookClientId',
        'clientSecret'  => 'facebookClientSecret',
        'redirectUri'   => 'facebookRedirectUri',
    ],
    'twitter' => [
        'clientId'      => 'twitterClientId',
        'clientSecret'  => 'twitterClientSecret',
        'redirectUri'   => 'twitterRedirectUri',
    ],
];
```

Alternatively, you can store (key => value) pairs too.

```php
<?php
 
$data = [
    'key' => 'value',
    'more_key' => ['key' => 'value'],
];
```

## Download using composer

```bash
composer require unclexo/filestorage
```

## Creating a store
`filestorage` stores array data into a file. You can create a file-storage using `Storage::create($data, $location)`. 
Keep in mind `$location` must be existed and writable.

```php
<?php

require_once './vendor/autoload.php';

use Xo\Storage\Storage;

$data = [
    'facebook' => [
        'clientId'      => 'facebookClientId',
        'clientSecret'  => 'facebookClientSecret',
        'redirectUri'   => 'facebookRedirectUri',
    ],
    'twitter' => [
        'clientId'      => 'twitterClientId',
        'clientSecret'  => 'twitterClientSecret',
        'redirectUri'   => 'twitterRedirectUri',
    ],
];

/** File must be writable */
$location = '/home/username/data/storage.txt';

Storage::create($data, $location);
```

## Using the store
Once you've created a store, you can use the store through the whole application. Just create an instance of the store specifying the file location and use wherever you need it.

```php
<?php

require_once './vendor/autoload.php';

use Xo\Storage\Storage;

$location = '/home/username/data/storage.txt';

Storage::getInstance($location);
```

### Get data for a given key
Now, you're able to get value for a given key from the store you created earlier. 

```
Storage::get('facebook'); /** returns */

[
    'clientId'      => 'facebookClientId',
    'clientSecret'  => 'facebookClientSecret',
    'redirectUri    => 'facebookRedirectUri',
]
```

#### To set data
```
Storage::set(string $key, mixed $value);
```

#### To update data
```
Storage::update(string $key, array $array);
```  

#### To check the availability of a key
```
Storage::has(string $key);
```  

#### To get all data
```
Storage::all();
```   

#### To remove data
```
Storage::remove(string $key);
```    

#### To clear all data
```
Storage::clear();
```    

#### To delete the store
```
Storage::delete();
```

## License
[![GitHub license](https://img.shields.io/github/license/unclexo/filestorage)](https://github.com/unclexo/filestorage/blob/master/LICENSE)
  


