<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class TaskController extends Controller
{
    public function index(TodoList $todo_list)
    {
        return response(Task::where(['todo_list_id' => $todo_list->id])->get());
    }

    public function store(TaskRequest $request, TodoList $todo_list)
    {
        $request['todo_list_id'] = $todo_list->id;
        return Task::create($request->all());
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->all());
        return response($task);
    }
}
