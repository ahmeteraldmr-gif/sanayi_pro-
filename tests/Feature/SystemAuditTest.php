<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Part;
use App\Models\WorkOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemAuditTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $master;
    protected Branch $branch;
    protected WorkOrder $workOrder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->branch = Branch::create([
            'name' => 'Merkez Oto Elektrik',
        ]);

        $this->admin = User::create([
            'name' => 'Sistem Yoneticisi',
            'email' => 'admin@sanayi.local',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->master = User::create([
            'name' => 'Ahmet Usta',
            'email' => 'ahmet@elektrik.local',
            'password' => bcrypt('password'),
            'role' => 'branch_user',
            'branch_id' => $this->branch->id,
        ]);

        $customer = Customer::create([
            'branch_id' => $this->branch->id,
            'name' => 'Mehmet Demir',
            'phone' => '05321112233',
        ]);

        $vehicle = Vehicle::create([
            'branch_id' => $this->branch->id,
            'customer_id' => $customer->id,
            'plate' => '34 ABC 789',
            'brand' => 'Renault',
            'model' => 'Megane',
            'year' => 2020,
        ]);

        $part = Part::create([
            'branch_id' => $this->branch->id,
            'code' => 'AKU-72',
            'name' => '72Ah Aku',
            'category' => 'Aku',
            'stock' => 10,
            'min_stock' => 2,
            'buy_price' => 1500,
            'sell_price' => 2200,
        ]);

        $this->workOrder = WorkOrder::create([
            'branch_id' => $this->branch->id,
            'vehicle_id' => $vehicle->id,
            'date' => now()->toDateString(),
            'status' => 'beklemede',
            'total_parts' => 2200,
            'total_labor' => 500,
            'discount' => 0,
            'notes' => 'Mars basmiyor',
        ]);
    }

    public function test_public_pages(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('SanayiPro');

        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Sanayi');
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');

        $response = $this->get('/admin/branches');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_all_management_pages(): void
    {
        $this->actingAs($this->admin);

        $routes = [
            '/dashboard',
            '/admin/branches',
            '/admin/branches/create',
            '/admin/users',
            '/admin/users/create',
            '/customers',
            '/customers/create',
            '/vehicles',
            '/vehicles/create',
            '/parts',
            '/parts/create',
            '/work-orders',
            '/work-orders/create',
            '/reports',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_branch_master_access_and_rbac_restrictions(): void
    {
        $this->actingAs($this->master);

        // Branch master must NOT access admin routes
        $response = $this->get('/admin/branches');
        $response->assertStatus(403);

        $response = $this->get('/admin/users');
        $response->assertStatus(403);

        // Branch master CAN access standard shop operations
        $masterRoutes = [
            '/dashboard',
            '/customers',
            '/vehicles',
            '/parts',
            '/work-orders',
            '/reports',
        ];

        foreach ($masterRoutes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_ajax_and_search_endpoints(): void
    {
        $this->actingAs($this->master);

        $response = $this->getJson('/vehicles/quick-search?plate=34');
        $response->assertStatus(200);

        $response = $this->getJson('/parts/ajax-search?q=a');
        $response->assertStatus(200);
    }

    public function test_work_order_print_and_detail_views(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get("/work-orders/{$this->workOrder->id}");
        $response->assertStatus(200);

        $response = $this->get("/work-orders/{$this->workOrder->id}/print");
        $response->assertStatus(200);
        $response->assertSee('34 ABC 789');
    }

    public function test_admin_branch_crud_operations(): void
    {
        $this->actingAs($this->admin);

        // Store
        $resStore = $this->post('/admin/branches', ['name' => 'Kaporta & Boya']);
        $resStore->assertRedirect('/admin/branches');
        $this->assertDatabaseHas('branches', ['name' => 'Kaporta & Boya']);

        $newBranch = Branch::where('name', 'Kaporta & Boya')->first();

        // Edit View
        $this->get("/admin/branches/{$newBranch->id}/edit")->assertStatus(200);

        // Update
        $resUpdate = $this->put("/admin/branches/{$newBranch->id}", ['name' => 'Kaporta, Boya & Düzeltme']);
        $resUpdate->assertRedirect('/admin/branches');
        $this->assertDatabaseHas('branches', ['name' => 'Kaporta, Boya & Düzeltme']);

        // Destroy
        $resDestroy = $this->delete("/admin/branches/{$newBranch->id}");
        $resDestroy->assertRedirect('/admin/branches');
        $this->assertDatabaseMissing('branches', ['id' => $newBranch->id]);
    }

    public function test_admin_user_crud_operations(): void
    {
        $this->actingAs($this->admin);

        // Store User
        $resStore = $this->post('/admin/users', [
            'name' => 'Mehmet Usta',
            'email' => 'mehmet@sanayi.local',
            'password' => 'password123',
            'role' => 'branch_user',
            'branch_id' => $this->branch->id,
        ]);
        $resStore->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', ['email' => 'mehmet@sanayi.local']);

        $newUser = User::where('email', 'mehmet@sanayi.local')->first();

        // Edit View
        $this->get("/admin/users/{$newUser->id}/edit")->assertStatus(200);

        // Update User
        $resUpdate = $this->put("/admin/users/{$newUser->id}", [
            'name' => 'Mehmet Usta Güncel',
            'email' => 'mehmet@sanayi.local',
            'role' => 'branch_user',
            'branch_id' => $this->branch->id,
        ]);
        $resUpdate->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', ['name' => 'Mehmet Usta Güncel']);

        // Destroy User
        $resDestroy = $this->delete("/admin/users/{$newUser->id}");
        $resDestroy->assertRedirect('/admin/users');
        $this->assertDatabaseMissing('users', ['id' => $newUser->id]);
    }

    public function test_unified_vehicle_and_customer_creation(): void
    {
        $this->actingAs($this->master);

        $res = $this->post('/vehicles', [
            'customer_mode' => 'new',
            'new_customer_name' => 'Mustafa Kaya',
            'new_customer_phone' => '05559998877',
            'new_customer_address' => 'Sanayi Mah. No:12',
            'plate' => '34XYZ999',
            'brand' => 'Honda',
            'model' => 'Civic',
            'year' => '2022',
            'color' => 'Siyah',
        ]);

        $res->assertRedirect('/vehicles');

        $this->assertDatabaseHas('customers', [
            'name' => 'Mustafa Kaya',
            'phone' => '05559998877',
        ]);

        $customer = Customer::where('name', 'Mustafa Kaya')->first();

        $this->assertDatabaseHas('vehicles', [
            'customer_id' => $customer->id,
            'plate' => '34XYZ999',
            'brand' => 'Honda',
        ]);
    }
}