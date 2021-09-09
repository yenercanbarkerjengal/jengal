<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

/**
 * Class SystemReportNotification
 * @package App\Notifications
 */
class SystemReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Holds exception log's details
     *
     * @var array
     */
    private $data;

    /**
     * Create a new notification instance.
     *
     * SystemReportNotification constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $this->data = str_replace("\n", "<br>", $this->data ?? '');

        return (new MailMessage)
            ->subject(__('Custom Exception Notification'))
            ->view('notifications.email.system_report', ['data' => $this->data ?? '']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => "System Report",
            'description' => "System Status"
        ];
    }

    /**
     * Sends notifications to slack channel
     *
     * @param $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $data = $this->data ? (string)$this->data : '';

        return (new SlackMessage)
            ->from(__('System Reporter'), ':desktop_computer:')
            ->content(config('logging.channels.slack.workspace'))
            ->content($data);
    }
}
