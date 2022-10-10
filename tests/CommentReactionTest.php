<?php

declare(strict_types=1);

use Alfonsobries\LaravelCommentable\Enums\CommentReactionTypeEnum;
use Alfonsobries\LaravelCommentable\Models\Comment;
use Alfonsobries\LaravelCommentable\Models\CommentReaction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\Fixtures\Models\Agent;
use Tests\Fixtures\Models\Commentable;

beforeEach(function () {
    $this->comment = Comment::forceCreate([
        'commentable_id' => 1,
        'commentable_type' => Commentable::class,
        'agent_id' => 1,
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

test('the reaction has a comment model', function () {
    expect($this->commentReaction->comment())->toBeInstanceOf(BelongsTo::class);
    expect($this->commentReaction->comment)->toBeInstanceOf(Comment::class);
});

it('filters likes reactions', function () {
    CommentReaction::forceCreate([
        'comment_id' => $this->comment->id,
        'agent_id' => Agent::create()->id,
        'type' => CommentReactionTypeEnum::Like,
    ]);

    CommentReaction::forceCreate([
        'comment_id' => $this->comment->id,
        'agent_id' =>  Agent::create()->id,
        'type' => CommentReactionTypeEnum::Dislike,
    ]);

    // The one created in the beforeEach method + the one created here
    expect(CommentReaction::likes()->count())->toBe(2);
});

it('filters dislikes reactions', function () {
    CommentReaction::forceCreate([
        'comment_id' => $this->comment->id,
        'agent_id' => Agent::create()->id,
        'type' => CommentReactionTypeEnum::Like,
    ]);

    CommentReaction::forceCreate([
        'comment_id' => $this->comment->id,
        'agent_id' =>  Agent::create()->id,
        'type' => CommentReactionTypeEnum::Dislike,
    ]);

    // The one created in the beforeEach method + the one created here is like
    expect(CommentReaction::dislikes()->count())->toBe(1);
});
