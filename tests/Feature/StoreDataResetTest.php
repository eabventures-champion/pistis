<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\HeroSlide;
use App\Models\Product;
use App\Models\SizeGuide;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StoreDataResetTest extends TestCase
{
    use DatabaseTransactions;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin_reset_test_' . uniqid() . '@pistis.com',
            'password' => Hash::make('secret_admin_pass'),
            'is_admin' => true,
        ]);
    }

    public function test_guest_cannot_wipe_store_data(): void
    {
        $response = $this->post(route('admin.settings.wipe-data'), [
            'password' => 'secret_admin_pass',
            'confirmation_text' => 'RESET',
        ]);

        $response->assertRedirect('/admin/login');
    }

    public function test_wipe_requires_correct_password_and_confirmation(): void
    {
        // Wrong password
        $response = $this->actingAs($this->admin)->post(route('admin.settings.wipe-data'), [
            'password' => 'wrong_password',
            'confirmation_text' => 'RESET',
        ]);

        $response->assertSessionHasErrors(['password']);

        // Wrong confirmation text
        $response2 = $this->actingAs($this->admin)->post(route('admin.settings.wipe-data'), [
            'password' => 'secret_admin_pass',
            'confirmation_text' => 'WRONG_WORD',
        ]);

        $response2->assertSessionHasErrors(['confirmation_text']);
    }

    public function test_admin_can_wipe_store_data_while_preserving_admin_users(): void
    {
        // Create sample data to be wiped
        $category = Category::create([
            'name' => 'Wipe Test Category ' . uniqid(),
            'slug' => 'wipe-cat-' . uniqid(),
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Wipe Test Product',
            'slug' => 'wipe-product-' . uniqid(),
            'price' => 50,
            'stock_quantity' => 10,
            'status' => 'active',
        ]);

        $customer = Customer::create([
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'email' => 'customer_' . uniqid() . '@test.com',
            'password' => Hash::make('password'),
        ]);

        $regularUser = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.settings.wipe-data'), [
            'password' => 'secret_admin_pass',
            'confirmation_text' => 'RESET',
        ]);

        $response->assertRedirect(route('admin.settings.index'));
        $response->assertSessionHas('success');

        // Verify products, categories, customers are wiped
        $this->assertEquals(0, Product::count());
        $this->assertEquals(0, Category::count());
        $this->assertEquals(0, Customer::count());

        // Verify regular user was removed
        $this->assertDatabaseMissing('users', ['id' => $regularUser->id]);

        // Verify administrator was strictly preserved
        $this->assertDatabaseHas('users', ['id' => $this->admin->id, 'is_admin' => true]);
    }
}
