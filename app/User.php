<?php

namespace App;

use Mail;
use Carbon\Carbon;
use App\Mail\TaskAlert;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'email', 'password',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  /**
   * Get all tasks that belong to the user
   *
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function tasks() : HasMany
  {
    return $this->hasMany(Task::class);
  }

  /**
   * Return tasks as json string
   *
   * @return string
   */
  public function tasksAsJson() : string
  {
    $tasks = $this->tasks()
                  ->whereIn('status', ['Todo', 'In Progress'])
                  ->orderBy('priority', 'asc')
                  ->orderBy('updated_at', 'desc')
                  ->select('id', 'title', 'description', 'status', 'due')
                  ->get()
                  ->toArray();

    /**
     * Convert array into a json sgtring
     */
    return json_encode($tasks);
  }

  /**
   * Alert user about incomeplete tasks
   *
   * @return void
   */
  public function alert()
  {
    $tasks = $this->tasks()->whereNotNull('due')
                  ->whereIn('status', ['Todo', 'Progress'])
                  ->whereDate('due', Carbon::today())
                  ->get();

    if (count($tasks) > 0) {
      Mail::to($this)->send(new TaskAlert($this, count($tasks) == 1 ? "The task \"**{$tasks->first()->title}**\" is due today." : 'You have ' . count($tasks) . ' tasks due today'));
    }
  }
}
