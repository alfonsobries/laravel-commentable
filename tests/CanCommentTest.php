<?php

declare(strict_types=1);

use Alfonsobries\LaravelCommentable\Models\Comment;
use Tests\Fixtures\Models\Agent;
use Tests\Fixtures\Models\Commentable;

test('an agent can comment into a commentable model', function () {
    $agent = Agent::create();

    $commentable = Commentable::create();

    $comment = $agent->comment($commentable, 'This is a comment');

    expect($comment)->toBeInstanceOf(Comment::class);
});

test('an agent have many comments', function () {
    $agent = Agent::create();

    $commentable = Commentable::create();

    $agent->comment($commentable, 'This is a comment');
    $agent->comment($commentable, 'This is another comment');

    expect($agent->comments()->count())->toBe(2);
});

test('stores the request ip address', function () {
    $agent = Agent::create();

    $commentable = Commentable::create();

    $this->app['request']->server->set('REMOTE_ADDR', '123.456.789.000');

    $comment = $agent->comment($commentable, 'This is a comment');

    expect($comment->ip_address)->toBe('123.456.789.000');
});

test('stores the request user agent', function () {
    $agent = Agent::create();

    $commentable = Commentable::create();

    $this->app['request']->headers->set('User-Agent', 'Mozilla/5.0');

    $comment = $agent->comment($commentable, 'This is a comment');

    expect($comment->user_agent)->toBe('Mozilla/5.0');
});
