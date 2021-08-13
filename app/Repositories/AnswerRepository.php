<?php


namespace App\Repositories;

namespace App\Repositories;


use App\Answer;
use App\channel;
use App\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AnswerRepository
{
    public function getAllAnswers()
    {
        return Answer::query()->latest()->get();
    }

    public function store(Request $request)
    {
        Thread::find($request->thread_id)->answers()->create([
            'content' => $request->input('content'),
            'user_id' => auth()->id(),
        ]);
    }
}
