<?php

declare(strict_types=1);

namespace Alfonsobries\LaravelCommentable\Traits;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

trait UsesUuid
{
    public static function bootUsesUuid(): void
    {
        static::creating(static function (Model $model) {
            /** @var mixed $model */
            $uuid        = $model->uuid;
            $model->uuid = $uuid ?? (string) Uuid::uuid4();
        });
    }
}
