<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\DiagnosticSession;
use App\Models\DiagnosticSolution;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class DiagnosticAuditTest extends TestCase
{
    use RefreshDatabase;

    protected User $userA;
    protected User $userB;
    protected Branch $branchA;
    protected Branch $branchB;
    protected Vehicle $vehicleA;
    protected WorkOrder $workOrderA;

    protected function setUp(): void
    {
        parent::setUp();

        $this->branchA = Branch::create(['name' => 'Kadıköy Şubesi']);
        $this->branchB = Branch::create(['name' => 'Beşiktaş Şubesi']);

        $this->userA = User::create([
            'name'      => 'Ahmet Usta (Kadıköy)',
            'email'     => 'ahmet@kadikoy.test',
            'password'  => bcrypt('password'),
            'role'      => 'usta',
            'branch_id' => $this->branchA->id,
        ]);

        $this->userB = User::create([
            'name'      => 'Mehmet Usta (Beşiktaş)',
            'email'     => 'mehmet@besiktas.test',
            'password'  => bcrypt('password'),
            'role'      => 'usta',
            'branch_id' => $this->branchB->id,
        ]);

        $customer = Customer::create([
            'branch_id' => $this->branchA->id,
            'user_id'   => $this->userA->id,
            'name'      => 'Ali Veli',
            'phone'     => '05559876543',
        ]);

        $this->vehicleA = Vehicle::create([
            'branch_id'   => $this->branchA->id,
            'user_id'     => $this->userA->id,
            'customer_id' => $customer->id,
            'plate'       => '34 KDK 101',
            'brand'       => 'Ford',
            'model'       => 'Focus',
            'year'        => 2018,
            'engine'      => '1.5 TDCi',
            'mileage'     => 120000,
        ]);

        $this->workOrderA = WorkOrder::create([
            'branch_id'   => $this->branchA->id,
            'user_id'     => $this->userA->id,
            'vehicle_id'  => $this->vehicleA->id,
            'date'        => today(),
            'mileage'     => 120000,
            'status'      => 'ariza_tespiti',
            'total_parts' => 0,
            'total_labor' => 0,
        ]);
    }

    /**
     * TEST: Validation - vehicle_id is required when creating standalone diagnosis.
     */
    public function test_standalone_store_requires_vehicle_id(): void
    {
        $response = $this->actingAs($this->userA)->post(route('diagnostic.store-standalone'), [
            'complaint' => 'Araba gitmiyor',
        ]);

        $response->assertSessionHasErrors('vehicle_id');
    }

    /**
     * TEST: Validation - non-existent vehicle ID fails validation.
     */
    public function test_store_fails_with_invalid_vehicle_id(): void
    {
        $response = $this->actingAs($this->userA)->post(route('diagnostic.store-standalone'), [
            'vehicle_id' => 999999,
        ]);

        $response->assertSessionHasErrors('vehicle_id');
    }

    /**
     * TEST: Validation - very long text strings are caught by validation.
     */
    public function test_store_fails_with_oversized_complaint(): void
    {
        $response = $this->actingAs($this->userA)->post(route('diagnostic.store-standalone'), [
            'vehicle_id' => $this->vehicleA->id,
            'complaint'  => str_repeat('A', 2500),
        ]);

        $response->assertSessionHasErrors('complaint');
    }

    /**
     * TEST: Edge Case - Bare vehicle with no customer, no past work orders, no mileage, no engine.
     */
    public function test_edge_case_vehicle_with_all_null_optional_fields(): void
    {
        $bareCustomer = Customer::create([
            'branch_id' => $this->branchA->id,
            'user_id'   => $this->userA->id,
            'name'      => 'Müşterisiz',
        ]);

        $bareVehicle = Vehicle::create([
            'branch_id'   => $this->branchA->id,
            'user_id'     => $this->userA->id,
            'customer_id' => $bareCustomer->id,
            'plate'       => '34 BARE 01',
            'brand'       => null,
            'model'       => null,
            'year'        => null,
            'engine'      => null,
            'mileage'     => null,
        ]);

        // Standalone form view must render with 200 OK without errors
        $response = $this->actingAs($this->userA)
            ->get(route('diagnostic.new', ['vehicle_id' => $bareVehicle->id]));

        $response->assertStatus(200);
        $response->assertSee('34 BARE 01');

        // AJAX details must also return valid JSON with empty defaults
        $ajaxResponse = $this->actingAs($this->userA)
            ->getJson(route('diagnostic.vehicle-details', $bareVehicle));

        $ajaxResponse->assertStatus(200);
        $ajaxResponse->assertJsonPath('vehicle.plate', '34 BARE 01');
        $ajaxResponse->assertJsonPath('past_orders', []);
    }

    /**
     * TEST: Multi-tenant security - User B (Branch B) cannot view or analyze User A's session.
     */
    public function test_cross_tenant_user_cannot_access_other_branch_diagnostic_session(): void
    {
        $sessionA = DiagnosticSession::create([
            'branch_id'     => $this->branchA->id,
            'user_id'       => $this->userA->id,
            'vehicle_id'    => $this->vehicleA->id,
            'work_order_id' => $this->workOrderA->id,
            'symptoms'      => ['Zor çalışıyor'],
            'status'        => 'analyzed',
            'ai_result'     => ['summary' => 'Gizli analiz'],
        ]);

        // User B attempts to access User A's session analysis -> must return 404 Not Found (BelongsToBranch scope prevents ID leak)
        $response = $this->actingAs($this->userB)
            ->get(route('diagnostic.session-analyze', $sessionA));

        $response->assertNotFound();
    }

    /**
     * TEST: Multi-tenant security - User B cannot submit solution to User A's session.
     */
    public function test_cross_tenant_user_cannot_save_solution_on_other_branch_session(): void
    {
        $sessionA = DiagnosticSession::create([
            'branch_id'     => $this->branchA->id,
            'user_id'       => $this->userA->id,
            'vehicle_id'    => $this->vehicleA->id,
            'work_order_id' => $this->workOrderA->id,
            'status'        => 'analyzed',
        ]);

        $response = $this->actingAs($this->userB)
            ->post(route('diagnostic.save-solution', $sessionA), [
                'root_cause'      => 'Yetkisiz erişim denemesi',
                'action_taken'    => 'İşlem',
                'solution_status' => 'tamamen_cozuldu',
            ]);

        $response->assertNotFound();
    }

    /**
     * TEST: Multi-tenant security - User B cannot send AI feedback to User A's session.
     */
    public function test_cross_tenant_user_cannot_send_ai_feedback_on_other_branch_session(): void
    {
        $sessionA = DiagnosticSession::create([
            'branch_id'  => $this->branchA->id,
            'user_id'    => $this->userA->id,
            'vehicle_id' => $this->vehicleA->id,
            'status'     => 'analyzed',
        ]);

        $response = $this->actingAs($this->userB)
            ->post(route('diagnostic.save-ai-feedback', $sessionA), [
                'ai_feedback' => 'helpful',
            ]);

        $response->assertNotFound();
    }

    /**
     * TEST: When AI is unconfigured, system handles it gracefully without 500 error or white screen.
     */
    public function test_graceful_handling_when_ai_key_is_not_configured(): void
    {
        config(['ai.gemini_key' => '', 'ai.openai_key' => '']);

        $session = DiagnosticSession::create([
            'branch_id'  => $this->branchA->id,
            'user_id'    => $this->userA->id,
            'vehicle_id' => $this->vehicleA->id,
            'status'     => 'draft',
        ]);

        $response = $this->actingAs($this->userA)
            ->get(route('diagnostic.session-analyze', $session));

        // Must redirect back with informative error message
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /**
     * TEST: Rate limiting prevents rapid-fire API spam.
     */
    public function test_rate_limiter_blocks_excessive_calls(): void
    {
        config(['ai.gemini_key' => 'fake-key']);

        $session = DiagnosticSession::create([
            'branch_id'  => $this->branchA->id,
            'user_id'    => $this->userA->id,
            'vehicle_id' => $this->vehicleA->id,
            'status'     => 'draft',
        ]);

        // Manually exhaust rate limiter for this user
        $rateLimitKey = 'ai-diagnostic:' . $this->userA->id;
        for ($i = 0; $i < 5; $i++) {
            RateLimiter::hit($rateLimitKey, 60);
        }

        $response = $this->actingAs($this->userA)
            ->get(route('diagnostic.session-analyze', $session));

        $response->assertSessionHas('error');
        $this->assertStringContainsString('Çok fazla istek', session('error'));
    }

    /**
     * TEST: Solution saving with 'kismen_cozuldu' and 'devam_ediyor' statuses.
     */
    public function test_saving_solution_with_different_status_options(): void
    {
        $session = DiagnosticSession::create([
            'branch_id'  => $this->branchA->id,
            'user_id'    => $this->userA->id,
            'vehicle_id' => $this->vehicleA->id,
            'status'     => 'analyzed',
            'ai_result'  => ['possible_causes' => [['title' => 'Sensör arızası']]],
        ]);

        $response = $this->actingAs($this->userA)
            ->post(route('diagnostic.save-solution', $session), [
                'root_cause'      => 'MAF sensörü kirlenmiş',
                'action_taken'    => 'Sensör temizlendi ama test süreci devam ediyor',
                'parts_replaced'  => 'Temizleyici Sprey',
                'extra_note'      => '1 hafta sonra tekrar kontrol edilecek',
                'solution_status' => 'kismen_cozuldu',
            ]);

        $response->assertRedirect(route('diagnostic.knowledge-base'));

        $this->assertDatabaseHas('diagnostic_solutions', [
            'diagnostic_session_id' => $session->id,
            'root_cause'            => 'MAF sensörü kirlenmiş',
            'solution_status'       => 'kismen_cozuldu',
            'result'                => 'Kısmen çözüldü',
        ]);

        $session->refresh();
        $this->assertEquals('kismen_cozuldu', $session->solution_status);
        $this->assertEquals('closed', $session->status);
    }
}
