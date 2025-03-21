<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class CommentAddedNotification extends Notification
{
    protected $comment;

    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        return ['database']; // Use database channel
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'A new comment was added to your surf spot: ' . $this->comment->surfSpot->name,
            'comment_id' => $this->comment->id,
            'surf_spot_id' => $this->comment->surf_spot_id,
        ];
    }
}
