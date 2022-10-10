<?php

declare(strict_types=1);

use Alfonsobries\LaravelCommentable\Enums\CommentReactionTypeEnum;
use Alfonsobries\LaravelCommentable\Models\Comment;
use Tests\Fixtures\Models\Agent;
use Tests\Fixtures\Models\Commentable;

test('a comment has an agent', function () {
    $agent = Agent::create();

    $commentable = Commentable::create();
    $comment     = $agent->comment($commentable, 'This is a comment');

    expect($comment->agent_id)->toBe($agent->id);

    $commentAgent = $comment->agent;

    expect($commentAgent)->toBeInstanceOf(Agent::class);

    expect($commentAgent->id)->toBe($agent->id);
});

test('a comment has a commentable model', function () {
    $agent = Agent::create();

    $commentable = Commentable::create();
    $comment     = $agent->comment($commentable, 'This is a comment');

    $commentCommentable = $comment->commentable;

    expect($commentCommentable)->toBeInstanceOf(Commentable::class);

    expect($commentCommentable->id)->toEqual($commentable->id);
});

test('the comment can be approved/unaproved', function () {
    $agent       = Agent::create();
    $commentable = Commentable::create();
    $comment     = $agent->comment($commentable, 'This is a comment');

    expect($comment->isApproved())->toBeFalse();

    $comment->approve();

    expect($comment->isApproved())->toBeTrue();

    $comment->unapprove();

    expect($comment->isApproved())->toBeFalse();
});

it('filters approved and not approved comments', function () {
    $agent       = Agent::create();
    $commentable = Commentable::create();
    $comment1    = $agent->comment($commentable, 'This is a comment');
    $comment2    = $agent->comment($commentable, 'This is a comment');
    $comment3    = $agent->comment($commentable, 'This is a comment');

    $comment1->approve();
    $comment2->approve();

    $approvedComments    = $commentable->comments()->approved()->get();
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
    $agent       = Agent::create();
    $commentable = Commentable::create();

    $comment = $commentable->addComment('some comment');

    $reply = $comment->replyFrom($agent, 'some reply');

    expect($reply)->toBeInstanceOf(Comment::class);

    expect($reply->commentable_type)->toBe($comment->getMorphClass());
    expect($reply->agent_id)->toBe($agent->id);
});

it('uses the table name from the config file', function () {
    $agent       = Agent::create();
    $commentable = Commentable::create();

    $comment = $agent->comment($commentable, 'This is a comment');

    expect($comment->getTable())->toBe('comments');

    config(['laravel-commentable.tables.comments' => 'custom_comments']);

    expect($comment->getTable())->toBe('custom_comments');
});

test('an agent can react to a comment', function () {
    $agent       = Agent::create();
    $commentable = Commentable::create();
    $comment     = $agent->comment($commentable, 'This is a comment');

    $comment->react(CommentReactionTypeEnum::Like, $agent);

    expect($comment->reactions()->count())->toBe(1);

    $comment->react(CommentReactionTypeEnum::Dislike, $agent);

    expect($comment->reactions()->count())->toBe(2);
});

test('an agent can like a comment', function () {
    $agent       = Agent::create();
    $commentable = Commentable::create();
    $comment     = $agent->comment($commentable, 'This is a comment');

    $comment->like($agent);

    expect($comment->reactions()->count())->toBe(1);

    expect($comment->likes()->count())->toBe(1);

    expect($comment->dislikes()->count())->toBe(0);
});

test('an agent can dislike a comment', function () {
    $agent       = Agent::create();
    $commentable = Commentable::create();
    $comment     = $agent->comment($commentable, 'This is a comment');

    $comment->dislike($agent);

    expect($comment->reactions()->count())->toBe(1);

    expect($comment->dislikes()->count())->toBe(1);

    expect($comment->likes()->count())->toBe(0);
});

test('accepts reactions without agent', function () {
    $agent       = Agent::create();
    $commentable = Commentable::create();
    $comment     = $agent->comment($commentable, 'This is a comment');

    $comment->react(CommentReactionTypeEnum::Dislike);

    $comment->like();

    $comment->dislike();

    expect($comment->reactions()->count())->toBe(3);

    expect($comment->likes()->count())->toBe(1);

    expect($comment->dislikes()->count())->toBe(2);
});

test('it sorts the comments by creation date', function () {
    $agent        = Agent::create();
    $agent2       = Agent::create();
    $agent3       = Agent::create();
    $agent4       = Agent::create();
    $commentable  = Commentable::create();

    $this->travelTo(now()->subDays(2));
    $comment3     = $agent->comment($commentable, 'This is a comment');

    $this->travelBack();
    $this->travelTo(now()->subDays(1));
    $comment1     = $agent2->comment($commentable, 'This is a comment 2');

    $this->travelBack();
    $this->travelTo(now()->subWeek(1));
    $comment4     = $agent3->comment($commentable, 'This is a comment 3');

    $this->travelBack();
    $this->travelTo(now()->subDay(1)->subHour(1));
    $comment2     = $agent4->comment($commentable, 'This is a comment 4');

    $comments = Comment::latest()->get();

    expect($comments->get(0)->id)->toBe($comment1->id);
    expect($comments->get(1)->id)->toBe($comment2->id);
    expect($comments->get(2)->id)->toBe($comment3->id);
    expect($comments->get(3)->id)->toBe($comment4->id);
});

test('it sorts the comments by popularity (average likes)', function () {
    $agent        = Agent::create();
    $agent2       = Agent::create();
    $agent3       = Agent::create();
    $agent4       = Agent::create();
    $agent5       = Agent::create();
    $commentable  = Commentable::create();

    $comment4     = $agent->comment($commentable, 'This is a comment');
    // Total likes: 2-2 =0
    $comment4->like($agent);
    $comment4->dislike($agent2);
    $comment4->like($agent3);
    $comment4->dislike($agent4);

    $comment1     = $agent2->comment($commentable, 'This is a comment 2');
    // Total likes: 4
    $comment1->like($agent);
    $comment1->like($agent2);
    $comment1->like($agent3);
    $comment1->like($agent4);

    $comment3  = $agent3->comment($commentable, 'This is a comment 3');
    // Total likes: 1
    $comment3->like($agent);

    $comment2 = $agent4->comment($commentable, 'This is a comment 4');
    // Total likes: 3 (4-1)
    $comment2->like($agent);
    $comment2->dislike($agent2);
    $comment2->like($agent3);
    $comment2->like($agent4);
    $comment2->like($agent5);

    $comments = Comment::popular()->get();

    expect($comments->get(0)->id)->toBe($comment1->id);
    expect($comments->get(1)->id)->toBe($comment2->id);
    expect($comments->get(2)->id)->toBe($comment3->id);
    expect($comments->get(3)->id)->toBe($comment4->id);

    $comments = Comment::unpopular()->get();

    expect($comments->get(0)->id)->toBe($comment4->id);
    expect($comments->get(1)->id)->toBe($comment3->id);
    expect($comments->get(2)->id)->toBe($comment2->id);
    expect($comments->get(3)->id)->toBe($comment1->id);
});
