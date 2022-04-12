<?php

namespace App\Mail\Subscription;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BasicMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $body;
    public $post;
    public $subtitle;
    public $subject;

    public function __construct($body, $title, $post, $subject= 'Selevinia рекомендует к чтению')
    {
        $this->title = $title;
        $this->body = $body;
        $this->post = $post;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.sample-mail', [
            'title' => $this->title ?? $this->post['name'],
            'body' => $this->body,
            'postUrl' => env('MAIL_BUTTON_REDIRECT_URL') . '/' . $this->post['id'],
            'post' => $this->post,
        ])->subject($this->subject)
            ->from('selevinia@gmail.com');
    }
}
