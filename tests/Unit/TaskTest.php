<?php

namespace Tests\Unit;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_a_task_belongs_a_todo_list()
    {
       $list = $this->createTodoList();
       $task = $this->createTask(['todo_list_id' =>  $list->id]);
       
       $this->assertInstanceOf(TodoList::class, $task->todo_list);
    }
}
