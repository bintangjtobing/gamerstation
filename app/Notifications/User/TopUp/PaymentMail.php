<?php

namespace App\Notifications\User\TopUp;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentMail extends Notification
{
    use Queueable;

    protected $user;
    protected $output;
    protected $trx_id;
    public function __construct($user, $output, $trx_id,)
    {
        $this->user = $user;
        $this->output = $output;
        $this->trx_id = $trx_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $user = $this->user;
        $output = $this->output;
        $trx_id = $this->trx_id;
        $date = Carbon::now();
        $dateTime = $date->format('Y-m-d h:i:s A');
        return (new MailMessage)
            ->greeting("Hello " . $user->fullname . " !")
            ->subject("Payment Successfully")
            ->line("Your topup successful via " . $output['currency']['name'] . " , details of topup:")
            ->line("Amount: " . getAmount($output['amount']->requested_amount, 2) . ' ' . $output['amount']->default_currency)
            ->line("Fees & Charges: " . $output['amount']->total_charge . ' ' . $output['amount']->sender_cur_code)
            ->line("Total Amount: " . getAmount($output['amount']->total_amount, 2) . ' ' . $output['amount']->sender_cur_code)
            ->line("Transaction Id: " . $trx_id)
            ->line("Status: Success")
            ->line("Date And Time: " . $dateTime)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
