<?php

namespace Tests\Feature;

use App\Models\ProductModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_normal_user_can_view_product_list_and_details(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $product = ProductModel::create([
            'name' => 'Espresso',
            'tagline' => 'Strong & Bold',
            'badge' => 'Hot',
            'price' => 3.50,
            'currency' => '$',
            'description' => 'Classic Italian espresso.',
        ]);

        $response = $this->actingAs($user)->get('/productView');
        $response->assertStatus(200);
        $response->assertSee('Espresso');
        $response->assertSee('View Details');
        // Normal user should NOT see Admin Dashboard link or Add New Product button
        $response->assertDontSee('Add New Product');

        // Test show endpoint
        $showResponse = $this->actingAs($user)->getJson("/products/{$product->id}");
        $showResponse->assertStatus(200);
        $showResponse->assertJsonFragment(['name' => 'Espresso']);
    }

    public function test_normal_user_cannot_create_or_edit_or_delete_product(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $product = ProductModel::create([
            'name' => 'Mocha',
            'tagline' => 'Chocolate coffee',
            'price' => 4.50,
            'currency' => '$',
            'description' => 'Rich mocha.',
        ]);

        // Attempt create page
        $this->actingAs($user)->get('/product-form-view')->assertRedirect(route('productView'));

        // Attempt store
        $this->actingAs($user)->post('/create-product', [
            'name' => 'Unauthorized Product',
            'tagline' => 'Test',
            'price' => 5.0,
            'currency' => '$',
            'description' => 'Test',
        ])->assertRedirect(route('productView'));

        // Attempt edit page
        $this->actingAs($user)->get("/edit-product/{$product->id}")->assertRedirect(route('productView'));

        // Attempt update
        $this->actingAs($user)->put("/update-product/{$product->id}", [
            'name' => 'Hacked Name',
            'tagline' => 'Test',
            'price' => 5.0,
            'currency' => '$',
            'description' => 'Test',
        ])->assertRedirect(route('productView'));

        // Attempt delete
        $this->actingAs($user)->delete("/delete-product/{$product->id}")->assertRedirect(route('productView'));

        $this->assertDatabaseHas('product', ['id' => $product->id, 'name' => 'Mocha']);
    }

    public function test_admin_can_manage_products(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Admin can access dashboard
        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');
        $response->assertSee('Add New Product');

        // Admin can access create form
        $this->actingAs($admin)->get('/product-form-view')->assertStatus(200);

        // Admin can create a product
        $createResponse = $this->actingAs($admin)->post('/create-product', [
            'name' => 'Cappuccino',
            'tagline' => 'Creamy foam',
            'badge' => 'Popular',
            'price' => 4.25,
            'currency' => '$',
            'description' => 'Smooth espresso with steamed milk foam.',
        ]);
        $createResponse->assertRedirect(route('dashboard'));
        $createResponse->assertSessionHas('success');

        $this->assertDatabaseHas('product', ['name' => 'Cappuccino']);

        $product = ProductModel::where('name', 'Cappuccino')->first();

        // Admin can access edit form
        $this->actingAs($admin)->get("/edit-product/{$product->id}")->assertStatus(200);

        // Admin can update product
        $updateResponse = $this->actingAs($admin)->put("/update-product/{$product->id}", [
            'name' => 'Iced Cappuccino',
            'tagline' => 'Chilled & frothy',
            'badge' => 'Summer Special',
            'price' => 4.75,
            'currency' => '$',
            'description' => 'Chilled espresso with cold froth.',
        ]);
        $updateResponse->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('product', ['name' => 'Iced Cappuccino']);

        // Admin can delete product
        $deleteResponse = $this->actingAs($admin)->delete("/delete-product/{$product->id}");
        $deleteResponse->assertRedirect(route('dashboard'));
        $this->assertDatabaseMissing('product', ['id' => $product->id]);
    }
}
