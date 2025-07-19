<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Queue;
use Tests\TestCase;

final class AdminProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_admin_can_view_product_index(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->get('/admin/products');

        $response->assertStatus(200);
        $response->assertViewIs('product.admin.index');
    }

    public function test_admin_can_create_product(): void
    {
        $company = Company::factory()->create();
        $type = ProductType::factory()->create();

        $response = $this->post('/admin/products', [
            'name' => 'Test Product',
            'price' => 100,
            'releaseDate' => '2024-01-01',
            'description' => 'Test description',
            'company_id' => $company->id,
            'product_type_id' => $type->id,
        ]);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'description' => 'Test description',
        ]);
    }

    public function test_admin_can_edit_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->get("/admin/products/{$product->uuid}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('product.admin.edit');
    }

    public function test_admin_can_update_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->put("/admin/products/{$product->uuid}", [
            'name' => 'Updated Name',
            'price' => 999,
            'description' => 'Updated description',
            'releaseDate' => '2025-01-01',
            'product_type_id' => ProductType::factory()->create()->id,
            'company_id' => Company::factory()->create()->id,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Updated Name']);
    }

    public function test_admin_can_delete_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->delete("/admin/products/{$product->id}");

        $response->assertRedirect('/');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_export_dispatches_jobs(): void
    {
        Queue::fake();

        Product::factory()->count(150)->create();

        $response = $this->post(route('admin.products.export'));

        $response->assertRedirect();
        Queue::assertPushed(\App\Jobs\ExportProductJob::class);
    }
}
