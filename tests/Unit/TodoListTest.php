<?php

namespace Tests\Unit;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_a_todo_list_can_has_many_task()
    {
       $list = $this->createTodoList();
       $task = $this->createTask(['todo_list_id' =>  $list->id]);
       
       $this->assertInstanceOf(Task::class, $list->tasks->first());

    }
}
