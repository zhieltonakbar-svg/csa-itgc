<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ControlWorkflowNotification extends Notification
{
    use Queueable;

    public $message;
    public $url;
    public $control_id;

    /**
     * Create a new notification instance.
     */
    public function __construct($message, $url, $control_id)
    {
        $this->message = $message;
        $this->url = $url;
        $this->control_id = $control_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];

        $defaultBroadcaster = config('broadcasting.default', 'null');

        if ($defaultBroadcaster === 'reverb') {
            $host = config('broadcasting.connections.reverb.options.host', 'localhost');
            $port = config('broadcasting.connections.reverb.options.port', 8080);
            
            // Check if Reverb server is running with a 100ms connection timeout
            $connection = @fsockopen($host, $port, $errno, $errstr, 0.1);
            if (is_resource($connection)) {
                fclose($connection);
                $channels[] = 'broadcast';
            }
        } elseif ($defaultBroadcaster === 'pusher') {
            $host = config('broadcasting.connections.pusher.options.host');
            $port = config('broadcasting.connections.pusher.options.port', 443);
            
            if ($host) {
                // Check if Pusher host is reachable
                $connection = @fsockopen($host, $port, $errno, $errstr, 0.1);
                if (is_resource($connection)) {
                    fclose($connection);
                    $channels[] = 'broadcast';
                }
            }
        } elseif (in_array($defaultBroadcaster, ['log', 'null'])) {
            $channels[] = 'broadcast';
        }

        return $channels;
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): \Illuminate\Notifications\Messages\BroadcastMessage
    {
        return new \Illuminate\Notifications\Messages\BroadcastMessage([
            'message' => $this->message,
            'url' => $this->url,
            'control_id' => $this->control_id,
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message,
            'url' => $this->url,
            'control_id' => $this->control_id,
        ];
    }
}
