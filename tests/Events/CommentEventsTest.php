<?php

use Alfonsobries\LaravelCommentable\Events\CommentCreated;
use Alfonsobries\LaravelCommentable\Events\CommentCreating;
use Alfonsobries\LaravelCommentable\Events\CommentDeleted;
use Alfonsobries\LaravelCommentable\Events\CommentDeleting;
use Alfonsobries\LaravelCommentable\Events\CommentUpdated;
use Alfonsobries\LaravelCommentable\Events\CommentUpdating;
use Illuminate\Support\Facades\Event;
use Tests\Fixtures\Models\Agent;
use Tests\Fixtures\Models\Commentable;

it('fires the comment created events', function () {
    Event::fake([CommentCreated::class, CommentCreating::class]);

    $agent = Agent::create();

    $commentable = Commentable::create();

    Event::assertNotDispatched(CommentCreating::class);
    Event::assertNotDispatched(CommentCreated::class);

    $comment = $agent->comment($commentable, 'This is a comment');

    Event::assertDispatched(CommentCreated::class, fn ($event) => $event->comment->is($comment));
    Event::assertDispatched(CommentCreating::class, fn ($event) => $event->comment->is($comment));
});

it('fires the comment deleted events', function () {
    Event::fake([CommentDeleted::class, CommentDeleting::class]);

    $agent = Agent::create();

    $commentable = Commentable::create();

    $comment = $agent->comment($commentable, 'This is a comment');

    Event::assertNotDispatched(CommentDeleted::class);
    Event::assertNotDispatched(CommentDeleting::class);

    $comment->delete();

    Event::assertDispatched(CommentDeleted::class, fn ($event) => $event->comment->is($comment));
    Event::assertDispatched(CommentDeleting::class, fn ($event) => $event->comment->is($comment));
});

it('fires the comment updating events', function () {
    Event::fake([CommentUpdated::class, CommentUpdating::class]);

    $agent = Agent::create();

    $commentable = Commentable::create();

    $comment = $agent->comment($commentable, 'This is a comment');

    Event::assertNotDispatched(CommentUpdated::class);
    Event::assertNotDispatched(CommentUpdating::class);

    $comment->update(['comment' => 'This is an updated comment']);

    Event::assertDispatched(CommentUpdated::class, fn ($event) => $event->comment->is($comment));
    Event::assertDispatched(CommentUpdating::class, fn ($event) => $event->comment->is($comment));
});
