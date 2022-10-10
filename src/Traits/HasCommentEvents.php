<?php

declare(strict_types=1);

namespace Alfonsobries\LaravelCommentable\Traits;

use Alfonsobries\LaravelCommentable\Events\CommentCreated;
use Alfonsobries\LaravelCommentable\Events\CommentCreating;
use Alfonsobries\LaravelCommentable\Events\CommentDeleted;
use Alfonsobries\LaravelCommentable\Events\CommentDeleting;
use Alfonsobries\LaravelCommentable\Events\CommentUpdated;
use Alfonsobries\LaravelCommentable\Events\CommentUpdating;
use Illuminate\Database\Eloquent\Model;

trait HasCommentEvents
{
    public static function bootHasCommentEvents(): void
    {
        static::creating(function (Model $model) {
            event(new CommentCreating($model));
        });

        static::created(function (Model $model) {
            event(new CommentCreated($model));
        });

        static::updating(function (Model $model) {
            event(new CommentUpdating($model));
        });

        static::updated(function (Model $model) {
            event(new CommentUpdated($model));
        });

        static::deleting(function (Model $model) {
            event(new CommentDeleting($model));
        });

        static::deleted(function (Model $model) {
            event(new CommentDeleted($model));
        });
    }
}
