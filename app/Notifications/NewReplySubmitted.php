<?php

namespace App\Notifications;

use App\Thread;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewReplySubmitted extends Notification
{
    use Queueable;

    private Thread $thread;

    public function __construct(Thread $thread)
    {
        $this->thread = $thread;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'thread_title' => $this->thread->title,
            'url' => route('threads.show',[$this->thread]),
            'time' => now()->format('Y-m-d H:i:s')
        ];
    }
}
