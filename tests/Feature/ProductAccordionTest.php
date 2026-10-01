<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductAccordionTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $category = Category::create([
            'name' => 'Hoodies',
            'slug' => 'hoodies',
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Ladies Hoodie',
            'slug' => 'ladies-hoodie',
            'price' => 120.00,
            'stock_quantity' => 10,
            'status' => 'active',
            'colors' => ['Black'],
            'sizes' => ['S', 'M', 'L'],
        ]);
    }

    public function test_product_page_shows_default_accordions_when_product_fields_are_null(): void
    {
        $response = $this->get('/shop/ladies-hoodie');

        $response->assertStatus(200);
        $response->assertSee('Details & Fit', false);
        $response->assertSee('Shipping & Returns', false);
        $response->assertSee('Garment Care', false);
        $response->assertSee('Heavyweight 390 GSM');
        $response->assertSee('All orders are dispatched from our atelier');
        $response->assertSee('Machine wash cold inside-out');
    }

    public function test_product_page_shows_custom_product_specific_accordions(): void
    {
        $this->product->update([
            'details_and_fit' => "- Custom 100% Merino Wool\n- Tailored fit cut",
            'shipping_and_returns' => 'Special express courier within 24h only.',
            'garment_care' => 'Dry clean only with specialist care.',
        ]);

        $response = $this->get('/shop/ladies-hoodie');

        $response->assertStatus(200);
        $response->assertSee('Custom 100% Merino Wool');
        $response->assertSee('Tailored fit cut');
        $response->assertSee('Special express courier within 24h only.');
        $response->assertSee('Dry clean only with specialist care.');
    }

    public function test_admin_can_update_global_default_accordions_in_settings(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/settings', [
            'store_name' => 'PISTIS Atelier',
            'contact_email' => 'contact@pistis.com',
            'currency_symbol' => '€',
            'currency_code' => 'EUR',
            'default_details_and_fit' => "Global Premium Organic Cotton\nPreshrunk Luxury Fabric",
            'default_shipping_and_returns' => 'Global free worldwide express shipping.',
            'default_garment_care' => 'Hand wash in cold water with delicate soap.',
        ]);

        $response->assertRedirect('/admin/settings');
        $this->assertEquals('Global free worldwide express shipping.', Setting::get('default_shipping_and_returns'));

        // Fresh product should reflect updated store global defaults
        $pageResponse = $this->get('/shop/ladies-hoodie');
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee('Global Premium Organic Cotton');
        $pageResponse->assertSee('Global free worldwide express shipping.');
        $pageResponse->assertSee('Hand wash in cold water with delicate soap.');
    }
}
