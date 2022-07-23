<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TodoListController extends Controller
{
    public function index()
    {
        $list = TodoList::all();
        return response($list);
    }

    public function show(TodoList $todolist)
    {
        return response()->json($todolist);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => ['required']]);
        $list = TodoList::create($request->all());
        return response($list, Response::HTTP_CREATED);
    }

    public function destroy(TodoList $todolist)
    {
        $todolist->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    public function update(Request $request, TodoList $todolist)
    {
        $request->validate(['name' => ['required']]);
        $todolist->update($request->all());
        return $todolist;
    }
}
