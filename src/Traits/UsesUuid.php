<?php

declare(strict_types=1);

namespace Alfonsobries\LaravelCommentable\Traits;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;

trait UsesUuid
{
    public static function bootUsesUuid(): void
    {
        static::creating(function (Model $model) {
            $model->uuid = $model->uuid ?? (string) Uuid::uuid4();
        });
    }
}
