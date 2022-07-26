<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoListRequest;
use App\Models\TodoList;
use Illuminate\Http\Response;

class TodoListController extends Controller
{
    public function index()
    {
        // $list = TodoList::whereUserId(auth()->id())->get();
        $list = auth()->user()->todo_lists;
        return response($list);
    }

    public function show(TodoList $todo_list)
    {
        return response()->json($todo_list);
    }

    public function store(TodoListRequest $request)
    {
        return auth()->user()
            ->todo_lists()
            ->create($request->validated());
    }

    public function destroy(TodoList $todo_list)
    {
        $todo_list->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    public function update(TodoListRequest $request, TodoList $todo_list)
    {
        $todo_list->update($request->all());
        return $todo_list;
    }
}
