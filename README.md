# Laravel Commentable

A Laravel package to add comments to any model

[![Stable Version](http://poser.pugx.org/alfonsobries/laravel-commentable/v)](https://packagist.org/packages/alfonsobries/laravel-commentable) [![License](http://poser.pugx.org/alfonsobries/laravel-commentable/license)](https://packagist.org/packages/alfonsobries/laravel-commentable) [![PHP Version Require](http://poser.pugx.org/alfonsobries/laravel-commentable/require/php)](https://packagist.org/packages/alfonsobries/laravel-commentable)

## Use

### Installation

1. Install the composer package:

```console
composer require alfonsobries/laravel-commentable
```

2. Publish the database migration

```console
php artisan vendor:publish --provider="Alfonsobries\LaravelCommentable\LaravelCommentableServiceProvider" --tag="migrations"
```

3. Optionally publish the config file

```console
php artisan vendor:publish --provider="Alfonsobries\LaravelCommentable\LaravelCommentableServiceProvider" --tag="config"
```


4. Configure the model that is going to receive the comments with the `CommentableInterface` contract and add the `Commentable` trait.

```php
<?php

namespace App\Models;

use Alfonsobries\LaravelCommentable\Contracts\CommentableInterface;
use Alfonsobries\LaravelCommentable\Traits\Commentable;
use Illuminate\Database\Eloquent\Model;


class BlogPost extends Model implements CommentableInterface
{
    use Commentable;
    // ...
}
```

5. Add the `CanCommentInterface` contract and the `CanComment` trait to the model that is going to add the comment (usually the `User` Model). Notice that this is optional if you are going to add anonymous comments.

```php
<?php

namespace App\Models;

use Alfonsobries\LaravelCommentable\Contracts\CanCommentInterface;
use Alfonsobries\LaravelCommentable\Traits\CanComment;
use Illuminate\Database\Eloquent\Model;


class User extends Model implements CanCommentInterface
{
    use CanComment;
    // ...
}
```

### Add comments

1. Use the `addComment` method to add an anonymous comment


```php
$blogPost = BlogPost::first();

$comment = $blogPost->addComment('my comment');
```

2. Use the `addCommentFrom` method to add a comment from a User (or the model that implements the `CanCommentInterface` contract)

```php
$user = User::first();
$blogPost = BlogPost::first();

$comment = $blogPost->addCommentFrom($user, 'my comment');
```

3. You can also comment with the User model (or the model that implements the `CanCommentInterface` contract) by using the `comment` method.

```php
$user = User::first();
$blogPost = BlogPost::first();

$comment = $user->comment($blogPost, 'my comment');
```

### Add replies to comments

1. The `Comment` method is another commentable instance, meaning you can use the `addComment` or `addCommentFrom` method to add a reply.

2. You can also use the `reply` and `replyFrom` methods that are just an alias for the comments above. 


```php
$user = User::create([...]);
$user2 = User::create([...]);
$blogPost = BlogPost::first();

$comment = $blogPost->commentFrom($user, 'my comment');
$comment->replyFrom($user2, 'a reply');
```

2. Use the `addCommentFrom` method to add an anonymous comment from the User (or the model that implements the `CanCommentInterface` contract)

```php
$user = User::first();
$blogPost = BlogPost::first();

$comment = $blogPost->addCommentFrom($user, 'my comment');
```

3. You can also comment with the User model (or the model that implements the `CanCommentInterface` contract) by using the `comment` method.

```php
$user = User::first();
$blogPost = BlogPost::first();

$comment = $user->comment($blogPost, 'my comment');
```

### Get comments

1. You can get all the user comments with the `comments` method

```php
$comments = User::first()->comments();
```

2. You can get all the comments associated with the commentable model with the `comments` method

```php
$comments = BlogPost::first()->comments();
```

### Sort comments

Sort the comments by popularity (average positive likes) by using the `popular` and `unpopular` scope methods.

```php
$popularComments = BlogPost::first()->comments()->popular()->get();

$unpopularComments = BlogPost::first()->comments()->unpopular()->get();
```

### Update comments

Since the `Comment` object is just a regular Eloquent Model you can use any of the different ways to update the models.

```php
$comment->update(['comment' => 'updated comment']);
```

## Approving comments

By default, all the new comments are unapproved (`approved_at=null`), meaning that you need to approve them manually based on your specific needs (you can add an event listener based on the the events listed below to do that). If you don't need to handle approved and unapproved comments, you can simply ignore the filter when querying the comments.

```php
$comment->approve();
$comment->unapprove();
```

You can filter the approved or not approved comments with the `approved` and `notApproved` methods.

```php
$model->comments()->approved()->get();
$model->comments()->notApproved()->get();
```
## Events

The `Comment` model fires the following events that you can listen:

- CommentCreated
- CommentCreating
- CommentUpdated
- CommentUpdating
- CommentDeleted
- CommentDeleting
- CommentSaved
- CommentSaving

## Development

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
