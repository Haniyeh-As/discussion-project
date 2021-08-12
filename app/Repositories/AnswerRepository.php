<?php


namespace App\Repositories;

namespace App\Repositories;


use App\Answer;
use App\channel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AnswerRepository
{
    public function getAllAnswers()
    {
        return Answer::query()->latest()->get();
    }
}
