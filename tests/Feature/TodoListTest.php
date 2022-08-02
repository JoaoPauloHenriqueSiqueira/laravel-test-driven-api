<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    private $todoList;

    public function setUp(): void
    {
        parent::setUp();
        $user = $this->authUser();
        $this->todoList = $this->createTodoList(['name' => 'My list', 'user_id' => $user]);
    }

    public function test_fetch_all_todo_list()
    {
        $this->createTodoList();

        $response = $this->getJson(route('todo-list.index'));
        $this->assertEquals(1, count($response->json()));
    }

    public function test_fetch_single_todo_list()
    {
        
        $response = $this->getJson(route('todo-list.show', $this->todoList->id))->assertOk()->json();

        $this->assertEquals($this->todoList->name, $response['name']);
    }

    public function test_store_new_todo_list()
    {
        $todoList = TodoList::factory()->make();

        $response = $this->postJson(route('todo-list.store'), ['name' => $todoList->name])->assertCreated()->json();

        $this->assertEquals($todoList->name, $response['name']);
        $this->assertDatabaseHas('todo_lists', ['name' => $todoList->name]);
    }

    public function test_while_storing_todo_list_name_is_required()
    {
        $this->withExceptionHandling();
        $this->postJson(route('todo-list.store'))->assertUnprocessable()->assertJsonValidationErrors(['name']);
    }

    public function test_delete_todo_list()
    {
        $this->deleteJson(route('todo-list.destroy', $this->todoList->id))->assertNoContent();
        $this->assertDatabaseMissing('todo_lists', ['id' => $this->todoList->id]);
    }

    public function test_update_todo_list()
    {
        $this->patchJson(route('todo-list.update', $this->todoList->id), ['name' => 'Updated name'])->assertOk()->json();

        $this->assertDatabaseHas('todo_lists', ['id' => $this->todoList->id, 'name' => 'Updated name']);
    }

    public function test_while_updating_todo_list_name_is_required()
    {
        $this->withExceptionHandling();
        $this->patchJson(route('todo-list.update', $this->todoList->id))->assertUnprocessable()->assertJsonValidationErrors(['name']);
    }
}
