<?php

namespace tests\Feature\API\v1\Thread;

use App\Notifications\NewReplySubmitted;
use App\Thread;
use App\User;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubscribeTest extends TestCase
{
    /** @test */
    public function user_can_subscribe_to_a_thread()
    {
        Sanctum::actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->create();

        $response = $this->post(route('subscribe',[$thread]));
        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'User Subscribed Successfully'
        ]);
    }

    /** @test */
    public function user_can_unsubscribe_from_a_thread()
    {
        Sanctum::actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->create();

        $response = $this->post(route('unSubscribe',[$thread]));
        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'User Unsubscribed Successfully'
        ]);
    }

    /** @test */
    public function notification_will_send_to_subscribers_of_a_thread()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user);

        Notification::fake();

        $thread = factory(Thread::class)->create();

        $subscribe_response = $this->post(route('subscribe',[$thread]));
        $subscribe_response->assertSuccessful();
        $subscribe_response->assertJson([
            'message' => 'User Subscribed Successfully'
        ]);

        $answer_response = $this->postJson(route('answers.store',[
            'content' => 'Foo',
            'thread_id' => $thread->id
        ]));

        $answer_response->assertSuccessful();
        $answer_response->assertJson([
            'message' => 'answer submitted successfully'
        ]);

        Notification::assertSentTo($user,NewReplySubmitted::class);
    }
}
