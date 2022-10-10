<?php

declare(strict_types=1);

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
