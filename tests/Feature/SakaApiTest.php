<?php

namespace Tests\Feature;

use App\Models\Feedback;
use App\Models\Menu;
use App\Models\Outlet;
use App\Models\Rider;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SakaApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_and_receive_token(): void
    {
        User::create(['name' => 'Admin', 'email' => 'admin@saka.test', 'password' => 'secret123']);

        $this->postJson('/api/admin/login', ['email' => 'admin@saka.test', 'password' => 'secret123'])
            ->assertOk()
            ->assertJsonPath('data.role', 'Admin')
            ->assertJsonStructure(['token', 'expires_in']);
    }

    public function test_active_rider_can_login_but_inactive_rider_cannot(): void
    {
        $outlet = $this->outlet('Cabang Login');
        $active = $this->rider($outlet, 'active_rider');

        $this->postJson('/api/rider/login', ['username' => $active->username, 'password' => 'password123'])
            ->assertOk()
            ->assertJsonPath('data.role', 'Rider');

        $inactiveOutlet = $this->outlet('Cabang Nonaktif');
        $inactive = $this->rider($inactiveOutlet, 'inactive_rider', 'Tidak Aktif');
        $this->postJson('/api/rider/login', ['username' => $inactive->username, 'password' => 'password123'])
            ->assertForbidden();
    }

    public function test_admin_routes_require_admin_token(): void
    {
        $this->getJson('/api/admin/menus')->assertUnauthorized();

        $outlet = $this->outlet('Cabang Rider');
        Sanctum::actingAs($this->rider($outlet), ['rider']);
        $this->getJson('/api/admin/menus')->assertForbidden();
    }

    public function test_admin_can_crud_menu(): void
    {
        $this->actingAsAdmin();

        $created = $this->postJson('/api/admin/menus', [
            'name' => 'Menu Test', 'category' => 'Cup Series', 'cup_price' => 12000,
            'description' => 'Test', 'durability' => 'Satu hari', 'status' => 'Aktif',
        ])->assertCreated()->json('data');

        $this->patchJson('/api/admin/menus/'.$created['id'], ['cup_price' => 15000])
            ->assertOk()->assertJsonPath('data.cup_price', 15000);

        $this->deleteJson('/api/admin/menus/'.$created['id'])->assertNoContent();
        $this->assertDatabaseMissing('menus', ['id' => $created['id']]);
    }

    public function test_admin_can_crud_outlet(): void
    {
        $this->actingAsAdmin();

        $created = $this->postJson('/api/admin/outlets', [
            'branch' => 'Cabang CRUD', 'vehicle' => 'Sepeda', 'open_time' => '10:00',
            'close_time' => '18:00', 'status' => 'Aktif', 'address' => 'Pekanbaru',
        ])->assertCreated()->json('data');

        $this->patchJson('/api/admin/outlets/'.$created['id'], ['status' => 'Tidak Aktif'])
            ->assertOk()->assertJsonPath('data.status', 'Tidak Aktif');

        $this->deleteJson('/api/admin/outlets/'.$created['id'])->assertNoContent();
    }

    public function test_admin_can_crud_rider_and_one_outlet_only_has_one_rider(): void
    {
        $this->actingAsAdmin();
        $outlet = $this->outlet('Cabang Rider CRUD');

        $created = $this->postJson('/api/admin/riders', [
            'outlet_id' => $outlet->id, 'name' => 'Rider CRUD', 'username' => 'rider_crud',
            'password' => 'password123', 'account_status' => 'Aktif', 'operational_status' => 'Tutup',
        ])->assertCreated()->json('data');

        $this->postJson('/api/admin/riders', [
            'outlet_id' => $outlet->id, 'name' => 'Duplikat', 'username' => 'rider_duplikat',
            'password' => 'password123', 'account_status' => 'Aktif', 'operational_status' => 'Tutup',
        ])->assertUnprocessable()->assertJsonValidationErrors('outlet_id');

        $this->patchJson('/api/admin/riders/'.$created['id'], ['operational_status' => 'Berjualan'])
            ->assertOk()->assertJsonPath('data.operational_status', 'Berjualan');

        $this->deleteJson('/api/admin/riders/'.$created['id'])->assertNoContent();
    }

    public function test_customer_feedback_is_always_pending_and_private_fields_are_not_public(): void
    {
        $feedbackId = $this->postJson('/api/public/feedback', [
            'customer_name' => 'Pelanggan', 'phone' => '081234567890', 'type' => 'Review',
            'category' => 'Pelayanan', 'rating' => 5, 'message' => 'Bagus', 'status' => 'Ditampilkan',
        ])->assertCreated()->assertJsonPath('data.status', 'Pending')->json('data.id');

        $this->assertDatabaseHas('feedback', ['id' => $feedbackId, 'status' => 'Pending']);
        $this->getJson('/api/public/feedback')->assertOk()->assertJsonCount(0, 'data');

        Feedback::whereKey($feedbackId)->update(['status' => 'Ditampilkan']);
        $response = $this->getJson('/api/public/feedback')->assertOk()->assertJsonCount(1, 'data');
        $this->assertArrayNotHasKey('phone', $response->json('data.0'));
    }

    public function test_admin_status_update_does_not_erase_feedback_data(): void
    {
        $this->actingAsAdmin();
        $outlet = $this->outlet('Cabang Feedback');
        $feedback = Feedback::create([
            'outlet_id' => $outlet->id, 'customer_name' => 'Customer', 'phone' => '08123',
            'branch' => $outlet->branch, 'type' => 'Keluhan', 'category' => 'Produk',
            'rating' => 2, 'message' => 'Pesan awal', 'status' => 'Pending', 'feedback_date' => '2026-06-01',
        ]);

        $this->patchJson('/api/admin/feedback/'.$feedback->id, ['status' => 'Ditampilkan'])
            ->assertOk()->assertJsonPath('data.status', 'Ditampilkan');

        $feedback->refresh();
        $this->assertSame('08123', $feedback->phone);
        $this->assertSame('2026-06-01', $feedback->feedback_date->format('Y-m-d'));
    }

    public function test_literan_and_donut_business_rules_are_enforced(): void
    {
        $this->actingAsAdmin();
        $regular = $this->outlet('Cabang Biasa');
        $literan = $this->menu('Literan Test', 'Literan');
        $donut = $this->menu('Donat', 'Snack');

        $this->postJson('/api/admin/stocks', [
            'outlet_id' => $regular->id, 'menu_id' => $literan->id, 'stock_status' => 'Tersedia',
        ])->assertUnprocessable();

        $this->postJson('/api/admin/stocks', [
            'outlet_id' => $regular->id, 'menu_id' => $donut->id, 'stock_status' => 'Tersedia',
        ])->assertUnprocessable();

        $dahlia = $this->outlet('OUTLET SAKA DAHLIA');
        $this->postJson('/api/admin/stocks', [
            'outlet_id' => $dahlia->id, 'menu_id' => $literan->id, 'stock_status' => 'Tersedia',
        ])->assertCreated();
    }

    public function test_duplicate_stock_is_rejected(): void
    {
        $this->actingAsAdmin();
        $outlet = $this->outlet('Cabang Stock');
        $menu = $this->menu();
        Stock::create(['outlet_id' => $outlet->id, 'menu_id' => $menu->id, 'stock_status' => 'Tersedia']);

        $this->postJson('/api/admin/stocks', [
            'outlet_id' => $outlet->id, 'menu_id' => $menu->id, 'stock_status' => 'Tersedia',
        ])->assertUnprocessable()->assertJsonValidationErrors('menu_id');
    }

    public function test_rider_only_sees_own_non_literan_stock_and_can_update_it(): void
    {
        $outlet = $this->outlet('Cabang Milik Rider');
        $rider = $this->rider($outlet);
        $ownStock = Stock::create(['outlet_id' => $outlet->id, 'menu_id' => $this->menu()->id, 'rider_id' => $rider->id, 'stock_status' => 'Tersedia']);

        $otherOutlet = $this->outlet('Cabang Lain');
        $otherStock = Stock::create(['outlet_id' => $otherOutlet->id, 'menu_id' => $this->menu('Menu Lain')->id, 'stock_status' => 'Tersedia']);

        Sanctum::actingAs($rider, ['rider']);
        $this->getJson('/api/rider/stocks')->assertOk()->assertJsonCount(1, 'data');
        $this->patchJson('/api/rider/stocks/'.$ownStock->id.'/availability', ['stock_status' => 'Tidak Tersedia'])
            ->assertOk()->assertJsonPath('data.stock_status', 'Tidak Tersedia');
        $this->patchJson('/api/rider/stocks/'.$otherStock->id.'/availability', ['stock_status' => 'Tidak Tersedia'])
            ->assertForbidden();
    }

    public function test_rider_cannot_update_literan_or_admin_resources(): void
    {
        $dahlia = $this->outlet('OUTLET SAKA DAHLIA');
        $rider = $this->rider($dahlia);
        $stock = Stock::create([
            'outlet_id' => $dahlia->id, 'menu_id' => $this->menu('Literan Rider', 'Literan')->id,
            'rider_id' => $rider->id, 'stock_status' => 'Tersedia',
        ]);

        Sanctum::actingAs($rider, ['rider']);
        $this->patchJson('/api/rider/stocks/'.$stock->id.'/availability', ['stock_status' => 'Tidak Tersedia'])
            ->assertForbidden();
        $this->postJson('/api/admin/menus', [])->assertForbidden();
    }

    private function actingAsAdmin(): User
    {
        $admin = User::factory()->create();
        Sanctum::actingAs($admin, ['admin']);

        return $admin;
    }

    private function outlet(string $branch): Outlet
    {
        return Outlet::create(['branch' => $branch, 'status' => 'Aktif']);
    }

    private function rider(Outlet $outlet, string $username = 'rider_test', string $status = 'Aktif'): Rider
    {
        return Rider::create([
            'outlet_id' => $outlet->id, 'name' => 'Rider Test', 'username' => $username,
            'password' => Hash::make('password123'), 'account_status' => $status, 'operational_status' => 'Tutup',
        ]);
    }

    private function menu(string $name = 'Kopi Test', string $category = 'Cup Series'): Menu
    {
        return Menu::create(['name' => $name, 'category' => $category, 'cup_price' => 10000, 'status' => 'Aktif']);
    }
}
