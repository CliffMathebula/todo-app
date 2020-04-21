<?php

namespace App\Http\Requests;

use App\Task;
use App\Rules\Options;
use App\Rules\BelongsToUser;
use Illuminate\Foundation\Http\FormRequest;

class MoveTask extends FormRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'id' => [ 'required', 'integer', new BelongsToUser ],
      'priority' => 'required|integer',
      'status' => ['required', new Options(['Todo', 'In Progress', 'Done']) ]
    ];
  }

  /**
   * Update task position
   *
   * @return bool
   */
  public function moveTask() : bool
  {
    return Task::where('id', $this->id)
                ->update([
                  'priority' => $this->priority,
                  'status' => $this->status
                ]);
  }
}
