<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Part;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Süper Admin
        User::create([
            'name'      => 'Süper Admin',
            'email'     => 'admin@sanayi.local',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'branch_id' => null,
        ]);

        // 2. Tüm Sanayi Dalları ve Atanmış Ustaları
        $branchDefinitions = [
            // 1. Motor ve Mekanik Grubu
            'Motor & Mekanik'            => ['usta' => 'Motorcu Selim Usta',     'email' => 'selim@motor.local',     'title' => 'Motor Revizyon'],
            'Şanzıman & Vites Kutusu'    => ['usta' => 'Şanzımancı Burak Usta',  'email' => 'burak@sanziman.local',  'title' => 'Otomatik & Manuel Şanzıman'],
            'Ön Düzen & Rot-Balans'      => ['usta' => 'Rotçu Cemal Usta',       'email' => 'cemal@rotduzen.local',  'title' => 'Ön Düzen & Amortisör'],
            'Fren Sistemleri'            => ['usta' => 'Frenci Kemal Usta',      'email' => 'kemal@fren.local',      'title' => 'Disk & Balata'],
            'Enjektör & Turbo'           => ['usta' => 'Turbocu Serkan Usta',    'email' => 'serkan@turbo.local',    'title' => 'Dizel Pompa & Turbo'],

            // 2. Elektrik ve Elektronik Grubu
            'Oto Elektrik'               => ['usta' => 'Elektrikçi Ahmet Usta',  'email' => 'ahmet@elektrik.local',  'title' => 'Tesisat & Marş / Şarj'],
            'Oto Beyin & Elektronik'     => ['usta' => 'Elektronikçi Murat Usta', 'email' => 'murat@elektronik.local','title' => 'ECU & Gösterge Kodlama'],
            'Oto Klima Ustası'           => ['usta' => 'Klimacı Erhan Usta',     'email' => 'erhan@klima.local',     'title' => 'Gaz Dolum & Kompresör'],
            'Akümülatör (Akü) Ustası'    => ['usta' => 'Akücü Dursun Usta',      'email' => 'dursun@aku.local',      'title' => 'Akü Test & Değişim'],

            // 3. Kaporta, Boya ve Dış Aksam
            'Kaporta Ustası'             => ['usta' => 'Kaportacı İsmail Usta',  'email' => 'ismail@kaporta.local',  'title' => 'Şase Düzeltme & Gövde Onarım'],
            'Oto Boyacı'                 => ['usta' => 'Boyacı Yusuf Usta',      'email' => 'yusuf@boya.local',      'title' => 'Fırın Boya & Lokal Boya'],
            'PDR (Boyasız Göçük) Ustası' => ['usta' => 'Göçükçü Sinan Usta',    'email' => 'sinan@pdr.local',       'title' => 'Dolu & Park Hasarı PDR'],
            'Plastik Tamircisi'          => ['usta' => 'Plastikçi Kazım Usta',  'email' => 'kazim@plastik.local',   'title' => 'Tampon & Far Kulağı Kaynağı'],
            'Oto Camcısı'                => ['usta' => 'Camcı Rıza Usta',        'email' => 'riza@cam.local',        'title' => 'Cam Değişimi & Kriko Tamiri'],

            // 4. İç Donanım, Kilit ve Güvenlik
            'Oto Döşeme Ustası'          => ['usta' => 'Döşemeci Cengiz Usta',   'email' => 'cengiz@doseme.local',   'title' => 'Koltuk & Tavan Kumaş Yenileme'],
            'Oto Kilit & İmmobilizer'    => ['usta' => 'Kilitçi Metin Usta',     'email' => 'metin@kilit.local',     'title' => 'Kontak, Anahtar & Çip Kodlama'],
            'Ses & Multimedya Ustası'    => ['usta' => 'Müzikçi Barış Usta',     'email' => 'baris@multimedya.local','title' => 'Ekran, Ses Sistemi & Kamera'],

            // 5. Yardımcı ve Tamamlayıcı Branşlar
            'Egzoz Ustası'               => ['usta' => 'Egzozcu Hakan Usta',     'email' => 'hakan@egzoz.local',     'title' => 'Susturucu, Katalizör & Kaynak'],
            'Radyatör & Petek Ustası'    => ['usta' => 'Radyatörcü Veli Usta',   'email' => 'veli@radyator.local',   'title' => 'Petek Temizleme & Soğutma'],
            'Torna & Kaynak Ustası'      => ['usta' => 'Tornacı Necati Usta',    'email' => 'necati@torna.local',    'title' => 'Kırık Civata & Diş Açma'],
            'Rot-Balans Lastikçisi'      => ['usta' => 'Lastikçi Orhan Usta',    'email' => 'orhan@lastik.local',    'title' => 'Lastik Değişim & Balans Ayarı'],

            // 6. Bakım ve Gaz Sistemleri
            'Periyodik Hızlı Bakım'      => ['usta' => 'Bakımcı Mehmet Usta',    'email' => 'mehmet@bakim.local',    'title' => 'Yağ & Filtre Değişimi'],
            'Oto LPG & Gaz Sistemleri'   => ['usta' => 'Gazcı Levent Usta',      'email' => 'levent@lpg.local',      'title' => 'LPG Tank & Enjektör'],
        ];

        $users = [];

        foreach ($branchDefinitions as $branchName => $data) {
            $branch = Branch::create(['name' => $branchName]);

            $user = User::create([
                'name'      => $data['usta'],
                'email'     => $data['email'],
                'password'  => Hash::make('password'),
                'role'      => 'branch_user',
                'branch_id' => $branch->id,
            ]);

            $users[$branchName] = $user;
        }

        // ========================================================
        // 1. FREN SİSTEMLERİ — Frencî Kemal Usta
        // ========================================================
        auth()->login($users['Fren Sistemleri']);
        $p1 = Part::create(['name' => 'Ön Fren Balatası (Ferodo)', 'code' => 'FRN-BLT-01', 'category' => 'Fren', 'buy_price' => 450, 'sell_price' => 750, 'stock' => 20, 'min_stock' => 4]);
        $p2 = Part::create(['name' => 'Hava Kanallı Fren Diski (Çift)', 'code' => 'FRN-DSK-02', 'category' => 'Fren', 'buy_price' => 1100, 'sell_price' => 1800, 'stock' => 10, 'min_stock' => 2]);
        $p3 = Part::create(['name' => 'DOT-4 Fren Hidroliği 500ml', 'code' => 'FRN-DOT4', 'category' => 'Fren', 'buy_price' => 120, 'sell_price' => 220, 'stock' => 25, 'min_stock' => 5]);
        $c1 = Customer::create(['name' => 'Mustafa Kaya', 'phone' => '05321112233', 'notes' => 'Ticari taksi']);
        $v1 = Vehicle::create(['customer_id' => $c1->id, 'plate' => '31APP435', 'brand' => 'Fiat', 'model' => 'Egea 1.3 MJet', 'year' => '2021', 'color' => 'Sarı']);
        $w1 = WorkOrder::create(['vehicle_id' => $v1->id, 'date' => now(), 'status' => 'tamamlandi', 'total_parts' => 2550, 'total_labor' => 600, 'discount' => 150]);
        WorkOrderItem::create(['work_order_id' => $w1->id, 'part_id' => $p1->id, 'type' => 'part', 'name' => 'Ön Fren Balatası', 'quantity' => 1, 'unit_price' => 750, 'total' => 750]);
        WorkOrderItem::create(['work_order_id' => $w1->id, 'part_id' => $p2->id, 'type' => 'part', 'name' => 'Hava Kanallı Ön Disk', 'quantity' => 1, 'unit_price' => 1800, 'total' => 1800]);
        WorkOrderItem::create(['work_order_id' => $w1->id, 'part_id' => null, 'type' => 'labor', 'name' => 'Ön Disk & Balata Değişimi', 'quantity' => 1, 'unit_price' => 600, 'total' => 600]);
        $w1->recalculate();

        // ========================================================
        // 2. MOTOR & MEKANİK — Motorcu Selim Usta
        // ========================================================
        auth()->login($users['Motor & Mekanik']);
        $pm1 = Part::create(['name' => 'Triger Seti & Devirdaim', 'code' => 'MTR-TRG-01', 'category' => 'Motor', 'buy_price' => 2200, 'sell_price' => 3500, 'stock' => 6, 'min_stock' => 2]);
        $pm2 = Part::create(['name' => 'Külbütör Kapak Contası', 'code' => 'MTR-CNT-02', 'category' => 'Motor', 'buy_price' => 200, 'sell_price' => 450, 'stock' => 12, 'min_stock' => 3]);
        $cm1 = Customer::create(['name' => 'Emre Çelik', 'phone' => '05448889900']);
        $vm1 = Vehicle::create(['customer_id' => $cm1->id, 'plate' => '34MC789', 'brand' => 'Volkswagen', 'model' => 'Golf 1.4 TSI', 'year' => '2017', 'color' => 'Gri']);
        $wm1 = WorkOrder::create(['vehicle_id' => $vm1->id, 'date' => now(), 'status' => 'devam_ediyor', 'total_parts' => 3950, 'total_labor' => 1500, 'discount' => 0]);
        WorkOrderItem::create(['work_order_id' => $wm1->id, 'part_id' => $pm1->id, 'type' => 'part', 'name' => 'Triger Seti & Devirdaim', 'quantity' => 1, 'unit_price' => 3500, 'total' => 3500]);
        WorkOrderItem::create(['work_order_id' => $wm1->id, 'part_id' => $pm2->id, 'type' => 'part', 'name' => 'Külbütör Kapak Contası', 'quantity' => 1, 'unit_price' => 450, 'total' => 450]);
        WorkOrderItem::create(['work_order_id' => $wm1->id, 'part_id' => null, 'type' => 'labor', 'name' => 'Triger Sente Ayarı & Montaj', 'quantity' => 1, 'unit_price' => 1500, 'total' => 1500]);
        $wm1->recalculate();

        // ========================================================
        // 3. ŞANZIMAN & VİTES — Şanzımancı Burak Usta
        // ========================================================
        auth()->login($users['Şanzıman & Vites Kutusu']);
        $ps1 = Part::create(['name' => 'Baskı Balata & Rulman Seti (Luk)', 'code' => 'SNZ-BSK-01', 'category' => 'Şanzıman', 'buy_price' => 3100, 'sell_price' => 4800, 'stock' => 5, 'min_stock' => 1]);
        $ps2 = Part::create(['name' => '75W-80 Şanzıman Yağı 1LT', 'code' => 'SNZ-YAG-02', 'category' => 'Şanzıman', 'buy_price' => 180, 'sell_price' => 320, 'stock' => 18, 'min_stock' => 4]);
        $cs1 = Customer::create(['name' => 'Turgut Aydın', 'phone' => '05332223344']);
        $vs1 = Vehicle::create(['customer_id' => $cs1->id, 'plate' => '06SNZ10', 'brand' => 'Renault', 'model' => 'Megane 1.5 dCi', 'year' => '2016', 'color' => 'Beyaz']);
        $ws1 = WorkOrder::create(['vehicle_id' => $vs1->id, 'date' => now(), 'status' => 'beklemede', 'total_parts' => 5120, 'total_labor' => 2000, 'discount' => 200]);
        WorkOrderItem::create(['work_order_id' => $ws1->id, 'part_id' => $ps1->id, 'type' => 'part', 'name' => 'Baskı Balata Seti', 'quantity' => 1, 'unit_price' => 4800, 'total' => 4800]);
        WorkOrderItem::create(['work_order_id' => $ws1->id, 'part_id' => null, 'type' => 'labor', 'name' => 'Şanzıman İndirme & Kavrama Montajı', 'quantity' => 1, 'unit_price' => 2000, 'total' => 2000]);
        $ws1->recalculate();

        // ========================================================
        // 4. OTO ELEKTRİK — Elektrikçi Ahmet Usta
        // ========================================================
        auth()->login($users['Oto Elektrik']);
        $pe1 = Part::create(['name' => '72Ah Varta AGM Akü', 'code' => 'ELK-AKU-72', 'category' => 'Elektrik', 'buy_price' => 2400, 'sell_price' => 3400, 'stock' => 10, 'min_stock' => 2]);
        $ce1 = Customer::create(['name' => 'Hasan Kaya', 'phone' => '05001112233']);
        $ve1 = Vehicle::create(['customer_id' => $ce1->id, 'plate' => '34HK123', 'brand' => 'Ford', 'model' => 'Focus', 'year' => '2015', 'color' => 'Beyaz']);
        $we1 = WorkOrder::create(['vehicle_id' => $ve1->id, 'date' => now(), 'status' => 'tamamlandi', 'total_parts' => 3400, 'total_labor' => 400, 'discount' => 0]);
        WorkOrderItem::create(['work_order_id' => $we1->id, 'part_id' => $pe1->id, 'type' => 'part', 'name' => '72Ah Varta AGM Akü', 'quantity' => 1, 'unit_price' => 3400, 'total' => 3400]);
        WorkOrderItem::create(['work_order_id' => $we1->id, 'part_id' => null, 'type' => 'labor', 'name' => 'Akü Montajı & Kodlama', 'quantity' => 1, 'unit_price' => 400, 'total' => 400]);
        $we1->recalculate();

        // ========================================================
        // 5. OTO KLİMA USTASI — Klimacı Erhan Usta
        // ========================================================
        auth()->login($users['Oto Klima Ustası']);
        $pk1 = Part::create(['name' => 'R134a Klima Gazı (Araç Dolumu)', 'code' => 'KLM-GAZ-134', 'category' => 'Klima', 'buy_price' => 350, 'sell_price' => 700, 'stock' => 30, 'min_stock' => 5]);
        $pk2 = Part::create(['name' => 'Anti-Bakteriyel Polen Filtresi', 'code' => 'KLM-FLT-01', 'category' => 'Klima', 'buy_price' => 90, 'sell_price' => 200, 'stock' => 20, 'min_stock' => 5]);
        $ck1 = Customer::create(['name' => 'Deniz Kurt', 'phone' => '05357778899']);
        $vk1 = Vehicle::create(['customer_id' => $ck1->id, 'plate' => '35KLM99', 'brand' => 'Honda', 'model' => 'Civic 1.6 i-VTEC', 'year' => '2019', 'color' => 'Kırmızı']);
        $wk1 = WorkOrder::create(['vehicle_id' => $vk1->id, 'date' => now(), 'status' => 'tamamlandi', 'total_parts' => 900, 'total_labor' => 350, 'discount' => 50]);
        WorkOrderItem::create(['work_order_id' => $wk1->id, 'part_id' => $pk1->id, 'type' => 'part', 'name' => 'R134a Gaz Basımı & Kaçak Testi', 'quantity' => 1, 'unit_price' => 700, 'total' => 700]);
        WorkOrderItem::create(['work_order_id' => $wk1->id, 'part_id' => $pk2->id, 'type' => 'part', 'name' => 'Polen Filtresi', 'quantity' => 1, 'unit_price' => 200, 'total' => 200]);
        WorkOrderItem::create(['work_order_id' => $wk1->id, 'part_id' => null, 'type' => 'labor', 'name' => 'Ozonla Klima Kanal Temizliği', 'quantity' => 1, 'unit_price' => 350, 'total' => 350]);
        $wk1->recalculate();

        // ========================================================
        // 6. PERİYODİK HIZLI BAKIM — Bakımcı Mehmet Usta
        // ========================================================
        auth()->login($users['Periyodik Hızlı Bakım']);
        $pb1 = Part::create(['name' => 'Castrol Edge 5W-30 Motor Yağı 4LT', 'code' => 'BKM-YAG-5W30', 'category' => 'Bakım', 'buy_price' => 850, 'sell_price' => 1350, 'stock' => 40, 'min_stock' => 10]);
        $pb2 = Part::create(['name' => 'Yağ Filtresi (Mann Filter)', 'code' => 'BKM-YF-01', 'category' => 'Bakım', 'buy_price' => 120, 'sell_price' => 250, 'stock' => 30, 'min_stock' => 5]);
        $cb1 = Customer::create(['name' => 'Ali Demir', 'phone' => '05559998877']);
        $vb1 = Vehicle::create(['customer_id' => $cb1->id, 'plate' => '06AD456', 'brand' => 'Toyota', 'model' => 'Corolla 1.6', 'year' => '2020', 'color' => 'Siyah']);
        $wb1 = WorkOrder::create(['vehicle_id' => $vb1->id, 'date' => now(), 'status' => 'tamamlandi', 'total_parts' => 1600, 'total_labor' => 350, 'discount' => 0]);
        WorkOrderItem::create(['work_order_id' => $wb1->id, 'part_id' => $pb1->id, 'type' => 'part', 'name' => 'Castrol 5W-30 Motor Yağı', 'quantity' => 1, 'unit_price' => 1350, 'total' => 1350]);
        WorkOrderItem::create(['work_order_id' => $wb1->id, 'part_id' => $pb2->id, 'type' => 'part', 'name' => 'Yağ Filtresi Değişimi', 'quantity' => 1, 'unit_price' => 250, 'total' => 250]);
        WorkOrderItem::create(['work_order_id' => $wb1->id, 'part_id' => null, 'type' => 'labor', 'name' => 'Periyodik Bakım İşçiliği', 'quantity' => 1, 'unit_price' => 350, 'total' => 350]);
        $wb1->recalculate();

        // ========================================================
        // 7. KAPORTA USTASI — Kaportacı İsmail Usta
        // ========================================================
        auth()->login($users['Kaporta Ustası']);
        $pkap1 = Part::create(['name' => 'Ön Tampon Demiri (Orijinal)', 'code' => 'KPR-TMP-01', 'category' => 'Kaporta', 'buy_price' => 1200, 'sell_price' => 1950, 'stock' => 4, 'min_stock' => 1]);
        $ckap1 = Customer::create(['name' => 'Kerem Gürbüz', 'phone' => '05386665544']);
        $vkap1 = Vehicle::create(['customer_id' => $ckap1->id, 'plate' => '34KPR88', 'brand' => 'Hyundai', 'model' => 'i20 1.4 MPI', 'year' => '2018', 'color' => 'Beyaz']);
        $wkap1 = WorkOrder::create(['vehicle_id' => $vkap1->id, 'date' => now(), 'status' => 'devam_ediyor', 'total_parts' => 1950, 'total_labor' => 3000, 'discount' => 200]);
        WorkOrderItem::create(['work_order_id' => $wkap1->id, 'part_id' => $pkap1->id, 'type' => 'part', 'name' => 'Ön Tampon Demiri', 'quantity' => 1, 'unit_price' => 1950, 'total' => 1950]);
        WorkOrderItem::create(['work_order_id' => $wkap1->id, 'part_id' => null, 'type' => 'labor', 'name' => 'Şase Çektirme & Ön Panel Düzeltme', 'quantity' => 1, 'unit_price' => 3000, 'total' => 3000]);
        $wkap1->recalculate();

        // ========================================================
        // 8. PLASTİK TAMİRCİSİ — Plastikçi Kazım Usta
        // ========================================================
        auth()->login($users['Plastik Tamircisi']);
        $pplas1 = Part::create(['name' => 'Plastik Tamir Teli & Takviye Zımbası', 'code' => 'PLS-TEL-01', 'category' => 'Plastik', 'buy_price' => 150, 'sell_price' => 300, 'stock' => 50, 'min_stock' => 10]);
        $cplas1 = Customer::create(['name' => 'Gökhan Yavuz', 'phone' => '05423334455']);
        $vplas1 = Vehicle::create(['customer_id' => $cplas1->id, 'plate' => '06PLS06', 'brand' => 'Opel', 'model' => 'Astra 1.6 CDTI', 'year' => '2015', 'color' => 'Gümüş Gri']);
        $wplas1 = WorkOrder::create(['vehicle_id' => $vplas1->id, 'date' => now(), 'status' => 'tamamlandi', 'total_parts' => 300, 'total_labor' => 850, 'discount' => 50]);
        WorkOrderItem::create(['work_order_id' => $wplas1->id, 'part_id' => $pplas1->id, 'type' => 'part', 'name' => 'Tampon Plastik Takviye Malzemesi', 'quantity' => 1, 'unit_price' => 300, 'total' => 300]);
        WorkOrderItem::create(['work_order_id' => $wplas1->id, 'part_id' => null, 'type' => 'labor', 'name' => 'Ön Tampon Kırık Kaynağı & Far Ayağı Onarımı', 'quantity' => 1, 'unit_price' => 850, 'total' => 850]);
        $wplas1->recalculate();

        // ========================================================
        // 9. OTO CAMCISI — Camcı Rıza Usta
        // ========================================================
        auth()->login($users['Oto Camcısı']);
        $pcam1 = Part::create(['name' => 'Sensörlü Lamine Ön Cam (Olimpia)', 'code' => 'CAM-ON-01', 'category' => 'Cam', 'buy_price' => 2800, 'sell_price' => 4200, 'stock' => 8, 'min_stock' => 2]);
        $pcam2 = Part::create(['name' => 'Poliüretan Cam Yapıştırıcı Mastik', 'code' => 'CAM-MST-02', 'category' => 'Cam', 'buy_price' => 180, 'sell_price' => 350, 'stock' => 30, 'min_stock' => 5]);
        $ccam1 = Customer::create(['name' => 'Onur Aslan', 'phone' => '05309991122']);
        $vcam1 = Vehicle::create(['customer_id' => $ccam1->id, 'plate' => '34CAM55', 'brand' => 'BMW', 'model' => '320i ED', 'year' => '2016', 'color' => 'Füme']);
        $wcam1 = WorkOrder::create(['vehicle_id' => $vcam1->id, 'date' => now(), 'status' => 'tamamlandi', 'total_parts' => 4550, 'total_labor' => 900, 'discount' => 0]);
        WorkOrderItem::create(['work_order_id' => $wcam1->id, 'part_id' => $pcam1->id, 'type' => 'part', 'name' => 'Sensörlü Ön Cam', 'quantity' => 1, 'unit_price' => 4200, 'total' => 4200]);
        WorkOrderItem::create(['work_order_id' => $wcam1->id, 'part_id' => $pcam2->id, 'type' => 'part', 'name' => 'Cam Yapıştırıcı ve Fitil', 'quantity' => 1, 'unit_price' => 350, 'total' => 350]);
        WorkOrderItem::create(['work_order_id' => $wcam1->id, 'part_id' => null, 'type' => 'labor', 'name' => 'Ön Cam Sökme & Yeni Cam Montajı', 'quantity' => 1, 'unit_price' => 900, 'total' => 900]);
        $wcam1->recalculate();

        // ========================================================
        // 10. OTO KİLİT & İMMOBİLİZER — Kilitçi Metin Usta
        // ========================================================
        auth()->login($users['Oto Kilit & İmmobilizer']);
        $pklt1 = Part::create(['name' => 'Renault Megane 4 Kartlı Akıllı Anahtar', 'code' => 'KLT-KRT-01', 'category' => 'Kilit', 'buy_price' => 600, 'sell_price' => 1250, 'stock' => 10, 'min_stock' => 2]);
        $cklt1 = Customer::create(['name' => 'Yasin Şahin', 'phone' => '05417770011']);
        $vklt1 = Vehicle::create(['customer_id' => $cklt1->id, 'plate' => '34KLT01', 'brand' => 'Renault', 'model' => 'Megane 4', 'year' => '2019', 'color' => 'Mavi']);
        $wklt1 = WorkOrder::create(['vehicle_id' => $vklt1->id, 'date' => now(), 'status' => 'tamamlandi', 'total_parts' => 1250, 'total_labor' => 500, 'discount' => 50]);
        WorkOrderItem::create(['work_order_id' => $wklt1->id, 'part_id' => $pklt1->id, 'type' => 'part', 'name' => 'Akıllı Kart Anahtar', 'quantity' => 1, 'unit_price' => 1250, 'total' => 1250]);
        WorkOrderItem::create(['work_order_id' => $wklt1->id, 'part_id' => null, 'type' => 'labor', 'name' => 'OBD Cihazı ile İmmobilizer Eşleştirme & Kodlama', 'quantity' => 1, 'unit_price' => 500, 'total' => 500]);
        $wklt1->recalculate();

        // ========================================================
        // 11. SES & MULTİMEDYA — Müzikçi Barış Usta
        // ========================================================
        auth()->login($users['Ses & Multimedya Ustası']);
        $pmed1 = Part::create(['name' => '9 inç Android 13 Multimedya Ekran (CarPlay/Android Auto)', 'code' => 'MED-AND-09', 'category' => 'Multimedya', 'buy_price' => 2600, 'sell_price' => 3900, 'stock' => 6, 'min_stock' => 1]);
        $pmed2 = Part::create(['name' => 'AHD Gece Görüşlü Geri Görüş Kamerası', 'code' => 'MED-KAM-02', 'category' => 'Multimedya', 'buy_price' => 250, 'sell_price' => 550, 'stock' => 15, 'min_stock' => 3]);
        $cmed1 = Customer::create(['name' => 'Furkan Güler', 'phone' => '05364445566']);
        $vmed1 = Vehicle::create(['customer_id' => $cmed1->id, 'plate' => '34SES99', 'brand' => 'Fiat', 'model' => 'Egea 1.4 Fire', 'year' => '2022', 'color' => 'Kurşun Gri']);
        $wmed1 = WorkOrder::create(['vehicle_id' => $vmed1->id, 'date' => now(), 'status' => 'tamamlandi', 'total_parts' => 4450, 'total_labor' => 800, 'discount' => 0]);
        WorkOrderItem::create(['work_order_id' => $wmed1->id, 'part_id' => $pmed1->id, 'type' => 'part', 'name' => '9 inç Android Ekran', 'quantity' => 1, 'unit_price' => 3900, 'total' => 3900]);
        WorkOrderItem::create(['work_order_id' => $wmed1->id, 'part_id' => $pmed2->id, 'type' => 'part', 'name' => 'AHD Geri Görüş Kamerası', 'quantity' => 1, 'unit_price' => 550, 'total' => 550]);
        WorkOrderItem::create(['work_order_id' => $wmed1->id, 'part_id' => null, 'type' => 'labor', 'name' => 'Kablo Tesisatı Çekimi & Teyp Montajı', 'quantity' => 1, 'unit_price' => 800, 'total' => 800]);
        $wmed1->recalculate();

        // ========================================================
        // 12. OTO DÖŞEME USTASI — Döşemeci Cengiz Usta
        // ========================================================
        auth()->login($users['Oto Döşeme Ustası']);
        $pdos1 = Part::create(['name' => 'Süngerli Tavan Kumaşı 3 Metre (Gri)', 'code' => 'DOS-TVN-01', 'category' => 'Döşeme', 'buy_price' => 400, 'sell_price' => 800, 'stock' => 20, 'min_stock' => 3]);
        $cdos1 = Customer::create(['name' => 'Serdar Akın', 'phone' => '05432221100']);
        $vdos1 = Vehicle::create(['customer_id' => $cdos1->id, 'plate' => '06DSM34', 'brand' => 'Volkswagen', 'model' => 'Passat B7', 'year' => '2013', 'color' => 'Siyah']);
        $wdos1 = WorkOrder::create(['vehicle_id' => $vdos1->id, 'date' => now(), 'status' => 'devam_ediyor', 'total_parts' => 800, 'total_labor' => 2200, 'discount' => 100]);
        WorkOrderItem::create(['work_order_id' => $wdos1->id, 'part_id' => $pdos1->id, 'type' => 'part', 'name' => 'Tavan Kumaşı ve Özel Tutkal', 'quantity' => 1, 'unit_price' => 800, 'total' => 800]);
        WorkOrderItem::create(['work_order_id' => $wdos1->id, 'part_id' => null, 'type' => 'labor', 'name' => 'Sarkmış Tavan İndirme, Kazıma & Kaplama İşçiliği', 'quantity' => 1, 'unit_price' => 2200, 'total' => 2200]);
        $wdos1->recalculate();

        // ========================================================
        // 13. EGZOZ USTASI — Egzozcu Hakan Usta
        // ========================================================
        auth()->login($users['Egzoz Ustası']);
        $pegz1 = Part::create(['name' => 'Paslanmaz Çelik Spiral Egzoz Körüğü', 'code' => 'EGZ-SPR-01', 'category' => 'Egzoz', 'buy_price' => 350, 'sell_price' => 650, 'stock' => 15, 'min_stock' => 3]);
        $cegz1 = Customer::create(['name' => 'Tolga Sarp', 'phone' => '05378881234']);
        $vegz1 = Vehicle::create(['customer_id' => $cegz1->id, 'plate' => '34EGZ44', 'brand' => 'Ford', 'model' => 'Courier 1.5 TDCi', 'year' => '2019', 'color' => 'Gümüş']);
        $wegz1 = WorkOrder::create(['vehicle_id' => $vegz1->id, 'date' => now(), 'status' => 'tamamlandi', 'total_parts' => 650, 'total_labor' => 500, 'discount' => 50]);
        WorkOrderItem::create(['work_order_id' => $wegz1->id, 'part_id' => $pegz1->id, 'type' => 'part', 'name' => 'Spiral Egzoz Körüğü', 'quantity' => 1, 'unit_price' => 650, 'total' => 650]);
        WorkOrderItem::create(['work_order_id' => $wegz1->id, 'part_id' => null, 'type' => 'labor', 'name' => 'Gazaltı Kaynak & Spiral Montajı', 'quantity' => 1, 'unit_price' => 500, 'total' => 500]);
        $wegz1->recalculate();

        // ========================================================
        // 14. RADYATÖR & PETEK USTASI — Radyatörcü Veli Usta
        // ========================================================
        auth()->login($users['Radyatör & Petek Ustası']);
        $prad1 = Part::create(['name' => 'Organik Kırmızı Antifriz -40C (3 Litre)', 'code' => 'RAD-ATF-03', 'category' => 'Soğutma', 'buy_price' => 220, 'sell_price' => 450, 'stock' => 30, 'min_stock' => 5]);
        $crad1 = Customer::create(['name' => 'Zafer Erdem', 'phone' => '05459990033']);
        $vrad1 = Vehicle::create(['customer_id' => $crad1->id, 'plate' => '06RAD12', 'brand' => 'Peugeot', 'model' => '301 1.6 HDi', 'year' => '2016', 'color' => 'Beyaz']);
        $wrad1 = WorkOrder::create(['vehicle_id' => $vrad1->id, 'date' => now(), 'status' => 'tamamlandi', 'total_parts' => 450, 'total_labor' => 750, 'discount' => 0]);
        WorkOrderItem::create(['work_order_id' => $wrad1->id, 'part_id' => $prad1->id, 'type' => 'part', 'name' => 'Organik Kırmızı Antifriz', 'quantity' => 1, 'unit_price' => 450, 'total' => 450]);
        WorkOrderItem::create(['work_order_id' => $wrad1->id, 'part_id' => null, 'type' => 'labor', 'name' => 'Özel Makineli Kalorifer Petek Temizleme', 'quantity' => 1, 'unit_price' => 750, 'total' => 750]);
        $wrad1->recalculate();

        // ========================================================
        // 15. TORNA & KAYNAK USTASI — Tornacı Necati Usta
        // ========================================================
        auth()->login($users['Torna & Kaynak Ustası']);
        $ptor1 = Part::create(['name' => 'M10 Çelik Helicoil Diş Kovanı', 'code' => 'TRN-HLC-10', 'category' => 'Torna', 'buy_price' => 50, 'sell_price' => 150, 'stock' => 100, 'min_stock' => 20]);
        $ctor1 = Customer::create(['name' => 'Cemil Bozkurt', 'phone' => '05391114477']);
        $vtor1 = Vehicle::create(['customer_id' => $ctor1->id, 'plate' => '34TRN80', 'brand' => 'Mercedes-Benz', 'model' => 'Sprinter 316 CDI', 'year' => '2018', 'color' => 'Beyaz']);
        $wtor1 = WorkOrder::create(['vehicle_id' => $vtor1->id, 'date' => now(), 'status' => 'tamamlandi', 'total_parts' => 150, 'total_labor' => 950, 'discount' => 0]);
        WorkOrderItem::create(['work_order_id' => $wtor1->id, 'part_id' => $ptor1->id, 'type' => 'part', 'name' => 'Çelik Diş Kovanı', 'quantity' => 1, 'unit_price' => 150, 'total' => 150]);
        WorkOrderItem::create(['work_order_id' => $wtor1->id, 'part_id' => null, 'type' => 'labor', 'name' => 'Motor Bloğundaki Kırık Saplamanın Çıkarılması & Yeni Diş Açılması', 'quantity' => 1, 'unit_price' => 950, 'total' => 950]);
        $wtor1->recalculate();

        // ========================================================
        // 16. ROT-BALANS LASTİKÇİSİ — Lastikçi Orhan Usta
        // ========================================================
        auth()->login($users['Rot-Balans Lastikçisi']);
        $plas1 = Part::create(['name' => '205/55 R16 Michelin Primacy 4 Lastik (Adet)', 'code' => 'LST-MCH-16', 'category' => 'Lastik', 'buy_price' => 2100, 'sell_price' => 2950, 'stock' => 24, 'min_stock' => 4]);
        $clas1 = Customer::create(['name' => 'Tarık Yaman', 'phone' => '05315556677']);
        $vlas1 = Vehicle::create(['customer_id' => $clas1->id, 'plate' => '34LST90', 'brand' => 'Skoda', 'model' => 'Octavia 1.6 TDI', 'year' => '2020', 'color' => 'Mavi']);
        $wlas1 = WorkOrder::create(['vehicle_id' => $vlas1->id, 'date' => now(), 'status' => 'tamamlandi', 'total_parts' => 5900, 'total_labor' => 400, 'discount' => 100]);
        WorkOrderItem::create(['work_order_id' => $wlas1->id, 'part_id' => $plas1->id, 'type' => 'part', 'name' => '205/55 R16 Michelin Lastik (2 Adet)', 'quantity' => 2, 'unit_price' => 2950, 'total' => 5900]);
        WorkOrderItem::create(['work_order_id' => $wlas1->id, 'part_id' => null, 'type' => 'labor', 'name' => 'Lastik Sökme-Takma, Balans Ayarı & Nitrojen Dolumu', 'quantity' => 1, 'unit_price' => 400, 'total' => 400]);
        $wlas1->recalculate();

        auth()->logout();
    }
}
