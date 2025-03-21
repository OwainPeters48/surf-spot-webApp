<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SurfSpotFavouritedNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $surfSpot;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $surfSpot)
    {
        $this->user = $user;
        $this->surfSpot = $surfSpot;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database']; // Store in the database
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => "{$this->user->name} favourited your surf spot '{$this->surfSpot->name}'!",
            'surf_spot_id' => $this->surfSpot->id,
            'user_id' => $this->user->id,
        ];
    }
}
