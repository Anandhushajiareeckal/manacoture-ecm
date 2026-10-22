<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Page;

class AdminCmsTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create([
            'name' => 'Super Administrator',
            'slug' => 'super-admin',
            'description' => 'Full access',
        ]);

        $this->adminUser = User::create([
            'role_id' => $role->id,
            'name' => 'Admin Test User',
            'email' => 'admin.test@inspinia.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
    }

    public function test_admin_can_view_cms_home_editor(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.cms.home'));
        $response->assertStatus(200);
        $response->assertSee('Storefront & Home CMS', false);
        $response->assertSee('Hero Showcase');
    }

    public function test_admin_can_view_cms_about_editor(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.cms.about'));
        $response->assertStatus(200);
        $response->assertSee('About & Atelier Story CMS', false);
        $response->assertSee('The Three Pillars of Craft');
    }

    public function test_admin_can_update_cms_settings_and_reflects_on_storefront(): void
    {
        $newEyebrow = 'Royal Vishu Edit · 2027';
        $newTitle1 = 'Handspun Majesty.';
        $newTitle2 = 'Woven For Generations.';

        $response = $this->actingAs($this->adminUser)->post(route('admin.cms.update'), [
            'cms_form_context' => 'home',
            'cms_hero_eyebrow' => $newEyebrow,
            'cms_hero_title_1' => $newTitle1,
            'cms_hero_title_2' => $newTitle2,
            'cms_hero_btn1_text' => 'Shop Royal Silks',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals($newEyebrow, Setting::get('cms_hero_eyebrow'));

        // Check storefront immediately renders dynamic values
        $storefront = $this->get(route('home'));
        $storefront->assertStatus(200);
        $storefront->assertSee($newEyebrow);
        $storefront->assertSee($newTitle1);
        $storefront->assertSee($newTitle2);
        $storefront->assertSee('Shop Royal Silks');
    }

    public function test_admin_can_create_custom_cms_page(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.pages.store'), [
            'title' => 'Handloom Care Instructions',
            'slug' => 'handloom-care-instructions',
            'content' => '<h2>Proper Saree Care</h2><p>Always dry clean your pure zari heirlooms.</p>',
            'excerpt' => 'A comprehensive guide to preserving Kasavu sarees.',
            'is_active' => 1,
            'sort_order' => 1,
        ]);

        $response->assertRedirect(route('admin.pages.index'));
        $this->assertDatabaseHas('pages', [
            'slug' => 'handloom-care-instructions',
            'title' => 'Handloom Care Instructions',
        ]);

        // Assert public can view page
        $publicPage = $this->get(route('pages.show', 'handloom-care-instructions'));
        $publicPage->assertStatus(200);
        $publicPage->assertSee('Handloom Care Instructions');
        $publicPage->assertSee('Proper Saree Care');
    }

    public function test_admin_can_update_and_toggle_page_status(): void
    {
        $page = Page::create([
            'title' => 'Draft Policy',
            'slug' => 'draft-policy',
            'content' => '<p>Work in progress policy.</p>',
            'is_active' => false,
        ]);

        // Should return 404 for public when inactive
        $this->get(route('pages.show', 'draft-policy'))->assertStatus(404);

        // Toggle to active
        $response = $this->actingAs($this->adminUser)->patch(route('admin.pages.toggle-status', $page));
        $response->assertSessionHas('success');
        $this->assertTrue($page->fresh()->is_active);

        // Now public can view it
        $this->get(route('pages.show', 'draft-policy'))->assertStatus(200);
    }
}
