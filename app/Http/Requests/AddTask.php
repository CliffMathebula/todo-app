<?php

namespace App\Http\Requests;

use App\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class AddTask extends FormRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'title' => 'required|min:20|max:100',
      'description' => 'required|min:50|max:140'
    ];
  }

  /**
   * Create a new task
   *
   * @return Task
   */
  public function addTask() : Task
  {
    return Task::create([
      'user_id' => Auth::user()->id,
      'title' => $this->input('title'),
      'description' => $this->input('description')
    ]);
  }
}
