<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class GuestProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_products_index()
    {
        $response = $this->get('/products');
        $response->assertStatus(200);
        $response->assertViewIs('product.guest.index'); // или какой у тебя там вид
    }

    public function test_guest_can_view_product_show()
    {
        $product = Product::factory()->create();

        $response = $this->get("/products/{$product->uuid}");
        $response->assertStatus(200);
        $response->assertViewIs('product.guest.show'); // замени на реальный
        $response->assertSee($product->name);
    }

    public function test_guest_cannot_access_admin_routes()
    {
        $product = Product::factory()->create();

        $response = $this->get('/admin/products');
        $response->assertRedirect('/login');

        $response = $this->get("/admin/products/{$product->id}/edit");
        $response->assertRedirect('/login');

        $response = $this->post('/admin/products', []);
        $response->assertRedirect('/login');

        $response = $this->delete("/admin/products/{$product->id}");
        $response->assertRedirect('/login');
    }
}
