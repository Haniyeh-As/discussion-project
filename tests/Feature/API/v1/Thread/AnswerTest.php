<?php

namespace tests\Feature\API\v1\Thread;

use App\channel;
use App\Thread;
use App\User;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AnswerTest extends TestCase
{
    /** @test */
    public function can_get_all_answer_list()
    {
        $response = $this->get(route('answers.index'));
        $response->assertSuccessful();
    }
}
