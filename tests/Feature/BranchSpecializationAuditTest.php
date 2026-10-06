<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Part;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BranchSpecializationAuditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_all_23_branches_exist_with_users_and_specializations(): void
    {
        $branches = Branch::all();
        $this->assertGreaterThanOrEqual(23, $branches->count());

        $expectedBranches = [
            'Motor & Mekanik',
            'Şanzıman & Vites Kutusu',
            'Ön Düzen & Rot-Balans',
            'Fren Sistemleri',
            'Enjektör & Turbo',
            'Oto Elektrik',
            'Oto Beyin & Elektronik',
            'Oto Klima Ustası',
            'Akümülatör (Akü) Ustası',
            'Kaporta Ustası',
            'Oto Boyacı',
            'PDR (Boyasız Göçük) Ustası',
            'Plastik Tamircisi',
            'Oto Camcısı',
            'Oto Döşeme Ustası',
            'Oto Kilit & İmmobilizer',
            'Ses & Multimedya Ustası',
            'Egzoz Ustası',
            'Radyatör & Petek Ustası',
            'Torna & Kaynak Ustası',
            'Rot-Balans Lastikçisi',
            'Periyodik Hızlı Bakım',
            'Oto LPG & Gaz Sistemleri',
        ];

        foreach ($expectedBranches as $branchName) {
            $branch = Branch::where('name', $branchName)->first();
            $this->assertNotNull($branch, "Branch {$branchName} must exist");
            $this->assertNotEmpty($branch->title, "Branch {$branchName} must have specialization title");

            $user = User::where('branch_id', $branch->id)->first();
            $this->assertNotNull($user, "Branch {$branchName} must have at least one user");

            $quickActions = Branch::getBranchQuickActions($branchName);
            $this->assertCount(4, $quickActions, "Branch {$branchName} must have 4 quick actions");
        }
    }

    public function test_branch_data_isolation_prevents_idor_access(): void
    {
        $branch1 = Branch::where('name', 'Motor & Mekanik')->first();
        $branch2 = Branch::where('name', 'Fren Sistemleri')->first();

        $user1 = User::where('branch_id', $branch1->id)->first();
        $user2 = User::where('branch_id', $branch2->id)->first();

        // Branch 1's customer & vehicle & work order
        $customer1 = Customer::where('branch_id', $branch1->id)->first();
        $this->assertNotNull($customer1);

        $workOrder1 = WorkOrder::where('branch_id', $branch1->id)->first();
        $this->assertNotNull($workOrder1);

        // User 2 logs in and tries to access User 1's customer/work order
        $response = $this->actingAs($user2)->get(route('customers.show', $customer1));
        $response->assertStatus(404);

        $responseWo = $this->actingAs($user2)->get(route('work-orders.show', $workOrder1));
        $responseWo->assertStatus(404);
    }

    public function test_master_can_view_dashboard_with_branch_specialization_badge_and_quick_actions(): void
    {
        $branch = Branch::where('name', 'Oto Klima Ustası')->first();
        $user = User::where('branch_id', $branch->id)->first();

        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Oto Klima Ustası');
        $response->assertSee('Klima Gaz Dolumu');
    }

    public function test_master_can_update_branch_settings(): void
    {
        $branch = Branch::where('name', 'Motor & Mekanik')->first();
        $user = User::where('branch_id', $branch->id)->first();

        $response = $this->actingAs($user)->put(route('settings.update'), [
            'name'             => 'Yılmaz Motor Revizyon Atölyesi',
            'title'            => 'Motor Rektifiye ve Silindir Kapak Revizyonu',
            'phone'            => '05329998877',
            'address'          => 'Sanayi 4. Blok No:12',
            'tax_office'       => 'Kadıköy',
            'tax_no'           => '9988776655',
            'receipt_footer'   => 'Revizyonlu motorlarımız 1 yıl garantilidir.',
            'whatsapp_message' => 'Sayın {musteri_adi}, aracınız hazır.',
            'currency'         => '₺',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $branch->refresh();
        $this->assertEquals('Yılmaz Motor Revizyon Atölyesi', $branch->name);
        $this->assertEquals('Motor Rektifiye ve Silindir Kapak Revizyonu', $branch->title);
        $this->assertEquals('05329998877', $branch->phone);
    }

    public function test_supplier_crud_operations_work_correctly(): void
    {
        $branch = Branch::where('name', 'Motor & Mekanik')->first();
        $user = User::where('branch_id', $branch->id)->first();

        // Create
        $response = $this->actingAs($user)->post(route('suppliers.store'), [
            'name'           => 'Mega Motor Parça A.Ş.',
            'contact_person' => 'Ahmet Usta',
            'phone'          => '05551234567',
            'email'          => 'siparis@megamotor.com',
            'address'        => 'Oto Sanayi C Blok',
            'tax_office'     => 'İkitelli',
            'tax_no'         => '1234567890',
            'notes'          => '30 gün vade',
        ]);

        $response->assertRedirect(route('suppliers.index'));
        $response->assertSessionHas('success');

        $supplier = Supplier::where('name', 'Mega Motor Parça A.Ş.')->first();
        $this->assertNotNull($supplier);
        $this->assertEquals($branch->id, $supplier->branch_id);

        // Index
        $responseIndex = $this->actingAs($user)->get(route('suppliers.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Mega Motor Parça A.Ş.');

        // Show
        $responseShow = $this->actingAs($user)->get(route('suppliers.show', $supplier));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('Mega Motor Parça A.Ş.');

        // Update
        $responseUpdate = $this->actingAs($user)->put(route('suppliers.update', $supplier), [
            'name'           => 'Mega Motor & Rektifiye A.Ş.',
            'contact_person' => 'Ahmet Demir',
            'phone'          => '05551234567',
        ]);
        $responseUpdate->assertRedirect(route('suppliers.index'));
        $supplier->refresh();
        $this->assertEquals('Mega Motor & Rektifiye A.Ş.', $supplier->name);

        // Delete
        $responseDelete = $this->actingAs($user)->delete(route('suppliers.destroy', $supplier));
        $responseDelete->assertRedirect(route('suppliers.index'));
        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
    }

    public function test_customer_with_vehicles_cannot_be_deleted(): void
    {
        $branch = Branch::where('name', 'Motor & Mekanik')->first();
        $user = User::where('branch_id', $branch->id)->first();

        $customer = Customer::where('branch_id', $branch->id)->first();
        $this->assertNotNull($customer);
        $this->assertTrue($customer->vehicles()->exists());

        $response = $this->actingAs($user)->delete(route('customers.destroy', $customer));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('customers', ['id' => $customer->id]);
    }

    public function test_work_order_prevents_negative_stock_exceeding_availability(): void
    {
        $branch = Branch::where('name', 'Motor & Mekanik')->first();
        $user = User::where('branch_id', $branch->id)->first();

        $vehicle = Vehicle::where('branch_id', $branch->id)->first();
        $part = Part::where('branch_id', $branch->id)->where('stock', '>', 0)->first();
        $this->assertNotNull($part);

        $excessiveQty = $part->stock + 100;

        $response = $this->actingAs($user)->post(route('work-orders.store'), [
            'vehicle_id' => $vehicle->id,
            'date'       => now()->format('Y-m-d'),
            'status'     => 'devam_ediyor',
            'discount'   => 0,
            'items'      => [
                [
                    'type'       => 'part',
                    'name'       => $part->name,
                    'part_id'    => $part->id,
                    'quantity'   => $excessiveQty,
                    'unit_price' => $part->sell_price,
                ]
            ]
        ]);

        $response->assertSessionHas('error');
    }
}
