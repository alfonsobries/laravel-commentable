<?php

declare(strict_types=1);

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
