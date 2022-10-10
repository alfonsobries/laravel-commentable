<?php

declare(strict_types=1);

use Alfonsobries\LaravelCommentable\Contracts\CanComment;
use Alfonsobries\LaravelCommentable\Models\Comment;
use Tests\Fixtures\Models\Agent;
use Tests\Fixtures\Models\Commentable;

test('a commentable model have many comments', function () {
    $agent = Agent::create();
    $agent2 = Agent::create();

    $commentable = Commentable::create();

    $agent->comment($commentable, 'This is a comment');
    $agent->comment($commentable, 'This is a comment');
    $agent2->comment($commentable, 'This is a comment');

    expect($commentable->comments()->count())->toBe(3);
});

test('a commentable can receive a comment', function () {
    $commentable = Commentable::create();

    $comment = $commentable->addComment('This is a comment');

    expect($comment)->toBeInstanceOf(Comment::class);
});

test('a commentable can receive a comment from an agent', function () {
    $agent = Agent::create();

    $commentable = Commentable::create();

    $comment = $commentable->addCommentFrom($agent, 'This is a comment');

    expect($comment)->toBeInstanceOf(Comment::class);

    expect($comment->agent_id)->toBe($agent->id);

    expect($comment->agent)->toBeInstanceOf(CanComment::class);
});


test('stores the request ip address', function () {
    $commentable = Commentable::create();

    $this->app['request']->server->set('REMOTE_ADDR', '123.456.789.000');

    $comment = $commentable->addComment('This is a comment');

    expect($comment->ip_address)->toBe('123.456.789.000');
});

test('stores the request user agent', function () {
    $commentable = Commentable::create();

    $this->app['request']->headers->set('User-Agent', 'Mozilla/5.0');

    $comment = $commentable->addComment('This is a comment');

    expect($comment->user_agent)->toBe('Mozilla/5.0');
});
