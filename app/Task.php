<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'priority', 'user_id', 'status', 'title', 'description', 'due'
  ];

  /**
   * The attributes that are appended.
   *
   * @var array
   */
  protected $appends = [
    'dueSoon'
  ];

  /**
   * Check if task is due soon
   *
   * @return bool
   */
  public function getDueSoonAttribute() : bool
  {
    return $this->due && (Carbon::parse($this->due)->format('Y-m-d') == Carbon::today()->format('Y-m-d')) ? true : false;
  }
}
