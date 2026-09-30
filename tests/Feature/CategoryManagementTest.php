<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    private function createAdminUser(): User
    {
        $user = new User([
            'name' => 'Admin User',
            'email' => 'admin_test_' . uniqid() . '@pistis.com',
            'password' => bcrypt('password'),
        ]);
        $user->is_admin = true;
        $user->save();
        return $user;
    }

    public function test_admin_can_toggle_category_status_via_ajax(): void
    {
        $admin = $this->createAdminUser();
        $category = Category::create([
            'name' => 'Test Footwear ' . uniqid(),
            'slug' => 'test-footwear-' . uniqid(),
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->patch(route('admin.categories.toggle-status', $category));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_active' => false,
        ]);

        $this->assertFalse($category->fresh()->is_active);

        // Toggle back to active
        $response2 = $this->actingAs($admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->patch(route('admin.categories.toggle-status', $category));

        $response2->assertStatus(200);
        $response2->assertJson([
            'success' => true,
            'is_active' => true,
        ]);

        $this->assertTrue($category->fresh()->is_active);

        $category->delete();
        $admin->delete();
    }

    public function test_admin_can_update_category_details(): void
    {
        $admin = $this->createAdminUser();
        $parent = Category::create([
            'name' => 'Parent Cat ' . uniqid(),
            'slug' => 'parent-cat-' . uniqid(),
            'is_active' => true,
        ]);

        $category = Category::create([
            'name' => 'Sub Cat ' . uniqid(),
            'slug' => 'sub-cat-' . uniqid(),
            'description' => 'Old description',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.categories.update', $category), [
            'name' => 'Updated Sub Cat',
            'description' => 'New refined description',
            'parent_id' => $parent->id,
            'is_active' => '0',
        ]);

        $response->assertRedirect(route('admin.categories.index'));

        $fresh = $category->fresh();
        $this->assertEquals('Updated Sub Cat', $fresh->name);
        $this->assertEquals('New refined description', $fresh->description);
        $this->assertEquals($parent->id, $fresh->parent_id);
        $this->assertFalse($fresh->is_active);

        $category->delete();
        $parent->delete();
        $admin->delete();
    }

    public function test_admin_cannot_delete_parent_category_with_subcategories(): void
    {
        $admin = $this->createAdminUser();
        $parent = Category::create([
            'name' => 'Parent Protect ' . uniqid(),
            'slug' => 'parent-protect-' . uniqid(),
            'is_active' => true,
        ]);

        $child = Category::create([
            'name' => 'Child Protect ' . uniqid(),
            'slug' => 'child-protect-' . uniqid(),
            'parent_id' => $parent->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.categories.destroy', $parent));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('error');

        // Confirm parent was NOT deleted
        $this->assertNotNull(Category::find($parent->id));

        // Child can be deleted
        $childDeleteResponse = $this->actingAs($admin)->delete(route('admin.categories.destroy', $child));
        $childDeleteResponse->assertRedirect(route('admin.categories.index'));
        $childDeleteResponse->assertSessionHas('success');
        $this->assertNull(Category::find($child->id));

        // Now parent has no children and CAN be deleted
        $parentDeleteResponse = $this->actingAs($admin)->delete(route('admin.categories.destroy', $parent));
        $parentDeleteResponse->assertRedirect(route('admin.categories.index'));
        $parentDeleteResponse->assertSessionHas('success');
        $this->assertNull(Category::find($parent->id));

        $admin->delete();
    }

    public function test_category_displays_multi_level_breadcrumb_path(): void
    {
        $clothing = Category::create([
            'name' => 'Clothing ' . uniqid(),
            'slug' => 'clothing-' . uniqid(),
            'is_active' => true,
        ]);

        $mensWear = Category::create([
            'name' => "Men's Wear " . uniqid(),
            'slug' => 'mens-wear-' . uniqid(),
            'parent_id' => $clothing->id,
            'is_active' => true,
        ]);

        $shirts = Category::create([
            'name' => 'Shirts ' . uniqid(),
            'slug' => 'shirts-' . uniqid(),
            'parent_id' => $mensWear->id,
            'is_active' => true,
        ]);

        $this->assertEquals("{$clothing->name} → {$mensWear->name} → ", $shirts->parent_path);
        $this->assertEquals("{$clothing->name} → {$mensWear->name} → {$shirts->name}", $shirts->full_path);

        $admin = $this->createAdminUser();
        $response = $this->actingAs($admin)->get(route('admin.categories.index'));
        $response->assertStatus(200);
        $response->assertSee("{$clothing->name} → {$mensWear->name} → ");
        $response->assertSee($shirts->name);

        $shirts->delete();
        $mensWear->delete();
        $clothing->delete();
        $admin->delete();
    }

    public function test_admin_can_bulk_delete_multiple_categories(): void
    {
        $admin = $this->createAdminUser();
        $c1 = Category::create(['name' => 'Cat 1 ' . uniqid(), 'slug' => 'cat1-' . uniqid()]);
        $c2 = Category::create(['name' => 'Cat 2 ' . uniqid(), 'slug' => 'cat2-' . uniqid()]);
        $c3 = Category::create(['name' => 'Cat 3 ' . uniqid(), 'slug' => 'cat3-' . uniqid()]);

        $response = $this->actingAs($admin)->delete(route('admin.categories.bulk-destroy'), [
            'category_ids' => [$c1->id, $c2->id, $c3->id],
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertNull(Category::find($c1->id));
        $this->assertNull(Category::find($c2->id));
        $this->assertNull(Category::find($c3->id));

        $admin->delete();
    }

    public function test_admin_bulk_delete_rejects_parent_with_unselected_subcategories(): void
    {
        $admin = $this->createAdminUser();
        $parent = Category::create(['name' => 'Parent Bulk ' . uniqid(), 'slug' => 'parent-bulk-' . uniqid()]);
        $child = Category::create(['name' => 'Child Bulk ' . uniqid(), 'slug' => 'child-bulk-' . uniqid(), 'parent_id' => $parent->id]);

        // Attempting to delete only parent
        $response = $this->actingAs($admin)->delete(route('admin.categories.bulk-destroy'), [
            'category_ids' => [$parent->id],
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('error');

        // Confirm parent still exists
        $this->assertNotNull(Category::find($parent->id));

        // When deleting both parent and child together, it succeeds
        $response2 = $this->actingAs($admin)->delete(route('admin.categories.bulk-destroy'), [
            'category_ids' => [$parent->id, $child->id],
        ]);

        $response2->assertRedirect(route('admin.categories.index'));
        $response2->assertSessionHas('success');

        $this->assertNull(Category::find($parent->id));
        $this->assertNull(Category::find($child->id));

        $admin->delete();
    }
}
