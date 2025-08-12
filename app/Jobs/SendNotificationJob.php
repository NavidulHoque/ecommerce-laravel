<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\Notification;

class SendNotificationJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public $userId;

    public function __construct($userId) {
        $this->userId = $userId;
    }

    public function handle() {
        $user = User::find($this->userId);
        $user->notify(new Notification());
    }
}
