<?php

declare(strict_types=1);

use Alfonsobries\LaravelCommentable\Models\Comment;
use Tests\Fixtures\Models\Agent;
use Tests\Fixtures\Models\Commentable;

test('a comment has an agent', function () {
    $agent = Agent::create();

    $commentable = Commentable::create();
    $comment = $agent->comment($commentable, 'This is a comment');

    expect($comment->agent_id)->toBe($agent->id);

    $commentAgent = $comment->agent;

    expect($commentAgent)->toBeInstanceOf(Agent::class);

    expect($commentAgent->id)->toBe($agent->id);
});

test('a comment has a commentable model', function () {
    $agent = Agent::create();

    $commentable = Commentable::create();
    $comment = $agent->comment($commentable, 'This is a comment');

    $commentCommentable = $comment->commentable;

    expect($commentCommentable)->toBeInstanceOf(Commentable::class);

    expect($commentCommentable->id)->toEqual($commentable->id);
});

test('the comment can be approved/unaproved', function () {
    $agent = Agent::create();
    $commentable = Commentable::create();
    $comment = $agent->comment($commentable, 'This is a comment');

    expect($comment->isApproved())->toBeFalse();

    $comment->approve();

    expect($comment->isApproved())->toBeTrue();

    $comment->unapprove();

    expect($comment->isApproved())->toBeFalse();
});

it ('filters approved and not approved comments', function () {
    $agent = Agent::create();
    $commentable = Commentable::create();
    $comment1 = $agent->comment($commentable, 'This is a comment');
    $comment2 = $agent->comment($commentable, 'This is a comment');
    $comment3 = $agent->comment($commentable, 'This is a comment');

    $comment1->approve();
    $comment2->approve();


    $approvedComments = $commentable->comments()->approved()->get();
    $notApprovedComments = $commentable->comments()->notApproved()->get();

    expect($approvedComments->count())->toBe(2);
    expect($notApprovedComments->count())->toBe(1);
});

test('a comment can receive another comment', function () {
    $commentable = Commentable::create();

    $comment = $commentable->addComment('some comment');

    $reply = $comment->addComment('some reply');


    expect($reply)->toBeInstanceOf(Comment::class);

    expect($reply->commentable_type)->toBe($comment->getMorphClass());
    expect($reply->commentable_id)->toBe($comment->getKey());
});

test('a comment can receive another comment with the reply method', function () {
    $commentable = Commentable::create();

    $comment = $commentable->addComment('some comment');

    $reply = $comment->reply('some reply');

    expect($reply)->toBeInstanceOf(Comment::class);

    expect($reply->commentable_type)->toBe($comment->getMorphClass());
    expect($reply->commentable_id)->toBe($comment->getKey());
});

test('a comment can receive another comment with the replyFrom method', function () {
    $agent = Agent::create();
    $commentable = Commentable::create();

    $comment = $commentable->addComment('some comment');

    $reply = $comment->replyFrom($agent, 'some reply');

    expect($reply)->toBeInstanceOf(Comment::class);

    expect($reply->commentable_type)->toBe($comment->getMorphClass());
    expect($reply->agent_id)->toBe($agent->id);
});

it('uses the table name from the config file', function () {
    $agent = Agent::create();
    $commentable = Commentable::create();

    $comment = $agent->comment($commentable, 'This is a comment');

    expect($comment->getTable())->toBe('comments');

    config(['laravel-commentable.tables.comments' => 'custom_comments']);

    expect($comment->getTable())->toBe('custom_comments');
});
