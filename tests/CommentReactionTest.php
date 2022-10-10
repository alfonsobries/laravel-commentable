<?php

declare(strict_types=1);

use Alfonsobries\LaravelCommentable\Enums\CommentReactionTypeEnum;
use Alfonsobries\LaravelCommentable\Models\Comment;
use Alfonsobries\LaravelCommentable\Models\CommentReaction;
use Tests\Fixtures\Models\Agent;
use Tests\Fixtures\Models\Commentable;

beforeEach(function () {
    $this->comment = Comment::forceCreate([
        'commentable_id' => 1,
        'commentable_type' => Commentable::class,
        'agent_id' => 1,
        'agent_type' => 'App\Models\Agent',
        'comment' => 'This is a comment',
    ]);

    $this->commentReaction  = CommentReaction::forceCreate([
        'comment_id' => $this->comment->id,
        'agent_id' => 1,
        'type' => CommentReactionTypeEnum::Like,
    ]);

    $this->agent = Agent::create();
});

it('uses the table name from the config file', function () {
    expect($this->commentReaction->getTable())->toBe('comment_reaction');

    config(['laravel-commentable.tables.comment_reaction' => 'custom_comment_reaction']);

    expect($this->commentReaction->getTable())->toBe('custom_comment_reaction');
});
