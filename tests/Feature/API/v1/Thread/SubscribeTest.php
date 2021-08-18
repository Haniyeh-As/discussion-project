<?php

namespace tests\Feature\API\v1\Thread;

use App\channel;
use App\Thread;
use App\User;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
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
}
