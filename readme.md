# Laravel Media Handler

Laravel Media Handler package.

### Content

1. [Installation](https://github.com/bedus-creation/laravel-media#installation)
2. [Publish Assets and Migration](https://github.com/bedus-creation/laravel-media#publish-assests)
3. [Use Trait in model](https://github.com/bedus-creation/laravel-media#add-hasmedia-trait-to-your-model)
4. [Call from anywhere](https://github.com/bedus-creation/laravel-media#use-with-model-form-whereever)
5. [Standalone Media](https://github.com/bedus-creation/laravel-media#standalone-media)

### Installation

```
composer require aammui/laravel-media
```
**Laravel Compatibility**

| Laravel Version          | Laravel Media Version | Installation                                      |
|--------------------------|-----------------------|---------------------------------------------------|
| 10.x, 11.x, 12.x, 13.x   | 4.x                   | ```composer require aammui/laravel-media:^4.0```  |
| 10.x                     | 3.0.0                 | ```composer require aammui/laravel-media:3.0.0``` |
| 9.x             | 2.0.0                   | ```composer require aammui/laravel-media:2.0.0``` |
| 8.x, 7.x, 6.x   | 1.0.0                   | ```composer require aammui/laravel-media:1.0.0``` |

**Requirements (4.x):** PHP 8.1+ with the `gd` extension. Images are processed with
[intervention/image](https://image.intervention.io/v3) v3.

Upgrading from 3.x? See the [CHANGELOG](CHANGELOG.md).

### Publish assests

```
php artisan vendor:publish --provider="Aammui\LaravelMedia\LaravelMediaServiceProvider"
php artisan migrate
```

### Add ```HasMedia``` Trait to your model.

```php
use Aammui\LaravelMedia\Traits\HasMedia;
use Illuminate\Database\Eloquent\Model;
 
class Document extends Model
{
    use HasMedia;
}
```

### Use with model form whereever.

```php
$user->addMedia(request()->file);
$user->toCollection('profilePicture')
    ->addMedia(request()->file);
$user->toCollection('profilePicture')
     ->toDisk('public')
    ->addMedia(request()->file);
$user->toCollection('profilePicture')
     ->toDisk('public')
    ->addMedia(request()->file);
```

### Add Media From URL

```This doesnot download media into your storage.``` It just add url to the database and when your application serve,
media will load from remote url.

```php
$user->addMediaFromUrl('http://example.com/image.jpeg');
```

##### Download and Add Media From Remote URL.

```php
$user->setDownloadTrue()
    ->addMediaFromUrl('http://example.com/image.jpeg');
```

### Retrive media from your model

```php
$user->getMedia();
$user->fromCollection('profilePicture')
    ->fromDisk('local')
    ->getMedia();
```

### Setup Responsive images

The standard responsive image size can be defined into the configuration file. The original image will be resized into
the different sizes defined in configuration.

```php
use Aammui\LaravelMedia\Enum\Responsive;

'responsive'=>[
   'responsive' => [
        Responsive::SM => [
            'w' => 50,
            'h' => 50,
        ],
        Responsive::MD => [
            'w' => 150,
            'h' => 150,
        ],
        Responsive::LG => [
            'w' => 600, // Can define either height or width only.
        ],
    ],
];
```

##### Only Particular size of image:

```php

use Aammui\LaravelMedia\Facades\Media;
use Aammui\LaravelMedia\Enum\Responsive;

Media::ofSize([Responsive::SM,Responsive::MD])
    ->addMedia(request()->file);
```

### Enable Image optimization

```
'optimize'=>true
```

### Standalone Media

Suppose you want to add file which doesnot belong to any model, then you can create standalone media. Use case could be
you are storing images in description of some products in ecommerce shop, Where you simply store images in database
before the product form is submitted via api, which returns public image url.

```php
use Aammui\LaravelMedia\Facades\Media; // Use Media facade instead

Media::addMedia(request()->file); // returns Media Object
Media::toCollection('profilePicture')
    ->addMedia(request()->file); // returns Media Object while storing grouping files in profilepicture
Media::toCollection('profilePicture')
    ->toStorage('s3')
    ->addMedia(request()->file); // returns Media Object while storing files in s3
```
