<?php

namespace tests\Feature\API\v1\Thread;

use App\channel;
use App\Thread;
use App\User;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    /** @test */
    public function all_threads_list_should_be_accessible()
    {
        $response = $this->get(route('threads.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test  */
    public function thread_should_be_accessible_by_slug()
    {
        $thread = factory(Thread::class)->create();

        $response = $this->get(route('threads.show',[$thread->slug]));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test  */
    public function thread_should_be_validated()
    {
        $response = $this->postJson(route('threads.store'),[]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test  */
    public function can_create_thread()
    {
        //$this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->create());

        $response = $this->postJson(route('threads.store'),[
            'title' => 'Foo',
            'content' => 'Bar',
            'channel_id' => factory(channel::class)->create()->id,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }
}
