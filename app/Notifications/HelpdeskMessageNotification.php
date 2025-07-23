<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HelpdeskMessageNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    protected $message;
    protected $user;

    public $full_name;
    public $id;

    public function __construct()
    {
        $this->message = $message;
        $this->user = $user;
        $this->full_name = $full_name;
        $this->id        = $id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type'       => "helpdesk",
            'title'      => "HelpDesk Message",
            'description'=> "New message from". $this->full_name,
            'route'      => route('admin.helpdesk.response',['id'=> $this->id])
         ];
    }
}
