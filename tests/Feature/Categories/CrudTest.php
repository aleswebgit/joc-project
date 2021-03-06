<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CrudTest extends TestCase {
  /**
   * A basic feature test example.
   *
   * @return void
   */

  use RefreshDatabase;

  public function test_list_categories_appear_in_home() {
    $this->withExceptionHandling();
    Category::all();

    $response = $this->get('/');
    $response->assertStatus(200)->assertViewIs('home');
  }

  public function test_categories_can_be_delete_if_user_is_admin() {
    $this->withExceptionHandling();

    $userAdmin = User::factory()->create(['isAdmin' => true]);
    $this->actingAs($userAdmin);

    $category = Category::factory()->create();

    $response = $this->delete(route('categories.delete', $category->id));
    $this->assertCount(0, Category::all());
  }

  public function test_categories_can_not_be_delete_if_user_is_not_admin() {
    $this->withExceptionHandling();

    $user1 = User::factory()->create();
    $this->actingAs($user1);

    $category = Category::factory()->create();
    $this->assertCount(1, Category::all());

    $response = $this->delete(route('categories.delete', $category->id));
    $this->assertCount(1, Category::all());
  }


  public function test_category_can_be_update() {
    $this->withExceptionHandling();

    $userAdmin = User::factory()->create(['isAdmin' => true]);
    $this->actingAs($userAdmin);

    $category = Category::factory()->create([
      'name' => 'test',
    ]);
    
    $this->assertCount(1, Category::all());

    $this->patch(route('categories.update', $category->id), ["name" => "new name"]);

    $this->assertEquals("new name", Category::first()->name);
    $this->assertCount(1, Category::all());
  }

  public function test_view_edit_form() {
    $this->withExceptionHandling();

    $userAdmin = User::factory()->create(['isAdmin' => true]);
    $this->actingAs($userAdmin);

    $category = Category::factory()->create();

    $response = $this->get(route('categories.edit', $category->id));
    $response->assertStatus(200)->assertViewIs('categories.edit');
  }

  public function test_create_category() {
    $this->withExceptionHandling();
    // $category = Category::factory()->make();

    $userAdmin = User::factory()->create(['isAdmin' => true]);
    $this->actingAs($userAdmin);

    $response = $this->post(route('categories.store'), [
      'name' => 'New test',
      'slug' => 'new-test',
      'image' => 'test.png',
    ]);
    $this->assertCount(1, Category::all());
    $response->assertStatus(302)->assertRedirect(route('home'));
  }

  public function test_view_create_form() {
    $this->withExceptionHandling();

    $userAdmin = User::factory()->create(['isAdmin' => true]);
    $this->actingAs($userAdmin);

    $response = $this->get(route('categories.create'));
    $response->assertStatus(200)->assertViewIs('categories.create');
  }

  public function test_view_show_ok() {
    $this->withExceptionHandling();

    $category = Category::factory()->create();

    $response = $this->get(route('categories.show', $category->id));
    $response->assertStatus(200)->assertSee($category->name);
  }

}
