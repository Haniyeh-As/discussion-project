<?php


namespace App\Repositories;


use App\Thread;

class ThreadRepository
{
    public function getAllAvailableThreads()
    {
        return Thread::whereFlag(1)->latest()->get();
    }

    public function getThreadBySlug($slug)
    {
        return Thread::whereSlug()->whereFlag(1)->first();
    }
}
