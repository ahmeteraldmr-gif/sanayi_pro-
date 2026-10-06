<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\DiagnosticObdCode;
use App\Models\DiagnosticSession;
use App\Models\DiagnosticSolution;
use App\Models\Part;
use App\Models\PartMovement;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use App\Models\WorkOrderTask;
use App\Services\DiagnosticAiService;
use App\Services\VehicleHealthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ComprehensiveRuntimeAuditTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $usta;
    protected User $otherUsta;
    protected Branch $branch;

    protected function setUp(): void
    {
        parent::setUp();

        $this->branch = Branch::create(['name' => 'Merkez Şube']);

        $this->admin = User::create([
            'name' => 'Admin Ahmet',
            'email' => 'admin@sanayipro.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->usta = User::create([
            'name' => 'Usta Mehmet',
            'email' => 'mehmet@sanayipro.com',
            'password' => Hash::make('password123'),
            'role' => 'usta',
            'branch_id' => $this->branch->id,
        ]);

        $this->otherUsta = User::create([
            'name' => 'Diğer Usta Ali',
            'email' => 'ali@sanayipro.com',
            'password' => Hash::make('password123'),
            'role' => 'usta',
        ]);
    }

    /** 1. GUEST ACCESS & AUTH GUARDS */
    public function test_guest_is_redirected_to_login_for_protected_routes(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/work-orders')->assertRedirect('/login');
        $this->get('/vehicles')->assertRedirect('/login');
        $this->get('/customers')->assertRedirect('/login');
        $this->get('/parts')->assertRedirect('/login');
        $this->get('/finance')->assertRedirect('/login');
        $this->get('/reports')->assertRedirect('/login');
        $this->get('/diagnostic/knowledge-base')->assertRedirect('/login');
        $this->get('/diagnostic/new')->assertRedirect('/login');
    }

    /** 2. PUBLIC LANDING PAGE */
    public function test_landing_page_loads_successfully_with_200(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('SanayiPro');
        $response->assertSee('Akıllı Arıza Asistanı');
    }

    /** 3. CUSTOMER & VEHICLE EDGE CASES (NULL FIELDS, INLINE CREATION, SEARCH) */
    public function test_vehicle_and_customer_lifecycle_with_null_and_edge_cases(): void
    {
        $this->actingAs($this->usta);

        // 3a. Inline new customer vehicle creation
        $res = $this->post(route('vehicles.store'), [
            'customer_mode'      => 'new',
            'new_customer_name'  => 'Hakan Demir',
            'new_customer_phone' => '0533 111 22 33',
            'plate'              => '34 HK 999',
            'brand'              => 'Ford',
            'model'              => 'Focus',
            'year'               => null, // Nullable
            'mileage'            => null, // Nullable
            'color'              => null, // Nullable
            'notes'              => null, // Nullable
        ]);
        $res->assertRedirect(route('vehicles.index'));
        $this->assertDatabaseHas('customers', ['name' => 'Hakan Demir']);
        $this->assertDatabaseHas('vehicles', ['plate' => '34 HK 999']);

        $vehicle = Vehicle::where('plate', '34 HK 999')->first();

        // 3b. Vehicle Health Report with 0 past services
        $healthService = new VehicleHealthService();
        $report = $healthService->generateHealthReport($vehicle);
        $this->assertIsArray($report);
        $this->assertArrayHasKey('motor', $report);
        $this->assertArrayHasKey('overall_score', $report);

        // 3c. Vehicle details JSON route (used in Diagnostic modal)
        $detailsRes = $this->getJson(route('diagnostic.vehicle-details', $vehicle));
        $detailsRes->assertStatus(200);
        $detailsRes->assertJsonPath('success', true);
        $detailsRes->assertJsonPath('vehicle.plate', '34 HK 999');

        // 3d. Vehicle search route with matching and non-matching plates
        $searchMatch = $this->getJson(route('vehicles.search', ['plate' => '34 HK 999']));
        $searchMatch->assertStatus(200);
        $searchMatch->assertJsonPath('found', true);
        $searchMatch->assertJsonPath('customer.name', 'Hakan Demir');

        $searchNone = $this->getJson(route('vehicles.search', ['plate' => '99 XYZ 999']));
        $searchNone->assertStatus(200);
        $searchNone->assertJsonPath('found', false);

        // 3e. QuickSearch route with short & long queries
        $quickShort = $this->getJson(route('vehicles.quick-search', ['q' => '3']));
        $quickShort->assertStatus(200)->assertExactJson([]);

        $quickLong = $this->getJson(route('vehicles.quick-search', ['q' => 'Focus']));
        $quickLong->assertStatus(200);
        $this->assertNotEmpty($quickLong->json());
    }

    /** 4. WORK ORDER FULL LIFECYCLE (STOCK DEDUCTION, TASKS, TIMELINE, NOTIFICATIONS, RECALCULATE) */
    public function test_work_order_full_lifecycle(): void
    {
        $this->actingAs($this->usta);

        $customer = Customer::create(['name' => 'Caner Erkin', 'phone' => '0544 555 66 77']);
        $vehicle = Vehicle::create([
            'customer_id' => $customer->id,
            'plate'       => '34 BJK 1903',
            'brand'       => 'Mercedes',
            'model'       => 'C200',
            'mileage'     => 120000,
        ]);

        $part = Part::create([
            'name'       => 'Castrol 5W-30 Motor Yağı',
            'buy_price'  => 500,
            'sell_price' => 850,
            'stock'      => 10,
            'min_stock'  => 2,
        ]);

        // 4a. Store Work Order with items
        $storeRes = $this->post(route('work-orders.store'), [
            'vehicle_id' => $vehicle->id,
            'date'       => today()->toDateString(),
            'status'     => 'arac_kabul',
            'notes'      => 'Periyodik bakım',
            'discount'   => 50,
            'items'      => [
                [
                    'type'       => 'part',
                    'name'       => 'Castrol 5W-30 Motor Yağı',
                    'part_id'    => $part->id,
                    'quantity'   => 4,
                    'unit_price' => 850,
                ],
                [
                    'type'       => 'labor',
                    'name'       => 'Yağ ve Filtre Değişim İşçiliği',
                    'part_id'    => null,
                    'quantity'   => 1,
                    'unit_price' => 600,
                ],
            ],
        ]);
        $storeRes->assertRedirect(route('work-orders.index'));

        // Part stock should have decremented from 10 to 6
        $part->refresh();
        $this->assertEquals(6, $part->stock);

        $workOrder = WorkOrder::where('vehicle_id', $vehicle->id)->first();
        $this->assertNotNull($workOrder);
        $this->assertEquals(3400, $workOrder->total_parts);
        $this->assertEquals(600, $workOrder->total_labor);
        $this->assertEquals(3950, $workOrder->grand_total); // 3400 + 600 - 50 = 3950

        // 4b. Add Task, Toggle Task, Delete Task
        $taskRes = $this->postJson(route('work-orders.tasks.add', $workOrder), ['title' => 'Yağ filtresini sök']);
        $taskRes->assertStatus(200)->assertJsonPath('success', true);
        $task = WorkOrderTask::where('work_order_id', $workOrder->id)->first();

        $toggleRes = $this->patchJson(route('work-order-tasks.toggle', $task));
        $toggleRes->assertStatus(200)->assertJsonPath('is_completed', true);

        $delTaskRes = $this->deleteJson(route('work-order-tasks.delete', $task));
        $delTaskRes->assertStatus(200)->assertJsonPath('success', true);

        // 4c. Notification Preview & Logging
        $notifPreview = $this->getJson(route('work-orders.notification-preview', ['workOrder' => $workOrder, 'status' => 'tamamlandi']));
        $notifPreview->assertStatus(200)->assertJsonPath('success', true);
        $this->assertStringContainsString('34 BJK 1903', $notifPreview->json('message'));

        $logNotifRes = $this->postJson(route('work-orders.log-notification', $workOrder), [
            'channel'    => 'whatsapp',
            'status_key' => 'tamamlandi',
            'message'    => 'Aracınız hazır.',
        ]);
        $logNotifRes->assertStatus(200)->assertJsonPath('success', true);

        // 4d. Status update AJAX
        $statusRes = $this->patchJson(route('work-orders.status', $workOrder), ['status' => 'odendi']);
        $statusRes->assertStatus(200)->assertJsonPath('status', 'odendi');

        // 4e. Print view
        $printRes = $this->get(route('work-orders.print', $workOrder));
        $printRes->assertStatus(200)->assertSee('34 BJK 1903');
    }

    /** 5. DIAGNOSTIC AI ASSISTANT FULL CYCLE (STANDALONE & WORK ORDER, FALLBACK WITHOUT API KEY, KNOWLEDGE BASE) */
    public function test_diagnostic_ai_assistant_flow(): void
    {
        $this->actingAs($this->usta);

        $customer = Customer::create(['name' => 'Kerem Aktürkoğlu', 'phone' => '0532 999 88 77']);
        $vehicle = Vehicle::create([
            'customer_id' => $customer->id,
            'plate'       => '34 GS 1905',
            'brand'       => 'BMW',
            'model'       => '320d',
            'year'        => '2020',
            'engine'      => '2.0 Diesel',
            'mileage'     => 145000,
        ]);

        // 5a. Knowledge base page loads
        $kbRes = $this->get(route('diagnostic.knowledge-base'));
        $kbRes->assertStatus(200)->assertSee('Arıza Bilgi Bankası');

        // 5b. Create standalone diagnostic session
        $storeRes = $this->post(route('diagnostic.store-standalone'), [
            'vehicle_id'       => $vehicle->id,
            'mileage'          => 145000,
            'complaint'        => 'Araç sabahları geç çalışıyor ve tekleme yapıyor.',
            'symptoms'         => ['Zor çalışma', 'Tekleme'],
            'custom_symptom'   => 'Siyah duman atma',
            'checks_performed' => ['Akü voltajı ölçüldü (12.4V)', 'Sigortalar kontrol edildi'],
            'measurements'     => 'Marş basma voltajı: 9.8V',
            'previous_work'    => 'Enjektörler 20.000 km önce temizlenmişti',
            'usta_notes'       => 'Kızdırma rölesi şüpheli',
            'obd_codes'        => [
                [
                    'code'        => 'P0380',
                    'description' => 'Kızdırma Bujisi Devre A Hatası',
                    'system'      => 'Motor / Ateşleme',
                    'usta_note'   => 'Devre açık ikazı',
                ],
            ],
        ]);

        $session = DiagnosticSession::where('vehicle_id', $vehicle->id)->first();
        $this->assertNotNull($session);
        $this->assertCount(3, $session->symptoms);
        $this->assertDatabaseHas('diagnostic_obd_codes', ['code' => 'P0380']);

        $storeRes->assertRedirect(route('diagnostic.session-analyze', $session));

        // 5c. Without configured API key, analyze redirect gives graceful warning message (No 500 error!)
        config(['ai.gemini_key' => null, 'ai.openai_key' => null]);
        $analyzeRes = $this->get(route('diagnostic.session-analyze', $session));
        $analyzeRes->assertRedirect();
        $analyzeRes->assertSessionHas('error'); // Controlled "AI servisi yapılandırılmamış" message

        // 5d. Save solution & check knowledge base
        $solutionRes = $this->post(route('diagnostic.save-solution', $session), [
            'root_cause'      => 'Kızdırma bujisi rölesi arızası',
            'action_taken'    => 'Kızdırma rölesi yenisiyle değiştirildi ve tesisat kontrol edildi.',
            'parts_replaced'  => ['Kızdırma Bujisi Rölesi', '1 Adet 70A Sigorta'],
            'extra_note'      => 'Sorun giderildi, marş süresi 1 saniyeye indi.',
            'solution_status' => 'tamamen_cozuldu',
        ]);
        $solutionRes->assertRedirect(route('diagnostic.knowledge-base'));

        $this->assertDatabaseHas('diagnostic_solutions', [
            'diagnostic_session_id' => $session->id,
            'vehicle_brand'         => 'BMW',
            'root_cause'            => 'Kızdırma bujisi rölesi arızası',
        ]);

        // 5e. Knowledge base search finds the newly recorded solution
        $kbSearchRes = $this->get(route('diagnostic.knowledge-base', ['search' => 'Kızdırma']));
        $kbSearchRes->assertStatus(200)->assertSee('BMW');

        // 5f. Send AI feedback
        $feedbackRes = $this->postJson(route('diagnostic.save-ai-feedback', $session), [
            'ai_feedback' => 'helpful',
        ]);
        $feedbackRes->assertStatus(200)->assertJsonPath('success', true);

        // 5g. Tenant isolation check: Other usta cannot manipulate this session
        $this->actingAs($this->otherUsta);
        $unauthorizedAnalyze = $this->post(route('diagnostic.save-solution', $session), [
            'root_cause'      => 'Yetkisiz deneme',
            'action_taken'    => 'Yetkisiz',
            'solution_status' => 'tamamen_cozuldu',
        ]);
        $this->assertTrue(in_array($unauthorizedAnalyze->status(), [403, 404]));
    }

    /** 6. FINANCE, REPORTS, APPOINTMENTS, SUPPLIERS, MASTERS, SETTINGS */
    public function test_finance_reports_and_management_modules(): void
    {
        $this->actingAs($this->admin);

        // 6a. Finance module with all 4 tabs
        $financeGelir = $this->get(route('finance.index', ['tab' => 'gelir']));
        $financeGelir->assertStatus(200);

        $financeGider = $this->get(route('finance.index', ['tab' => 'gider']));
        $financeGider->assertStatus(200);

        $financeTahsilat = $this->get(route('finance.index', ['tab' => 'tahsilatlar']));
        $financeTahsilat->assertStatus(200);

        $financeBorclar = $this->get(route('finance.index', ['tab' => 'borclar']));
        $financeBorclar->assertStatus(200);

        // 6b. Reports module
        $reportRes = $this->get(route('reports.index', ['month' => now()->month, 'year' => now()->year]));
        $reportRes->assertStatus(200)->assertSee('Dönem');

        // 6c. Appointments module
        $appIndexRes = $this->get(route('appointments.index'));
        $appIndexRes->assertStatus(200);

        $appStoreRes = $this->post(route('appointments.store'), [
            'title'      => 'Periyodik Bakım Randevusu',
            'start_time' => now()->addDay()->format('Y-m-d 10:00:00'),
            'status'     => 'randevu',
            'notes'      => 'Müşteri sabah bırakacak',
        ]);
        $appStoreRes->assertRedirect();
        $this->assertDatabaseHas('appointments', ['title' => 'Periyodik Bakım Randevusu']);

        // 6d. Suppliers module
        $supplierRes = $this->get(route('suppliers.index'));
        $supplierRes->assertStatus(200);

        // 6e. Masters & Role Update
        $mastersRes = $this->get(route('masters.index'));
        $mastersRes->assertStatus(200);

        $roleUpdateRes = $this->patch(route('masters.update-role', $this->usta), ['role' => 'manager']);
        $roleUpdateRes->assertRedirect();
        $this->assertEquals('manager', $this->usta->fresh()->role);

        // 6f. Settings module
        $settingsRes = $this->get(route('settings.index'));
        $settingsRes->assertStatus(200);
    }
}
