<?php

namespace App\Http\Requests;

use App\Task;
use Carbon\Carbon;
use App\Rules\BelongsToUser;
use Illuminate\Foundation\Http\FormRequest;

class EditTask extends FormRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'id' => ['required', 'integer', new BelongsToUser],
      'title' => 'required|min:20|max:100',
      'description' => 'required|min:50|max:140',
      'due' => 'date|nullable'
    ];
  }

  /**
   * Update task
   *
   * @return bool
   */
  public function updateTask() : bool
  {
    return Task::where('id', $this->id)->update([
      'title' => $this->title,
      'description' => $this->description,
      'due' => $this->due ? Carbon::createFromFormat('Y-m-d', strtok($this->due, 'T'))->addDay() : null
    ]);
  }
}
