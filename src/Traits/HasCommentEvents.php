<?php

declare(strict_types=1);

namespace Alfonsobries\LaravelCommentable\Traits;

use Alfonsobries\LaravelCommentable\Events\CommentCreated;
use Alfonsobries\LaravelCommentable\Events\CommentCreating;
use Alfonsobries\LaravelCommentable\Events\CommentDeleted;
use Alfonsobries\LaravelCommentable\Events\CommentDeleting;
use Alfonsobries\LaravelCommentable\Events\CommentSaved;
use Alfonsobries\LaravelCommentable\Events\CommentSaving;
use Alfonsobries\LaravelCommentable\Events\CommentUpdated;
use Alfonsobries\LaravelCommentable\Events\CommentUpdating;
use Illuminate\Database\Eloquent\Model;

trait HasCommentEvents
{
    public static function bootHasCommentEvents(): void
    {
        static::saving(static function (Model $model) {
            event(new CommentSaving($model));
        });

        static::saved(static function (Model $model) {
            event(new CommentSaved($model));
        });

        static::creating(static function (Model $model) {
            event(new CommentCreating($model));
        });

        static::created(static function (Model $model) {
            event(new CommentCreated($model));
        });

        static::updating(static function (Model $model) {
            event(new CommentUpdating($model));
        });

        static::updated(static function (Model $model) {
            event(new CommentUpdated($model));
        });

        static::deleting(static function (Model $model) {
            event(new CommentDeleting($model));
        });

        static::deleted(static function (Model $model) {
            event(new CommentDeleted($model));
        });
    }
}
