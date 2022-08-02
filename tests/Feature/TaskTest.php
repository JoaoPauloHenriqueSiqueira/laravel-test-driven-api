<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->authUser();
    }

    public function test_fetch_all_tasks_of_a_todo_list()
    {
        $todoList = $this->createTodoList();
        $task = $this->createTask(['todo_list_id' => $todoList->id]);

        $response = $this->getJson(route('todo-list.task.index', $todoList->id))->assertOk()->json();

        $this->assertEquals(1, count($response));
        $this->assertEquals($task->title, $response[0]['title']);
        $this->assertEquals($todoList->id, $response[0]['todo_list_id']);

    }

    public function test_store_a_task_for_a_todo_list_without_a_label()
    {
        $task = Task::factory()->make();
        $todoList = $this->createTodoList();

        $this->postJson(route('todo-list.task.store', $todoList->id), ['title' => $task->title])->assertCreated();
        $this->assertDatabaseHas(
            'tasks',
            ['title' => $task->title, 'todo_list_id' => $todoList->id]
        );
    }

    public function test_store_a_task_for_a_todo_list()
    {
        $task = Task::factory()->make();
        $todoList = $this->createTodoList();
        $label = $this->createLabel();

        $this->postJson(route('todo-list.task.store', $todoList->id), ['title' => $task->title, 'label_id' => $label->id])->assertCreated();
        $this->assertDatabaseHas(
            'tasks',
            [
                'title' => $task->title,
                'todo_list_id' => $todoList->id,
                'label_id' => $label->id
            ]
        );
    }

    public function test_delete_a_task_from_database()
    {
        $task = $this->createTask();
        $todoList = $this->createTodoList();

        $this->deleteJson(route('task.destroy', $task->id))->assertNoContent();
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_update_a_task_from_a_todo_list()
    {
        $task = $this->createTask();
        $this->patchJson(route('task.update', $task->id), ['title' => 'Update title'])->assertOk();
        $this->assertDatabaseHas('tasks', ['title' => 'Update title']);
    }
}
