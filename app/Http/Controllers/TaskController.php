<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Requests\AddTask;
use App\Http\Requests\EditTask;
use App\Http\Requests\MoveTask;
use App\Http\Requests\DeleteTask;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
  /**
   * Return tasks view
   *
   * @return View
   */
  public function home() : View
  {
    return view('tasks', ['tasks' => Auth::user()->tasksAsJson()]);
  }

  /**
   * Add a new task
   *
   * @param AddTask $request
   * @return
   */
  public function add(AddTask $request)
  {
    return response()->json([
      'status' => 'success',
      'task' => $request->addTask()
    ]);
  }

  /**
   * Edit existing task
   *
   * @param EditTask $request
   * @return
   */
  public function edit(EditTask $request)
  {
    $request->updateTask();

    return response()->json([
      'status' => 'success',
      'dueSoon' => Task::where('id', $request->id)->first()->dueSoon
    ]);
  }

  /**
   * Undocumented function
   *
   * @param Request $request
   * @return
   */
  public function move(MoveTask $request)
  {
    $request->moveTask();

    return response()->json([
      'status' => 'success'
    ]);
  }

  /**
   * Delete task from the db
   *
   * @param DeleteTask $request
   * @return
   */
  public function delete(DeleteTask $request)
  {
    $request->deleteTask();

    return response()->json([
      'status' => 'success'
    ]);
  }
}
