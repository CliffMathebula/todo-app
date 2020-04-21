<?php

namespace App\Http\Requests;

use App\Task;
use App\Rules\BelongsToUser;
use Illuminate\Foundation\Http\FormRequest;

class DeleteTask extends FormRequest
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
    ];
  }

  /**
   * Delete task from the db
   *
   * @return bool
   */
  public function deleteTask() : bool
  {
    return Task::where('id', $this->id)->delete();
  }
}
