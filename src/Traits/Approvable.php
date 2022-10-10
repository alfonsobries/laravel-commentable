<?php

declare(strict_types=1);

namespace Alfonsobries\LaravelCommentable\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait Approvable
{
    public function isApproved(): bool
    {
        return $this->approved_at !== null && $this->approved_at->isPast();
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->whereNotNull('approved_at')->where('approved_at', '<=', Carbon::now());
    }

    public function scopeNotApproved(Builder $query): Builder
    {
        return $query->whereNull('approved_at')->orWhere('approved_at', '>', Carbon::now());
    }

    public function approve(): self
    {
        $this->approved_at = Carbon::now();
        $this->save();

        return $this;
    }

    public function unapprove(): self
    {
        $this->approved_at = null;
        $this->save();

        return $this;
    }
}
