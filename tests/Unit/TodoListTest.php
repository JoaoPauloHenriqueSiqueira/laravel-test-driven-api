<?php

namespace Tests\Unit;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->authUser();
    }


    public function test_a_todo_list_can_has_many_task()
    {
       $list = $this->createTodoList();
       $task = $this->createTask(['todo_list_id' =>  $list->id]);
       
       $this->assertInstanceOf(Task::class, $list->tasks->first());
    }

    public function test_if_a_todo_list_is_deleted_then_all_its_tasks_will_be_deleted()
    {
       $list = $this->createTodoList();
       $secList = $this->createTodoList();

       $task = $this->createTask(['todo_list_id' =>  $list->id]);
       $secTask = $this->createTask(['todo_list_id' =>  $secList->id]);

       $list->delete();

       $this->assertDatabaseMissing('todo_lists', ['id' => $list->id]);
       $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
       $this->assertDatabaseHas('tasks', ['id' => $secTask->id]);
    }
}
