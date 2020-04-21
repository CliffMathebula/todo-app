<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskAlert extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * User object
   *
   * @var User
   */
  protected $user;

  /**
   * Message
   *
   * @var srtring
   */
  protected $message;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct(User $user, string $message)
  {
    $this->user    = $user;

    $this->message = $message;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    return $this->markdown('emails.tasks.alert')
                ->with([
                  'name' => $this->user->name,
                  'message' => $this->message
                ]);
  }
}
