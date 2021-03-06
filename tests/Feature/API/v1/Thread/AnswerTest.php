<?php

namespace tests\Feature\API\v1\Thread;

use App\Answer;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AnswerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_get_all_answer_list()
    {
        $response = $this->get(route('answers.index'));
        $response->assertSuccessful();
    }

    /** @test */
    public function create_answer_should_be_validated()
    {
        Sanctum::actingAs(factory(User::class)->create());
        $response = $this->postJson(route('answers.store'),[]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['content','thread_id']);
    }

    /** @test */
    public function can_submit_new_answer_for_thread()
    {
       //$this->withoutExceptionHandling();
        Sanctum::actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->create();

        $response = $this->postJson(route('answers.store'),[
            'content' => 'Foo',
            'thread_id' => $thread->id
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'message' => 'answer submitted successfully'
        ]);
        $this->assertTrue($thread->answers()->where('content', 'Foo')->exists());
    }

    /** @test */
    public function user_score_will_increase_by_submit_new_answer()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user);

        $thread = factory(Thread::class)->create();

        $response = $this->postJson(route('answers.store'),[
            'content' => 'Foo',
            'thread_id' => $thread->id
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $user->refresh();
        $this->assertEquals(10, $user->score);

    }

    /** @test */
    public function update_answer_should_be_validated()
    {
        Sanctum::actingAs(factory(User::class)->create());

        $answer = factory(Answer::class)->create();

        $response = $this->putJson(route('answers.update',[$answer]),[]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['content']);
    }

    /** @test */
    public function can_update_own_answer_of_thread()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user);

        $answer = factory(Answer::class)->create([
            'content' => 'Foo',
            'user_id' => $user->id
        ]);

        $response = $this->putJson(route('answers.update',[$answer]),[
            'content' => 'Bar',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'answer updated successfully'
        ]);

        $answer->refresh();
        $this->assertEquals('Bar',$answer->content);
    }

    /** @test */
    public function can_delete_own_answer()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user);

        $answer = factory(Answer::class)->create([
            'user_id' => $user->id
        ]);

        $response = $this->delete(route('answers.destroy',[$answer]));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'Answer Deleted Successfully'
        ]);

        $this->assertFalse(Thread::find($answer->thread_id)->answers()->whereContent($answer->content)->exists());
    }
}
