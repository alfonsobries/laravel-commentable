# Laravel Commentable

A Laravel package to add comments to any model

[![Stable Version](http://poser.pugx.org/alfonsobries/laravel-commentable/v)](https://packagist.org/packages/alfonsobries/laravel-commentable) [![License](http://poser.pugx.org/alfonsobries/laravel-commentable/license)](https://packagist.org/packages/alfonsobries/laravel-commentable) [![PHP Version Require](http://poser.pugx.org/alfonsobries/laravel-commentable/require/php)](https://packagist.org/packages/alfonsobries/laravel-commentable)

## Installation

```console
composer require alfonsobries/laravel-commentable
```

## Use

1. Configure the model that is going to receive the comments with the `HasComments` contract and add the `Commentable` trait.

```php
<?php

namespace App\Models;

use Alfonsobries\LaravelCommentable\Contracts\HasComments;
use Alfonsobries\LaravelCommentable\Traits\Commentable;
use Illuminate\Database\Eloquent\Model;


class BlogPost extends Model implements HasComments
{
    use Commentable;
    // ...
}
```

2. Add the `CanComment` contract and the `CanCommentTrait` trait to the model that is going to add the comment (usually the `User` Model).

```php
<?php

namespace App\Models;

use Alfonsobries\LaravelCommentable\Contracts\CanComment;
use Alfonsobries\LaravelCommentable\Traits\CanCommentTrait;
use Illuminate\Database\Eloquent\Model;


class User extends Model implements CanComment
{
    use CanCommentTrait;
    // ...
}
```

3. Use the `addComment` method to add an anonymous comment


```php
$blogPost = BlogPost::first();

$comment = $blogPost->addComment('my comment');
```

4. Use the `addCommentFrom` method to add an anonymous comment from the User (or the model that implements the `CanComment` contract)


```php
$user = User::first();
$blogPost = BlogPost::first();

$comment = $blogPost->addCommentFrom($user, 'my comment');
```

5. You can also comment with the User model (or the model that implements the `CanComment` contract) by using the `comment` method.

```php
$user = User::first();
$blogPost = BlogPost::first();

$comment = $user->comment($blogPost, 'my comment');
```

6. You can get all the user comments with the `comments` method

```php
$comments = User::first()->comments();
```

6. You can get all the comments associated with the commentable model with the `comments` method

```php
$comments = BlogPost::first()->comments();
```


### Analyze the code with `phpstan`

```bash
composer analyse
```

### Refactor the code with php `rector`

```bash
composer refactor
```

### Format the code with `php-cs-fixer`

```bash
composer format
```

### Run tests

```bash
composer test
```

## Security

If you discover a security vulnerability within this package, please write trough the [https://alfonsobries.com](https://alfonsobries.com) contact form. All security vulnerabilities will be promptly addressed.

## Credits

This project exists thanks to all the people who [contribute](../../contributors).

## License

[MIT](LICENSE) © [Alfonso Bribiesca](https://alfonsobries.com)
