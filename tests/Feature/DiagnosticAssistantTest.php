<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\DiagnosticSession;
use App\Models\DiagnosticSolution;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DiagnosticAssistantTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Branch $branch;
    protected Vehicle $vehicle;
    protected WorkOrder $workOrder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->branch = Branch::create(['name' => 'Kadıköy Servis']);

        $this->user = User::create([
            'name'      => 'Ahmet Usta',
            'email'     => 'usta@sanayi.test',
            'password'  => bcrypt('password'),
            'role'      => 'usta',
            'branch_id' => $this->branch->id,
        ]);

        $customer = Customer::create([
            'branch_id' => $this->branch->id,
            'user_id'   => $this->user->id,
            'name'      => 'Kemal Can',
            'phone'     => '05551234567',
        ]);

        $this->vehicle = Vehicle::create([
            'branch_id'   => $this->branch->id,
            'user_id'     => $this->user->id,
            'customer_id' => $customer->id,
            'plate'       => '34 XYZ 999',
            'brand'       => 'BMW',
            'model'       => '320d',
            'year'        => 2016,
            'engine'      => '2.0 TDI',
            'mileage'     => 185000,
        ]);

        $this->workOrder = WorkOrder::create([
            'branch_id'   => $this->branch->id,
            'user_id'     => $this->user->id,
            'vehicle_id'  => $this->vehicle->id,
            'date'        => today(),
            'mileage'     => 185000,
            'status'      => 'devam_ediyor',
            'total_parts' => 0,
            'total_labor' => 0,
        ]);

        WorkOrderItem::create([
            'work_order_id' => $this->workOrder->id,
            'type'          => 'part',
            'name'          => 'Hava Filtresi',
            'quantity'      => 1,
            'unit_price'    => 450,
            'total'         => 450,
        ]);
    }

    public function test_authenticated_user_can_view_diagnostic_form(): void
    {
        $response = $this->actingAs($this->user)->get(route('diagnostic.create', $this->workOrder));

        $response->assertStatus(200);
        $response->assertSee('SanayiPro Akıllı Arıza Asistanı');
        $response->assertSee('BMW');
        $response->assertSee('34 XYZ 999');
    }

    public function test_authenticated_user_can_view_standalone_diagnostic_form(): void
    {
        $response = $this->actingAs($this->user)->get(route('diagnostic.new'));

        $response->assertStatus(200);
        $response->assertSee('Yeni Araç Arıza Teşhisi');
        $response->assertSee('Teşhis Edilecek Araç');
    }

    public function test_vehicle_details_ajax_returns_json_with_history_and_parts(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('diagnostic.vehicle-details', $this->vehicle));

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('vehicle.plate', '34 XYZ 999');
        $response->assertJsonPath('vehicle.brand', 'BMW');
        $response->assertJsonPath('past_parts.0', 'Hava Filtresi');
    }

    public function test_user_can_save_diagnostic_session_with_symptoms_and_obd_codes(): void
    {
        $postData = [
            'vehicle_id'       => $this->vehicle->id,
            'complaint'        => 'Soğukta marş basıyor ama araç geç çalışıyor',
            'symptoms'         => ['Zor çalışıyor', 'Rölanti düzensizliği'],
            'custom_symptom'   => 'İlk çalıştırmada egzozdan beyaz duman',
            'checks_performed' => ['Akü kontrol edildi', 'Yakıt basıncı kontrol edildi'],
            'measurements'     => 'Akü voltajı: 12.1V, Marş anında: 10.1V',
            'previous_work'    => 'Hava filtresi yeni değişti',
            'usta_notes'       => 'Enjektör geri dönüşleri normal görünüyor',
            'obd_codes'        => [
                [
                    'code'        => 'P0670',
                    'description' => 'Glow Plug Control Module Circuit',
                    'system'      => 'Kızdırma',
                    'usta_note'   => 'Röle hattı ölçülecek',
                ]
            ],
        ];

        $response = $this->actingAs($this->user)
            ->post(route('diagnostic.store-standalone'), $postData);

        $session = DiagnosticSession::where('vehicle_id', $this->vehicle->id)->latest()->first();

        $this->assertNotNull($session);
        $this->assertCount(3, $session->symptoms);
        $this->assertCount(2, $session->checks_performed);
        $this->assertDatabaseHas('diagnostic_obd_codes', [
            'diagnostic_session_id' => $session->id,
            'code'                  => 'P0670',
        ]);

        $response->assertRedirect(route('diagnostic.session-analyze', $session));
    }

    public function test_diagnostic_ai_analysis_with_mocked_gemini_api_and_recommended_sequence(): void
    {
        config(['ai.provider' => 'gemini', 'ai.gemini_key' => 'fake-gemini-key']);

        $session = DiagnosticSession::create([
            'work_order_id'    => $this->workOrder->id,
            'vehicle_id'       => $this->vehicle->id,
            'user_id'          => $this->user->id,
            'complaint'        => 'Zor çalışma',
            'symptoms'         => ['Zor çalışıyor'],
            'checks_performed' => ['Akü kontrol edildi'],
        ]);

        // Mock Google Gemini API Response with recommended sequence
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                [
                                    'text' => json_encode([
                                        'summary' => 'Kızdırma bujileri veya rölesi arızası olasılığı yüksek.',
                                        'possible_causes' => [
                                            [
                                                'title'       => 'Kızdırma Bujisi Arızası',
                                                'probability' => 'Yüksek',
                                                'explanation' => 'Dizel araçlarda soğukta zor çalışmanın en yaygın sebebidir.',
                                                'checks'      => ['Buji direncini ölç', 'Röle voltajını kontrol et']
                                            ]
                                        ],
                                        'recommended_sequence' => [
                                            '1 → Akü voltajını kontrol et',
                                            '2 → OBD canlı verilerini incele',
                                            '3 → Kızdırma sistemini kontrol et'
                                        ],
                                        'recommended_checks'   => ['Akü voltaj testi', 'Yakıt basınç testi'],
                                        'warning'              => 'Röle kontaklarını aşırı yükten koruyun.',
                                        'disclaimer'           => 'Test amaçlı analiz.',
                                    ])
                                ]
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('diagnostic.session-analyze', $session));

        $response->assertStatus(200);
        $response->assertSee('Kızdırma Bujisi Arızası');
        $response->assertSee('ÖNERİLEN KONTROL SIRASI');

        $session->refresh();
        $this->assertTrue($session->is_analyzed);
        $this->assertEquals('analyzed', $session->status);
        $this->assertNotEmpty($session->recommended_sequence);
    }

    public function test_usta_can_submit_solution_and_add_to_knowledge_base(): void
    {
        $session = DiagnosticSession::create([
            'work_order_id' => $this->workOrder->id,
            'vehicle_id'    => $this->vehicle->id,
            'user_id'       => $this->user->id,
            'complaint'     => 'Zor çalışma',
            'symptoms'      => ['Zor çalışıyor'],
            'status'        => 'analyzed',
            'ai_result'     => ['summary' => 'Analiz özeti'],
        ]);

        $solutionData = [
            'root_cause'      => '2 ve 3 numaralı kızdırma bujileri patlak',
            'action_taken'    => '4 adet Bosch kızdırma bujisi takıldı ve tesisat temizlendi',
            'parts_replaced'  => 'Bosch Kızdırma Bujisi',
            'extra_note'      => 'Soket temas noktaları zımparalandı',
            'solution_status' => 'tamamen_cozuldu',
        ];

        $response = $this->actingAs($this->user)
            ->post(route('diagnostic.save-solution', $session), $solutionData);

        $response->assertRedirect(route('work-orders.show', $this->workOrder));

        $session->refresh();
        $this->assertEquals('solved', $session->feedback);
        $this->assertEquals('tamamen_cozuldu', $session->solution_status);
        $this->assertEquals('closed', $session->status);

        // Verify it was added to knowledge base
        $this->assertDatabaseHas('diagnostic_solutions', [
            'diagnostic_session_id' => $session->id,
            'vehicle_brand'         => 'BMW',
            'vehicle_model'         => '320d',
            'root_cause'            => '2 ve 3 numaralı kızdırma bujileri patlak',
            'solution_status'       => 'tamamen_cozuldu',
        ]);
    }

    public function test_usta_can_save_ai_feedback(): void
    {
        $session = DiagnosticSession::create([
            'vehicle_id' => $this->vehicle->id,
            'user_id'    => $this->user->id,
            'status'     => 'analyzed',
        ]);

        $response = $this->actingAs($this->user)
            ->postJson(route('diagnostic.save-ai-feedback', $session), [
                'ai_feedback' => 'helpful'
            ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('ai_feedback', 'helpful');

        $session->refresh();
        $this->assertEquals('helpful', $session->ai_feedback);
    }

    public function test_user_can_search_knowledge_base(): void
    {
        DiagnosticSolution::create([
            'vehicle_brand'  => 'BMW',
            'vehicle_model'  => '320d',
            'vehicle_year'   => 2016,
            'root_cause'     => 'Kızdırma bujisi arızası',
            'action_taken'   => 'Bujiler değişti',
            'parts_replaced' => ['Kızdırma Bujisi'],
            'result'         => 'Başarılı',
        ]);

        DiagnosticSolution::create([
            'vehicle_brand'  => 'Renault',
            'vehicle_model'  => 'Megane',
            'vehicle_year'   => 2019,
            'root_cause'     => 'EGR valfi tıkanıklığı',
            'action_taken'   => 'EGR temizlendi',
            'parts_replaced' => [],
            'result'         => 'Duman kesildi',
        ]);

        $response = $this->actingAs($this->user)->get(route('diagnostic.knowledge-base', ['search' => 'Kızdırma']));

        $response->assertStatus(200);
        $response->assertSee('BMW');
        $response->assertSee('Kızdırma bujisi arızası');
        $response->assertDontSee('Megane');
        $response->assertSee('Yeni AI Teşhisi');
    }
}
