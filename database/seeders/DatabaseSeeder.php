<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Part;
use App\Models\PartMovement;
use App\Models\Supplier;
use App\Models\User;
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
        $admin = User::firstOrCreate(
            ['email' => 'admin@sanayi.local'],
            [
                'name'      => 'Süper Admin',
                'password'  => Hash::make('password'),
                'role'      => 'admin',
                'branch_id' => null,
            ]
        );

        // 2. 23 Sanayi Branşı Tanımları
        $branchDefinitions = [
            'Motor & Mekanik' => [
                'usta' => 'Motorcu Selim Usta', 'email' => 'selim@motor.local', 'title' => 'Motor Revizyon & Ağır Bakım',
                'desc' => 'Benzinli ve dizel motor rektifiye, triger, üst kapak conta ve performans mekanik işlemleri.',
                'parts' => [
                    ['name' => 'Triger Seti & Devirdaim (Gates)', 'code' => 'MTR-TRG-01', 'cat' => 'Motor', 'buy' => 2200, 'sell' => 3500, 'stock' => 6, 'min' => 2],
                    ['name' => 'Külbütör Kapak Contası (Elring)',  'code' => 'MTR-CNT-02', 'cat' => 'Motor', 'buy' => 250,  'sell' => 500,  'stock' => 12, 'min' => 3],
                    ['name' => '5W-30 Tam Sentetik Motor Yağı 4LT', 'code' => 'MTR-YAG-03', 'cat' => 'Motor', 'buy' => 650,  'sell' => 1100, 'stock' => 24, 'min' => 6],
                ],
                'customer' => ['name' => 'Emre Çelik', 'phone' => '05448889900'],
                'vehicle'  => ['plate' => '34 MC 789', 'brand' => 'Volkswagen', 'model' => 'Golf 1.4 TSI', 'year' => '2018', 'color' => 'Gri', 'km' => 142000],
                'order'    => ['status' => 'devam_ediyor', 'discount' => 150, 'labor_name' => 'Triger Sente Ayarı & Conta Montajı', 'labor_price' => 1800],
            ],
            'Şanzıman & Vites Kutusu' => [
                'usta' => 'Şanzımancı Burak Usta', 'email' => 'burak@sanziman.local', 'title' => 'Otomatik & Manuel Şanzıman',
                'desc' => 'DSG, EDC, Tork Konvertörlü ve Manuel şanzıman revizyonu, kavrama değişimi.',
                'parts' => [
                    ['name' => 'Baskı Balata & Rulman Seti (Luk)', 'code' => 'SNZ-BSK-01', 'cat' => 'Şanzıman', 'buy' => 3100, 'sell' => 4900, 'stock' => 5, 'min' => 1],
                    ['name' => '75W-80 Şanzıman Yağı 1LT (Motul)', 'code' => 'SNZ-YAG-02', 'cat' => 'Şanzıman', 'buy' => 180,  'sell' => 340,  'stock' => 16, 'min' => 4],
                ],
                'customer' => ['name' => 'Turgut Aydın', 'phone' => '05332223344'],
                'vehicle'  => ['plate' => '06 SNZ 10', 'brand' => 'Renault', 'model' => 'Megane 1.5 dCi', 'year' => '2017', 'color' => 'Beyaz', 'km' => 185000],
                'order'    => ['status' => 'beklemede', 'discount' => 200, 'labor_name' => 'Şanzıman İndirme & Kavrama Montajı', 'labor_price' => 2500],
            ],
            'Ön Düzen & Rot-Balans' => [
                'usta' => 'Rotçu Cemal Usta', 'email' => 'cemal@rotduzen.local', 'title' => 'Ön Düzen, Rot & Amortisör',
                'desc' => '3D lazerli rot ayarı, dinamik balans, salıncak, z-rot, rot başı ve amortisör yenileme.',
                'parts' => [
                    ['name' => 'Ön Gazlı Amortisör (Monroe - Çift)', 'code' => 'ROT-AMR-01', 'cat' => 'Ön Düzen', 'buy' => 1800, 'sell' => 2900, 'stock' => 8,  'min' => 2],
                    ['name' => 'Sağ / Sol Rotil Takımı (Lemförder)',  'code' => 'ROT-RTL-02', 'cat' => 'Ön Düzen', 'buy' => 350,  'sell' => 600,  'stock' => 14, 'min' => 4],
                    ['name' => 'Ön Salıncak Arka Burcu (Adet)',       'code' => 'ROT-BRC-03', 'cat' => 'Ön Düzen', 'buy' => 150,  'sell' => 280,  'stock' => 20, 'min' => 4],
                ],
                'customer' => ['name' => 'Serdar Özkan', 'phone' => '05301234567'],
                'vehicle'  => ['plate' => '34 ROT 44', 'brand' => 'Toyota', 'model' => 'Corolla 1.6', 'year' => '2019', 'color' => 'Gümüş Gri', 'km' => 96000],
                'order'    => ['status' => 'tamamlandi', 'discount' => 100, 'labor_name' => '3D Ön/Arka Rot Ayarı & Amortisör Montajı', 'labor_price' => 950],
            ],
            'Fren Sistemleri' => [
                'usta' => 'Frenci Kemal Usta', 'email' => 'kemal@fren.local', 'title' => 'Fren Sistemleri, Disk & Balata',
                'desc' => 'Hava kanallı disk, seramik balata, ABS fren merkezi, kaliper revizyonu ve hidrolik dolumu.',
                'parts' => [
                    ['name' => 'Ön Fren Balatası (Ferodo Premier)', 'code' => 'FRN-BLT-01', 'cat' => 'Fren', 'buy' => 450,  'sell' => 780,  'stock' => 18, 'min' => 4],
                    ['name' => 'Hava Kanallı Fren Diski (Brembo Çift)', 'code' => 'FRN-DSK-02', 'cat' => 'Fren', 'buy' => 1150, 'sell' => 1950, 'stock' => 10, 'min' => 2],
                    ['name' => 'DOT-4 Fren Hidroliği 500ml (Ate)',      'code' => 'FRN-DOT4',   'cat' => 'Fren', 'buy' => 130,  'sell' => 240,  'stock' => 22, 'min' => 5],
                ],
                'customer' => ['name' => 'Mustafa Kaya', 'phone' => '05321112233'],
                'vehicle'  => ['plate' => '31 APP 435', 'brand' => 'Fiat', 'model' => 'Egea 1.3 MJet', 'year' => '2021', 'color' => 'Sarı', 'km' => 110000],
                'order'    => ['status' => 'odendi', 'discount' => 120, 'labor_name' => 'Ön Disk & Balata Değişimi + Hidrolik', 'labor_price' => 700],
            ],
            'Enjektör & Turbo' => [
                'usta' => 'Turbocu Serkan Usta', 'email' => 'serkan@turbo.local', 'title' => 'Dizel Enjektör, Pompa & Turbo',
                'desc' => 'Common rail piezo enjektör testi, değişken geometrili turbo revizyonu, aktüatör kalibrasyonu.',
                'parts' => [
                    ['name' => 'Common Rail Enjektör Memesi (Bosch)', 'code' => 'TRB-ENJ-01', 'cat' => 'Enjektör', 'buy' => 850,  'sell' => 1400, 'stock' => 12, 'min' => 4],
                    ['name' => 'Garrett Turbo Şarj Kartuşu (GT1749V)', 'code' => 'TRB-KRT-02', 'cat' => 'Turbo',    'buy' => 3800, 'sell' => 5900, 'stock' => 4,  'min' => 1],
                    ['name' => 'Dizel Yakıt Filtresi (Mann Filter)',   'code' => 'TRB-FLT-03', 'cat' => 'Filtre',   'buy' => 240,  'sell' => 420,  'stock' => 15, 'min' => 3],
                ],
                'customer' => ['name' => 'Yasin Bulut', 'phone' => '05364445566'],
                'vehicle'  => ['plate' => '16 TRB 88', 'brand' => 'Volkswagen', 'model' => 'Passat 2.0 TDI', 'year' => '2016', 'color' => 'Siyah', 'km' => 215000],
                'order'    => ['status' => 'devam_ediyor', 'discount' => 250, 'labor_name' => 'Enjektör Tezgah Testi & Turbo Revizyonu', 'labor_price' => 3200],
            ],
            'Oto Elektrik' => [
                'usta' => 'Elektrikçi Ahmet Usta', 'email' => 'ahmet@elektrik.local', 'title' => 'Tesisat, Marş Motoru & Şarj',
                'desc' => 'Oto elektrik tesisat tamiri, marş motoru kömür/otomatik değişimi, şarj dinamosu tamiri.',
                'parts' => [
                    ['name' => '72Ah Varta AGM Akü (Start-Stop)', 'code' => 'ELK-AKU-72', 'cat' => 'Elektrik', 'buy' => 2500, 'sell' => 3600, 'stock' => 9,  'min' => 2],
                    ['name' => 'Marş Motoru Kömür Takımı & Burç',  'code' => 'ELK-MRS-02', 'cat' => 'Elektrik', 'buy' => 140,  'sell' => 300,  'stock' => 14, 'min' => 3],
                    ['name' => 'H7 Photon LED Far Ampulü Takım',   'code' => 'ELK-AMP-03', 'cat' => 'Aydınlatma','buy' => 450,  'sell' => 850,  'stock' => 10, 'min' => 2],
                ],
                'customer' => ['name' => 'Hasan Kaya', 'phone' => '05001112233'],
                'vehicle'  => ['plate' => '34 HK 123', 'brand' => 'Ford', 'model' => 'Focus 1.5 TDCi', 'year' => '2016', 'color' => 'Beyaz', 'km' => 164000],
                'order'    => ['status' => 'tamamlandi', 'discount' => 100, 'labor_name' => 'Marş Motoru Sökme & Kömür Değişimi', 'labor_price' => 850],
            ],
            'Oto Beyin & Elektronik' => [
                'usta' => 'Elektronikçi Murat Usta', 'email' => 'murat@elektronik.local', 'title' => 'ECU Beyin, Modül & Kodlama',
                'desc' => 'Motor kontrol ünitesi (ECU), BSI/BCM gövde beyni onarımı, sensör teşhisi ve anahtar kodlama.',
                'parts' => [
                    ['name' => 'Krank Mili Pozisyon Sensörü (Bosch)', 'code' => 'ECU-SNS-01', 'cat' => 'Elektronik', 'buy' => 420,  'sell' => 750,  'stock' => 8,  'min' => 2],
                    ['name' => 'Geniş Bant Oksijen (Lambda) Sensörü', 'code' => 'ECU-LMB-02', 'cat' => 'Elektronik', 'buy' => 950,  'sell' => 1600, 'stock' => 5,  'min' => 1],
                    ['name' => 'Motor Beyin Soket Tamir Kiti',       'code' => 'ECU-SKT-03', 'cat' => 'Elektronik', 'buy' => 200,  'sell' => 450,  'stock' => 10, 'min' => 2],
                ],
                'customer' => ['name' => 'Bora Yılmaz', 'phone' => '05389998877'],
                'vehicle'  => ['plate' => '34 ECU 01', 'brand' => 'Audi', 'model' => 'A4 2.0 TFSI', 'year' => '2015', 'color' => 'Füme', 'km' => 178000],
                'order'    => ['status' => 'tamamlandi', 'discount' => 150, 'labor_name' => 'ECU Arıza Tespiti & Sensör Kalibrasyonu', 'labor_price' => 1500],
            ],
            'Oto Klima Ustası' => [
                'usta' => 'Klimacı Erhan Usta', 'email' => 'erhan@klima.local', 'title' => 'Klima Gaz Dolumu & Kompresör',
                'desc' => 'R134a/R1234yf gaz basımı, azotlu kaçak testi, klima kompresör kasnağı ve petek dezenfeksiyonu.',
                'parts' => [
                    ['name' => 'R134a Klima Gazı Tüpü (Dolum Başı)', 'code' => 'KLM-GAZ-134', 'cat' => 'Klima', 'buy' => 380, 'sell' => 850, 'stock' => 25, 'min' => 5],
                    ['name' => 'Anti-Bakteriyel Karbonlu Polen Filtresi', 'code' => 'KLM-FLT-01', 'cat' => 'Klima', 'buy' => 110, 'sell' => 260, 'stock' => 18, 'min' => 4],
                    ['name' => 'Klima Kompresör Kasnak Rulmanı',         'code' => 'KLM-RLM-03', 'cat' => 'Klima', 'buy' => 320, 'sell' => 650, 'stock' => 7,  'min' => 2],
                ],
                'customer' => ['name' => 'Deniz Kurt', 'phone' => '05357778899'],
                'vehicle'  => ['plate' => '35 KLM 99', 'brand' => 'Honda', 'model' => 'Civic 1.6 i-VTEC', 'year' => '2020', 'color' => 'Kırmızı', 'km' => 62000],
                'order'    => ['status' => 'tamamlandi', 'discount' => 50, 'labor_name' => 'Vakumlu Gaz Dolumu & Ozon Kanal Temizliği', 'labor_price' => 650],
            ],
            'Akümülatör (Akü) Ustası' => [
                'usta' => 'Akücü Dursun Usta', 'email' => 'dursun@aku.local', 'title' => 'Akü Test, Şarj & Değişim',
                'desc' => 'Start-stop AGM, EFB ve kurşun asit akü teşhisi, yerinde akü montajı ve şarj dinamosu ölçümü.',
                'parts' => [
                    ['name' => '60Ah İnci Akü Formul A',       'code' => 'AKU-INC-60', 'cat' => 'Akü', 'buy' => 1450, 'sell' => 2100, 'stock' => 14, 'min' => 3],
                    ['name' => '74Ah Mutlu Akü SFB Seri 3',     'code' => 'AKU-MTL-74', 'cat' => 'Akü', 'buy' => 1850, 'sell' => 2650, 'stock' => 10, 'min' => 2],
                    ['name' => 'Pirinç Akü Kutup Başı (Takım)', 'code' => 'AKU-KTP-03', 'cat' => 'Akü', 'buy' => 60,   'sell' => 150,  'stock' => 30, 'min' => 5],
                ],
                'customer' => ['name' => 'Kadir Şen', 'phone' => '05423334455'],
                'vehicle'  => ['plate' => '07 AKU 55', 'brand' => 'Renault', 'model' => 'Clio 1.2', 'year' => '2014', 'color' => 'Mavi', 'km' => 135000],
                'order'    => ['status' => 'odendi', 'discount' => 50, 'labor_name' => 'Akü Montajı & Şarj Dinamosu Testi', 'labor_price' => 250],
            ],
            'Kaporta Ustası' => [
                'usta' => 'Kaportacı İsmail Usta', 'email' => 'ismail@kaporta.local', 'title' => 'Şase Düzeltme & Kaporta Onarım',
                'desc' => 'Kaza hasarı onarımı, şase çektirme tezgahı, çamurluk, kapı ve tampon sacı düzeltme.',
                'parts' => [
                    ['name' => 'Ön Çamurluk Sacı (Orijinal Muadili)', 'code' => 'KPR-CML-01', 'cat' => 'Kaporta', 'buy' => 1400, 'sell' => 2300, 'stock' => 4,  'min' => 1],
                    ['name' => 'Kaput Kilidi & Açma Teli',           'code' => 'KPR-KLT-02', 'cat' => 'Kaporta', 'buy' => 280,  'sell' => 550,  'stock' => 8,  'min' => 2],
                    ['name' => 'Tampon İç Braket & Klips Seti',      'code' => 'KPR-KLP-03', 'cat' => 'Kaporta', 'buy' => 90,   'sell' => 220,  'stock' => 25, 'min' => 5],
                ],
                'customer' => ['name' => 'Orhan Veli', 'phone' => '05312223344'],
                'vehicle'  => ['plate' => '34 KPR 77', 'brand' => 'Peugeot', 'model' => '308 1.6 HDi', 'year' => '2017', 'color' => 'Beyaz', 'km' => 152000],
                'order'    => ['status' => 'devam_ediyor', 'discount' => 200, 'labor_name' => 'Sağ Çamurluk Sac Düzeltme & Panel Ayarı', 'labor_price' => 2200],
            ],
            'Oto Boyacı' => [
                'usta' => 'Boyacı Yusuf Usta', 'email' => 'yusuf@boya.local', 'title' => 'Fırın Boya, Lokal Boya & Pasta Cila',
                'desc' => 'Tozsuz fırında komple ve lokal boyama, renk eşleştirme, seramik kaplama ve pasta cila.',
                'parts' => [
                    ['name' => '2K Akrilik Oto Boyası 1LT (Glasurit)', 'code' => 'BYA-AKR-01', 'cat' => 'Boya', 'buy' => 650, 'sell' => 1200, 'stock' => 12, 'min' => 3],
                    ['name' => 'Yüksek Parlaklık HS Vernik Seti 1.5LT', 'code' => 'BYA-VRN-02', 'cat' => 'Boya', 'buy' => 450, 'sell' => 850,  'stock' => 15, 'min' => 4],
                    ['name' => '3M İnce Maskeleme Bandı (50m Rulo)',   'code' => 'BYA-BND-03', 'cat' => 'Boya', 'buy' => 75,  'sell' => 160,  'stock' => 40, 'min' => 10],
                ],
                'customer' => ['name' => 'Tarık Akın', 'phone' => '05556667788'],
                'vehicle'  => ['plate' => '06 BYA 90', 'brand' => 'Opel', 'model' => 'Astra 1.4 Turbo', 'year' => '2018', 'color' => 'Mavi', 'km' => 88000],
                'order'    => ['status' => 'tamamlandi', 'discount' => 250, 'labor_name' => 'Fırında Arka Tampon & Çamurluk Lokal Boya', 'labor_price' => 3500],
            ],
            'PDR (Boyasız Göçük) Ustası' => [
                'usta' => 'Göçükçü Sinan Usta', 'email' => 'sinan@pdr.local', 'title' => 'Boyasız Göçük Onarımı (PDR)',
                'desc' => 'Dolu hasarı, park hasarı ve kapı vuruğu onarımı, boyasız göçük çektirme ve masaj tekniği.',
                'parts' => [
                    ['name' => 'PDR Yüksek Mukavemetli Silikon Çubuk', 'code' => 'PDR-SLK-01', 'cat' => 'PDR', 'buy' => 120, 'sell' => 260, 'stock' => 50, 'min' => 10],
                    ['name' => 'PDR Farklı Çaplı Çektirme Pulu Seti',  'code' => 'PDR-PUL-02', 'cat' => 'PDR', 'buy' => 250, 'sell' => 550, 'stock' => 12, 'min' => 3],
                ],
                'customer' => ['name' => 'Ahmet Polat', 'phone' => '05437778811'],
                'vehicle'  => ['plate' => '34 PDR 15', 'brand' => 'Hyundai', 'model' => 'i20 1.4 MPI', 'year' => '2021', 'color' => 'Kırmızı', 'km' => 42000],
                'order'    => ['status' => 'tamamlandi', 'discount' => 100, 'labor_name' => 'Sol Ön Kapı Masaj Yöntemiyle PDR Göçük Düzeltme', 'labor_price' => 1600],
            ],
            'Plastik Tamircisi' => [
                'usta' => 'Plastikçi Kazım Usta', 'email' => 'kazim@plastik.local', 'title' => 'Plastik Kaynak & Tampon Tamiri',
                'desc' => 'Kırık tampon, far ayağı, ayna kapağı, radyatör kazanı ve manifold plastik kaynak tamiri.',
                'parts' => [
                    ['name' => 'PP/EPDM Termoplastik Kaynak Teli (1kg)', 'code' => 'PLS-TEL-01', 'cat' => 'Plastik', 'buy' => 200, 'sell' => 450, 'stock' => 15, 'min' => 3],
                    ['name' => 'Far Ayak Tamir & Güçlendirme Braketi',    'code' => 'PLS-FAR-02', 'cat' => 'Plastik', 'buy' => 150, 'sell' => 350, 'stock' => 20, 'min' => 4],
                ],
                'customer' => ['name' => 'Kemal Demir', 'phone' => '05338887766'],
                'vehicle'  => ['plate' => '34 PLS 82', 'brand' => 'Dacia', 'model' => 'Duster 1.5 dCi', 'year' => '2019', 'color' => 'Turuncu', 'km' => 112000],
                'order'    => ['status' => 'tamamlandi', 'discount' => 80, 'labor_name' => 'Ön Tampon Çatlak Kaynağı & Far Ayağı Tamiri', 'labor_price' => 950],
            ],
            'Oto Camcısı' => [
                'usta' => 'Camcı Rıza Usta', 'email' => 'riza@cam.local', 'title' => 'Oto Cam Değişimi & Kriko Tamiri',
                'desc' => 'Ön cam, yan cam, arka rezistanslı cam montajı, taş çatlağı tamiri, otomatik cam motoru onarımı.',
                'parts' => [
                    ['name' => 'Güneş Korumalı Lamine Ön Cam (Olimpia)', 'code' => 'CAM-ONC-01', 'cat' => 'Cam', 'buy' => 2100, 'sell' => 3400, 'stock' => 6,  'min' => 1],
                    ['name' => 'Poliüretan Oto Cam Yapıştırıcı Mastik',   'code' => 'CAM-MST-02', 'cat' => 'Cam', 'buy' => 160,  'sell' => 320,  'stock' => 25, 'min' => 5],
                    ['name' => 'Elektrikli Cam Kriko Tamir Teli Seti',   'code' => 'CAM-KRK-03', 'cat' => 'Cam', 'buy' => 120,  'sell' => 280,  'stock' => 18, 'min' => 4],
                ],
                'customer' => ['name' => 'Mahmut Turan', 'phone' => '05371114477'],
                'vehicle'  => ['plate' => '34 CAM 61', 'brand' => 'Ford', 'model' => 'Transit Custom', 'year' => '2018', 'color' => 'Gümüş', 'km' => 198000],
                'order'    => ['status' => 'tamamlandi', 'discount' => 150, 'labor_name' => 'Ön Cam Sökme, Fitil Temizliği & Yeni Cam Montajı', 'labor_price' => 1200],
            ],
            'Oto Döşeme Ustası' => [
                'usta' => 'Döşemeci Cengiz Usta', 'email' => 'cengiz@doseme.local', 'title' => 'Koltuk, Tavan & Direksiyon Döşeme',
                'desc' => 'Deri ve kumaş koltuk tamiri, sarkan tavan kumaşı yenileme, hakiki deri direksiyon dikimi.',
                'parts' => [
                    ['name' => 'Alcantara / Deri Oto Döşeme Kumaşı (Metre)', 'code' => 'DSM-KMS-01', 'cat' => 'Döşeme', 'buy' => 400, 'sell' => 850,  'stock' => 20, 'min' => 5],
                    ['name' => 'Süngerli Yanmaz Tavan Kumaşı (Gri)',        'code' => 'DSM-TVN-02', 'cat' => 'Döşeme', 'buy' => 280, 'sell' => 600,  'stock' => 15, 'min' => 3],
                    ['name' => 'Hakiki Deri Direksiyon Kaplama Kiti',       'code' => 'DSM-DRK-03', 'cat' => 'Döşeme', 'buy' => 220, 'sell' => 550,  'stock' => 12, 'min' => 2],
                ],
                'customer' => ['name' => 'Cihan Koç', 'phone' => '05419992233'],
                'vehicle'  => ['plate' => '34 DSM 34', 'brand' => 'Mercedes-Benz', 'model' => 'E200 W211', 'year' => '2008', 'color' => 'Siyah', 'km' => 280000],
                'order'    => ['status' => 'devam_ediyor', 'discount' => 200, 'labor_name' => 'Sarkan Tavan Kumaşı Sökümü & Hakiki Deri Direksiyon Dikimi', 'labor_price' => 2800],
            ],
            'Oto Kilit & İmmobilizer' => [
                'usta' => 'Kilitçi Metin Usta', 'email' => 'metin@kilit.local', 'title' => 'Anahtar, İmmobilizer & Kontak Tamiri',
                'desc' => 'Yedek anahtar yapımı, çip kodlama, kontak kilit şifre dizme, kapı kilit motoru tamiri.',
                'parts' => [
                    ['name' => '3 Butonlu Sustalı Kumanda Kabı (Fiat/VAG)', 'code' => 'KLT-KMD-01', 'cat' => 'Kilit', 'buy' => 90,  'sell' => 250, 'stock' => 30, 'min' => 5],
                    ['name' => 'ID48 / ID46 Cam Transponder Çip',          'code' => 'KLT-CIP-02', 'cat' => 'Kilit', 'buy' => 65,  'sell' => 180, 'stock' => 40, 'min' => 8],
                    ['name' => 'Kontak Şifre Pimi & Yay Takımı',            'code' => 'KLT-SFR-03', 'cat' => 'Kilit', 'buy' => 110, 'sell' => 280, 'stock' => 15, 'min' => 3],
                ],
                'customer' => ['name' => 'Salih Arslan', 'phone' => '05395551122'],
                'vehicle'  => ['plate' => '16 KLT 99', 'brand' => 'Fiat', 'model' => 'Doblo 1.6 MJet', 'year' => '2017', 'color' => 'Beyaz', 'km' => 175000],
                'order'    => ['status' => 'tamamlandi', 'discount' => 100, 'labor_name' => 'Sustalı Yedek Anahtar Çıkarma & İmmobilizer Kodlama', 'labor_price' => 1100],
            ],
            'Ses & Multimedya Ustası' => [
                'usta' => 'Müzikçi Barış Usta', 'email' => 'baris@multimedya.local', 'title' => 'Android Ekran, Ses & Kamera',
                'desc' => 'CarPlay/Android Auto ekran montajı, geri görüş kamerası, amfi, subwoofer ve park sensörü.',
                'parts' => [
                    ['name' => '9 İnç Android 4GB/64GB CarPlay Ekran', 'code' => 'MLT-EKR-01', 'cat' => 'Multimedya', 'buy' => 3200, 'sell' => 5200, 'stock' => 5,  'min' => 1],
                    ['name' => '1080P Gece Görüşlü Geri Görüş Kamerası', 'code' => 'MLT-KMR-02', 'cat' => 'Multimedya', 'buy' => 250,  'sell' => 550,  'stock' => 14, 'min' => 3],
                    ['name' => '16cm 2 Yollu Komponent Hoparlör (Pioneer)', 'code' => 'MLT-HPR-03', 'cat' => 'Ses',     'buy' => 850,  'sell' => 1500, 'stock' => 8,  'min' => 2],
                ],
                'customer' => ['name' => 'Oğuzhan Mert', 'phone' => '05456663322'],
                'vehicle'  => ['plate' => '34 MLT 55', 'brand' => 'Seat', 'model' => 'Leon 1.2 TSI', 'year' => '2016', 'color' => 'Kırmızı', 'km' => 124000],
                'order'    => ['status' => 'tamamlandi', 'discount' => 150, 'labor_name' => 'Android Ekran Montajı & Geri Görüş Kamerası Tesisatı', 'labor_price' => 1200],
            ],
            'Egzoz Ustası' => [
                'usta' => 'Egzozcu Hakan Usta', 'email' => 'hakan@egzoz.local', 'title' => 'Susturucu, Katalizör & DPF',
                'desc' => 'Egzoz kaçak tamiri, spiral değişimi, susturucu yenileme, partikül filtresi (DPF) ve katalizör.',
                'parts' => [
                    ['name' => 'Paslanmaz Çift Katlı Egzoz Spirali (50x200)', 'code' => 'EGZ-SPR-01', 'cat' => 'Egzoz', 'buy' => 280,  'sell' => 580,  'stock' => 16, 'min' => 3],
                    ['name' => 'Orijinal Tip Arka Susturucu Kazan',           'code' => 'EGZ-SST-02', 'cat' => 'Egzoz', 'buy' => 1100, 'sell' => 1900, 'stock' => 6,  'min' => 1],
                    ['name' => 'Egzoz Boğaz Contası & Kelepçe Takımı',       'code' => 'EGZ-CNT-03', 'cat' => 'Egzoz', 'buy' => 80,   'sell' => 190,  'stock' => 25, 'min' => 5],
                ],
                'customer' => ['name' => 'Serkan Güler', 'phone' => '05354448899'],
                'vehicle'  => ['plate' => '34 EGZ 22', 'brand' => 'BMW', 'model' => '320i E90', 'year' => '2011', 'color' => 'Gümüş', 'km' => 230000],
                'order'    => ['status' => 'tamamlandi', 'discount' => 100, 'labor_name' => 'Egzoz Spirali Kesme & Gazaltı Kaynak Montajı', 'labor_price' => 750],
            ],
            'Radyatör & Petek Ustası' => [
                'usta' => 'Radyatörcü Veli Usta', 'email' => 'veli@radyator.local', 'title' => 'Radyatör, Kalorifer Peteği & Antifriz',
                'desc' => 'Makineli kalorifer petek temizleme, su radyatörü değişimi, termostat ve soğutma sıvısı bakımı.',
                'parts' => [
                    ['name' => 'Alüminyum Motor Su Radyatörü (Kale)',      'code' => 'RDY-RAD-01', 'cat' => 'Soğutma', 'buy' => 1650, 'sell' => 2600, 'stock' => 6,  'min' => 1],
                    ['name' => 'Kırmızı Organik Antifriz 3LT (-56°C)',      'code' => 'RDY-ANT-02', 'cat' => 'Soğutma', 'buy' => 220,  'sell' => 450,  'stock' => 30, 'min' => 6],
                    ['name' => '87°C Gövdeli Termostat (Wahler)',           'code' => 'RDY-TRM-03', 'cat' => 'Soğutma', 'buy' => 380,  'sell' => 700,  'stock' => 10, 'min' => 2],
                ],
                'customer' => ['name' => 'Zafer Erdem', 'phone' => '05441113355'],
                'vehicle'  => ['plate' => '34 RDY 77', 'brand' => 'Citroen', 'model' => 'C-Elysee 1.6 HDi', 'year' => '2017', 'color' => 'Beyaz', 'km' => 162000],
                'order'    => ['status' => 'tamamlandi', 'discount' => 100, 'labor_name' => 'Makineli İlaçlı Petek Yıkama & Organik Antifriz', 'labor_price' => 850],
            ],
            'Torna & Kaynak Ustası' => [
                'usta' => 'Tornacı Necati Usta', 'email' => 'necati@torna.local', 'title' => 'Torna, Kırık Civata & Alüminyum Kaynak',
                'desc' => 'Motor bloğunda kırık civata çıkarma, buji yuvası diş açma/helicoil, disk torna taşlama ve TIG kaynak.',
                'parts' => [
                    ['name' => 'M14x1.25 Buji Helicoil Yay Seti',        'code' => 'TRN-HLC-01', 'cat' => 'Torna', 'buy' => 180, 'sell' => 400, 'stock' => 20, 'min' => 4],
                    ['name' => 'Sertleştirilmiş Çelik Bijon Saplaması',   'code' => 'TRN-BJN-02', 'cat' => 'Torna', 'buy' => 60,  'sell' => 150, 'stock' => 35, 'min' => 8],
                    ['name' => 'Alüminyum TIG Kaynak Teli (1kg Paket)', 'code' => 'TRN-TIG-03', 'cat' => 'Torna', 'buy' => 320, 'sell' => 650, 'stock' => 10, 'min' => 2],
                ],
                'customer' => ['name' => 'Bekir Doğan', 'phone' => '05334449911'],
                'vehicle'  => ['plate' => '34 TRN 06', 'brand' => 'Isuzu', 'model' => 'D-Max 2.5 4x4', 'year' => '2015', 'color' => 'Beyaz', 'km' => 240000],
                'order'    => ['status' => 'tamamlandi', 'discount' => 100, 'labor_name' => 'Karter Yalama Diş Çekme & Helicoil Yay Uygulaması', 'labor_price' => 750],
            ],
            'Rot-Balans Lastikçisi' => [
                'usta' => 'Lastikçi Orhan Usta', 'email' => 'orhan@lastik.local', 'title' => 'Lastik Değişimi, Balans & Yama',
                'desc' => 'Yazlık/kışlık lastik değişimi, dinamik balans, nitrojen hava basımı, jant düzeltme ve fitil yama.',
                'parts' => [
                    ['name' => '205/55 R16 91V Yaz Lastiği (Lassa Driveways)', 'code' => 'LST-205-16', 'cat' => 'Lastik', 'buy' => 1650, 'sell' => 2350, 'stock' => 16, 'min' => 4],
                    ['name' => 'Yapıştırma Jant Kurşun Şeridi (60g x 50)',      'code' => 'LST-KRS-02', 'cat' => 'Lastik', 'buy' => 220,  'sell' => 480,  'stock' => 12, 'min' => 3],
                    ['name' => 'Krom Çelik Sibop Takımı (4 Adet)',             'code' => 'LST-SBP-03', 'cat' => 'Lastik', 'buy' => 80,   'sell' => 200,  'stock' => 25, 'min' => 5],
                ],
                'customer' => ['name' => 'Ufuk Baran', 'phone' => '05325556677'],
                'vehicle'  => ['plate' => '34 LST 16', 'brand' => 'Skoda', 'model' => 'Octavia 1.6 TDI', 'year' => '2019', 'color' => 'Beyaz', 'km' => 89000],
                'order'    => ['status' => 'tamamlandi', 'discount' => 100, 'labor_name' => '4 Lastik Sökme Takma, Balans & Nitrojen Dolumu', 'labor_price' => 600],
            ],
            'Periyodik Hızlı Bakım' => [
                'usta' => 'Bakımcı Mehmet Usta', 'email' => 'mehmet@bakim.local', 'title' => 'Hızlı Yağ, Filtre & Genel Bakım',
                'desc' => '30 dakikada ekspres periyodik bakım, yağ/hava/polen/yakıt filtreleri, buji değişimi ve sıvı kontrolü.',
                'parts' => [
                    ['name' => '5W-30 Castrol Edge Motor Yağı 4LT', 'code' => 'BKM-YAG-01', 'cat' => 'Bakım', 'buy' => 950, 'sell' => 1500, 'stock' => 20, 'min' => 5],
                    ['name' => '4\'lü Filtre Bakım Seti (Mann)',   'code' => 'BKM-FLT-02', 'cat' => 'Bakım', 'buy' => 550, 'sell' => 950,  'stock' => 15, 'min' => 4],
                    ['name' => 'İridyum Buji Takımı (NGK 4 Adet)',   'code' => 'BKM-BUJ-03', 'cat' => 'Bakım', 'buy' => 650, 'sell' => 1100, 'stock' => 10, 'min' => 2],
                ],
                'customer' => ['name' => 'Halil Demirtaş', 'phone' => '05367779900'],
                'vehicle'  => ['plate' => '34 BKM 48', 'brand' => 'Toyota', 'model' => 'Yaris 1.33', 'year' => '2018', 'color' => 'Beyaz', 'km' => 72000],
                'order'    => ['status' => 'odendi', 'discount' => 100, 'labor_name' => 'Periyodik Yağ & Filtre Değişim İşçiliği + 30 Nokta Kontrol', 'labor_price' => 600],
            ],
            'Oto LPG & Gaz Sistemleri' => [
                'usta' => 'Gazcı Levent Usta', 'email' => 'levent@lpg.local', 'title' => 'LPG Tank, Enjektör & Gaz Ayarı',
                'desc' => 'Prins, BRC, Atiker LPG montajı, gaz kaçak kontrolü, regülatör revizyonu ve yol AFR ayarı.',
                'parts' => [
                    ['name' => 'LPG Sıvı & Gaz Filtre Seti (Atiker/BRC)', 'code' => 'LPG-FLT-01', 'cat' => 'LPG', 'buy' => 120,  'sell' => 280,  'stock' => 30, 'min' => 5],
                    ['name' => '4 Silindir LPG Enjektör Kütüğü (Valtek)', 'code' => 'LPG-ENJ-02', 'cat' => 'LPG', 'buy' => 850,  'sell' => 1500, 'stock' => 8,  'min' => 2],
                    ['name' => 'LPG Regülatör / Beyin 110kW',              'code' => 'LPG-REG-03', 'cat' => 'LPG', 'buy' => 1100, 'sell' => 1950, 'stock' => 6,  'min' => 1],
                ],
                'customer' => ['name' => 'Tolga Çetin', 'phone' => '05386661133'],
                'vehicle'  => ['plate' => '34 LPG 06', 'brand' => 'Honda', 'model' => 'City 1.4', 'year' => '2012', 'color' => 'Gümüş', 'km' => 168000],
                'order'    => ['status' => 'tamamlandi', 'discount' => 80, 'labor_name' => 'LPG Filtre Değişimi, Kaçak Testi & Bilgisayarlı Gaz Ayarı', 'labor_price' => 500],
            ],
        ];

        // 3. Her Branş İçin Güvenli & İzole Veri Üretimi
        foreach ($branchDefinitions as $branchName => $data) {
            // Şube Kaydı (Uzmanlık ve İletişim Bilgileriyle)
            $branch = Branch::updateOrCreate(
                ['name' => $branchName],
                [
                    'title'            => $data['title'],
                    'description'      => $data['desc'],
                    'phone'            => '0212 ' . rand(200, 999) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                    'address'          => 'Oto Sanayi Sitesi, ' . rand(1, 45) . '. Blok No:' . rand(1, 80) . ', İstanbul',
                    'tax_office'       => 'Marmara Vergi Dairesi',
                    'tax_no'           => (string)rand(1000000000, 9999999999),
                    'receipt_footer'   => 'İşbu servis fişinde yer alan işlemler SanayiPro güvencesiyle 1 yıl garantilidir.',
                    'whatsapp_message' => 'Sayın {musteri_adi}, {plaka} plakalı aracınızın servis işlemleri tamamlanmıştır.',
                    'currency'         => 'TRY',
                ]
            );

            // Usta Kullanıcısı
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name'      => $data['usta'],
                    'password'  => Hash::make('password'),
                    'role'      => 'branch_user',
                    'branch_id' => $branch->id,
                ]
            );

            // Kullanıcı oturumu taklit edilerek oluşturulan verilerin branch/user_id otomatik atanması sağlanır
            auth()->login($user);

            // Branş Tedarikçisi
            Supplier::firstOrCreate(
                ['branch_id' => $branch->id, 'name' => $branchName . ' Toptan Yedek Parça A.Ş.'],
                [
                    'user_id'        => $user->id,
                    'contact_person' => 'Ahmet Yetkili',
                    'phone'          => '0532 999 00 ' . rand(10, 99),
                    'email'          => 'siparis@' . strtolower(preg_replace('/[^a-zA-Z]/', '', $branchName)) . '.com',
                    'address'        => 'Merkez Sanayi Toptancılar Çarşısı No: ' . rand(1, 50),
                    'notes'          => 'Hızlı sevkiyat, güvenilir tedarikçi.',
                ]
            );

            // Parçalar (Stok ve Alış/Satış Fiyatları)
            $createdParts = [];
            foreach ($data['parts'] as $partData) {
                $part = Part::firstOrCreate(
                    ['branch_id' => $branch->id, 'code' => $partData['code']],
                    [
                        'user_id'             => $user->id,
                        'name'                => $partData['name'],
                        'category'            => $partData['cat'],
                        'supplier'            => $branchName . ' Toptan Yedek Parça A.Ş.',
                        'buy_price'           => $partData['buy'],
                        'last_buy_price'      => $partData['buy'],
                        'sell_price'          => $partData['sell'],
                        'stock'               => $partData['stock'],
                        'min_stock'           => $partData['min'],
                        'compatible_vehicles' => $data['vehicle']['brand'] . ' ' . $data['vehicle']['model'],
                    ]
                );
                $createdParts[] = $part;
            }

            // Müşteri
            $customer = Customer::firstOrCreate(
                ['branch_id' => $branch->id, 'phone' => $data['customer']['phone']],
                [
                    'user_id' => $user->id,
                    'name'    => $data['customer']['name'],
                    'notes'   => 'Sürekli gelen sadık müşteri.',
                ]
            );

            // Araç
            $vehicle = Vehicle::firstOrCreate(
                ['branch_id' => $branch->id, 'plate' => $data['vehicle']['plate']],
                [
                    'user_id'     => $user->id,
                    'customer_id' => $customer->id,
                    'brand'       => $data['vehicle']['brand'],
                    'model'       => $data['vehicle']['model'],
                    'year'        => $data['vehicle']['year'],
                    'color'       => $data['vehicle']['color'],
                    'mileage'     => $data['vehicle']['km'] ?? 100000,
                ]
            );

            // İş Emri (Geçmiş / Aktif)
            $orderData = $data['order'];
            $workOrder = WorkOrder::firstOrCreate(
                ['branch_id' => $branch->id, 'vehicle_id' => $vehicle->id],
                [
                    'user_id'     => $user->id,
                    'date'        => now()->subDays(rand(0, 5)),
                    'mileage'     => $vehicle->mileage,
                    'status'      => $orderData['status'],
                    'discount'    => $orderData['discount'],
                    'notes'       => $data['title'] . ' kapsamında planlı servis işlemi.',
                    'total_parts' => 0,
                    'total_labor' => 0,
                ]
            );

            // Kalemler eklenmemişse ekle
            if ($workOrder->items()->count() === 0) {
                $totalPartCost = 0;
                foreach (array_slice($createdParts, 0, 2) as $p) {
                    WorkOrderItem::create([
                        'work_order_id' => $workOrder->id,
                        'part_id'       => $p->id,
                        'type'          => 'part',
                        'name'          => $p->name,
                        'quantity'      => 1,
                        'unit_price'    => $p->sell_price,
                        'total'         => $p->sell_price,
                    ]);
                    $totalPartCost += $p->sell_price;
                }

                WorkOrderItem::create([
                    'work_order_id' => $workOrder->id,
                    'part_id'       => null,
                    'type'          => 'labor',
                    'name'          => $orderData['labor_name'],
                    'quantity'      => 1,
                    'unit_price'    => $orderData['labor_price'],
                    'total'         => $orderData['labor_price'],
                ]);

                $workOrder->recalculate();
            }

            // Randevu (Bugün / Yarın için)
            Appointment::firstOrCreate(
                ['branch_id' => $branch->id, 'title' => $data['title'] . ' Kontrolü'],
                [
                    'user_id'    => $user->id,
                    'vehicle_id' => $vehicle->id,
                    'start_time' => now()->setTime(10 + (rand(0, 5)), 0, 0),
                    'end_time'   => now()->setTime(11 + (rand(0, 5)), 0, 0),
                    'status'     => 'randevu',
                    'notes'      => 'Müşteri randevu saatinde aracı getirecek.',
                ]
            );
        }

        auth()->logout();
    }
}
