<?php

namespace Tests\Feature;

use App\Models\Label;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LabelTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->authUser();
    }

    public function test_user_can_create_new_label()
    {
        $label = Label::factory()->raw();

        $this->postJson(route('label.store'), $label)->assertCreated();

        $this->assertDatabaseHas('labels', ['title' => $label['title'], 'color' => $label['color']]);
    }

    public function test_user_can_delete_a_label()
    {
        $label = Label::factory()->create();

        $this->deleteJson(route('label.destroy', $label->id))->assertNoContent();

        $this->assertDatabaseMissing('labels', ['id' => $label->id]);
    }

    public function test_user_can_update_a_label()
    {
        $label = Label::factory()->create();
        $updateTitle = 'Updated Title';
        $this->patchJson(route('label.update', $label->id), ['title' => $updateTitle, 'color' => 'new-color'])->assertOk();
        $this->assertDatabaseHas('labels', ['title' => $updateTitle]);
    }

    public function test_fetch_all_labels_from_a_user()
    {
        $label = $this->createLabel(['user_id' => $this->user->id]);
        
        $this->createLabel();

        $response = $this->getJson(route('label.index'))->assertOk()->json();

        $this->assertEquals($response[0]['title'], $label->title);
    }
}
