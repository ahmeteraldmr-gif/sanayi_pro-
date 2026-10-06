<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'title',
        'description',
        'phone',
        'address',
        'tax_office',
        'tax_no',
        'receipt_footer',
        'whatsapp_message',
        'currency',
        'logo',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function parts(): HasMany
    {
        return $this->hasMany(Part::class);
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * 23 Sanayi Branşı için Branşa Özel Hızlı İşlem Şablonları (Quick Actions).
     */
    public function getQuickActionsAttribute(): array
    {
        return static::getBranchQuickActions($this->name);
    }

    public static function getBranchQuickActions(?string $branchName): array
    {
        $actions = [
            'Motor & Mekanik' => [
                ['title' => 'Motor Arıza Tespiti',   'icon' => '🔍', 'labor' => 'Motor Detaylı Arıza Tespiti',   'price' => 750,  'color' => 'blue'],
                ['title' => 'Yağ Kaçağı Kontrolü',   'icon' => '🛢️', 'labor' => 'Külbütör & Karter Kaçak Kontrolü', 'price' => 500, 'color' => 'amber'],
                ['title' => 'Triger İşlemi',         'icon' => '⚙️', 'labor' => 'Triger Kayışı / Zinciri Değişimi', 'price' => 2500, 'color' => 'indigo'],
                ['title' => 'Motor Revizyonu',       'icon' => '🔧', 'labor' => 'Komple Motor İndirme & Revizyon', 'price' => 12000, 'color' => 'emerald'],
            ],
            'Şanzıman & Vites Kutusu' => [
                ['title' => 'Şanzıman Arıza Tespiti', 'icon' => '🔍', 'labor' => 'Şanzıman Basınç & Vites Testi', 'price' => 800,  'color' => 'blue'],
                ['title' => 'Şanzıman Yağı',          'icon' => '🛢️', 'labor' => 'Şanzıman Yağı & Filtre Değişimi', 'price' => 600, 'color' => 'amber'],
                ['title' => 'Baskı Balata',           'icon' => '⚙️', 'labor' => 'Baskı Balata & Rulman Değişimi', 'price' => 2800, 'color' => 'indigo'],
                ['title' => 'Kavrama Kontrolü',       'icon' => '🔧', 'labor' => 'Çift Kavrama / DSG Kalibrasyonu', 'price' => 1500, 'color' => 'emerald'],
            ],
            'Ön Düzen & Rot-Balans' => [
                ['title' => 'Rot Ayarı',              'icon' => '🎯', 'labor' => 'Lazerli Ön & Arka Rot Ayarı', 'price' => 450,  'color' => 'blue'],
                ['title' => 'Balans Ayarı',           'icon' => '🛞', 'labor' => '4 Lastik Dinamik Balans',     'price' => 400,  'color' => 'amber'],
                ['title' => 'Amortisör Değişimi',     'icon' => '🔩', 'labor' => 'Ön / Arka Amortisör Değişimi', 'price' => 900,  'color' => 'indigo'],
                ['title' => 'Salıncak / Rotil',       'icon' => '🔧', 'labor' => 'Salıncak Burcu & Rotil Değişimi', 'price' => 750, 'color' => 'emerald'],
            ],
            'Fren Sistemleri' => [
                ['title' => 'Balata Değişimi',        'icon' => '🛑', 'labor' => 'Ön / Arka Fren Balata Değişimi', 'price' => 500,  'color' => 'rose'],
                ['title' => 'Disk Kontrolü & Değişim','icon' => '💿', 'labor' => 'Fren Diski Değişimi & Temizliği', 'price' => 750, 'color' => 'blue'],
                ['title' => 'Fren Hidroliği',         'icon' => '🧪', 'labor' => 'Vakumlu Fren Hidroliği Değişimi', 'price' => 400, 'color' => 'amber'],
                ['title' => 'Fren Arıza Kontrolü',    'icon' => '🔍', 'labor' => 'ABS / ESP & Fren Testi', 'price' => 450, 'color' => 'emerald'],
            ],
            'Enjektör & Turbo' => [
                ['title' => 'Enjektör Testi',         'icon' => '🧪', 'labor' => 'Tezgahta Enjektör Geri Dönüş Testi', 'price' => 600, 'color' => 'blue'],
                ['title' => 'Turbo Kontrolü',         'icon' => '🌀', 'labor' => 'Turbo Basınç & Mil Boşluk Kontrolü', 'price' => 850, 'color' => 'indigo'],
                ['title' => 'Dizel Pompa Revizyonu',  'icon' => '⚙️', 'labor' => 'Yüksek Basınç Pompası Revizyonu', 'price' => 3500, 'color' => 'amber'],
                ['title' => 'Basınç Kontrolü',        'icon' => '📊', 'labor' => 'Common Rail Yakıt Rayı Basınç Testi', 'price' => 500, 'color' => 'emerald'],
            ],
            'Oto Elektrik' => [
                ['title' => 'Akü Testi',              'icon' => '🔋', 'labor' => 'Akü Sağlık & Marş Voltaj Testi', 'price' => 250, 'color' => 'emerald'],
                ['title' => 'Marş Sistemi',           'icon' => '⚡', 'labor' => 'Marş Motoru Sökme & Kömür Değişimi', 'price' => 850, 'color' => 'amber'],
                ['title' => 'Şarj Dinamosu',          'icon' => '🔌', 'labor' => 'Alternatör / Şarj Dinamosu Revizyonu', 'price' => 950, 'color' => 'blue'],
                ['title' => 'Elektrik Arızası',       'icon' => '🔍', 'labor' => 'Tesisat Kaçak & Sigorta Tespiti', 'price' => 600, 'color' => 'indigo'],
            ],
            'Oto Beyin & Elektronik' => [
                ['title' => 'ECU Arıza Tespiti',      'icon' => '💻', 'labor' => 'Detaylı Beyin & Modül Teşhisi', 'price' => 750, 'color' => 'indigo'],
                ['title' => 'Arıza Kodu Okuma',       'icon' => '📟', 'labor' => 'OBD Tüm Sistem Tarama & Sıfırlama', 'price' => 350, 'color' => 'blue'],
                ['title' => 'Sensör Kontrolü',        'icon' => '📡', 'labor' => 'Krank / Eksantrik / MAF Sensör Testi', 'price' => 500, 'color' => 'amber'],
                ['title' => 'Gizli Özellik & Kodlama', 'icon' => '✨', 'labor' => 'Modül Kodlama & Yazılım Güncelleme', 'price' => 1200, 'color' => 'emerald'],
            ],
            'Oto Klima Ustası' => [
                ['title' => 'Klima Gaz Dolumu',       'icon' => '❄️', 'labor' => 'Vakumlama & R134a/R1234yf Gaz Dolumu', 'price' => 900, 'color' => 'cyan'],
                ['title' => 'Kaçak Testi',            'icon' => '🔍', 'labor' => 'Azotlu Klima Kaçak Tespiti', 'price' => 450, 'color' => 'blue'],
                ['title' => 'Kompresör Kontrolü',     'icon' => '⚙️', 'labor' => 'Klima Kompresör & Kavrama Testi', 'price' => 600, 'color' => 'indigo'],
                ['title' => 'Polen Filtresi',         'icon' => '🍃', 'labor' => 'Polen Filtresi Değişimi & Dezenfeksiyon', 'price' => 300, 'color' => 'emerald'],
            ],
            'Akümülatör (Akü) Ustası' => [
                ['title' => 'Akü Testi',              'icon' => '🔋', 'labor' => 'Dijital Akü CCA & Şarj Testi', 'price' => 150, 'color' => 'emerald'],
                ['title' => 'Akü Değişimi',           'icon' => '🔄', 'labor' => 'Akü Değişimi & BMS Tanıtımı', 'price' => 350, 'color' => 'blue'],
                ['title' => 'Şarj Testi',             'icon' => '⚡', 'labor' => 'Şarj Dinamosu & Diyot Tabla Kontrolü', 'price' => 300, 'color' => 'amber'],
                ['title' => 'Kaçak Akım Ölçümü',      'icon' => '🔍', 'labor' => 'Parazit Akım / Akü Boşalma Tespiti', 'price' => 450, 'color' => 'indigo'],
            ],
            'Kaporta Ustası' => [
                ['title' => 'Hasar Kaydı',            'icon' => '📋', 'labor' => 'Kaza & Hasar Ekspertiz Tespiti', 'price' => 500, 'color' => 'blue'],
                ['title' => 'Kaporta Onarımı',        'icon' => '🔨', 'labor' => 'Çamurluk / Kapı Sacı Düzeltme', 'price' => 1800, 'color' => 'amber'],
                ['title' => 'Şase Kontrolü',          'icon' => '📐', 'labor' => 'Şase Çektirme & Lazer Ölçüm', 'price' => 3500, 'color' => 'indigo'],
                ['title' => 'Panel Ayarı',            'icon' => '🔩', 'labor' => 'Kaput / Bagaj Boşluk Ayarı', 'price' => 600, 'color' => 'emerald'],
            ],
            'Oto Boyacı' => [
                ['title' => 'Lokal Boya',             'icon' => '🎨', 'labor' => 'Parça Lokal Boya & Vernik', 'price' => 1500, 'color' => 'purple'],
                ['title' => 'Komple Parça Boya',      'icon' => '🚗', 'labor' => 'Fırınlı Komple Parça Boyama', 'price' => 3000, 'color' => 'indigo'],
                ['title' => 'Renk Kontrolü & Pasta',  'icon' => '✨', 'labor' => 'Pasta Cila & Boya Koruma', 'price' => 2500, 'color' => 'amber'],
                ['title' => 'Astar & Zımpara',        'icon' => '🛡️', 'labor' => 'Yüzey Hazırlama & Epoksi Astar', 'price' => 800, 'color' => 'emerald'],
            ],
            'PDR (Boyasız Göçük) Ustası' => [
                ['title' => 'Göçük Tespiti',          'icon' => '💡', 'labor' => 'Çizgili Işık Altında Göçük Analizi', 'price' => 300, 'color' => 'amber'],
                ['title' => 'Boyasız Göçük Onarımı',  'icon' => '🪄', 'labor' => 'PDR Masaj Yöntemi ile Göçük Çekme', 'price' => 1200, 'color' => 'emerald'],
                ['title' => 'Dolu Hasarı Onarımı',    'icon' => '🌧️', 'labor' => 'Komple Araç Dolu Hasarı PDR', 'price' => 6500, 'color' => 'indigo'],
                ['title' => 'Park Hasarı Düzeltme',   'icon' => '🅿️', 'labor' => 'Kapı Vuruğu & Çizgisiz Göçük Onarımı', 'price' => 800, 'color' => 'blue'],
            ],
            'Plastik Tamircisi' => [
                ['title' => 'Tampon Tamiri',          'icon' => '🛡️', 'labor' => 'Ön / Arka Tampon Kırık Onarımı', 'price' => 850, 'color' => 'blue'],
                ['title' => 'Plastik Kaynak',         'icon' => '🔥', 'labor' => 'Termoplastik Elektrot Kaynağı', 'price' => 600, 'color' => 'amber'],
                ['title' => 'Far Ayağı Onarımı',      'icon' => '💡', 'labor' => 'Kırık Far Kulak Kaynağı & Ayar', 'price' => 450, 'color' => 'emerald'],
                ['title' => 'İç Trim Onarımı',        'icon' => '🧩', 'labor' => 'Tırnak & Havalandırma Izgara Tamiri', 'price' => 400, 'color' => 'indigo'],
            ],
            'Oto Camcısı' => [
                ['title' => 'Ön Cam Değişimi',        'icon' => '🪟', 'labor' => 'Ön Cam Sökme, Fitil & Yapıştırma', 'price' => 1200, 'color' => 'blue'],
                ['title' => 'Taş Çatlağı Tamiri',     'icon' => '💎', 'labor' => 'Reçine Enjeksiyonu ile Cam Tamiri', 'price' => 500, 'color' => 'emerald'],
                ['title' => 'Cam Krikosu Tamiri',     'icon' => '⚙️', 'labor' => 'Elektrikli Cam Motoru & Tel Değişimi', 'price' => 750, 'color' => 'amber'],
                ['title' => 'Yan / Arka Cam Montajı', 'icon' => '🚙', 'labor' => 'Rezistanslı Arka Cam Değişimi', 'price' => 900, 'color' => 'indigo'],
            ],
            'Oto Döşeme Ustası' => [
                ['title' => 'Koltuk Döşeme',          'icon' => '💺', 'labor' => 'Koltuk Kumaş / Deri Yenileme', 'price' => 3500, 'color' => 'indigo'],
                ['title' => 'Tavan Döşeme',           'icon' => '🏕️', 'labor' => 'Sarkan Tavan Kumaşı Yenileme', 'price' => 2200, 'color' => 'blue'],
                ['title' => 'Direksiyon Kaplama',     'icon' => '⭕', 'labor' => 'Hakiki Deri Direksiyon Dikimi', 'price' => 900, 'color' => 'emerald'],
                ['title' => 'Taban Halısı & Yalıtım', 'icon' => '🔇', 'labor' => 'Ses Yalıtımı & Taban Halısı Montajı', 'price' => 2800, 'color' => 'amber'],
            ],
            'Oto Kilit & İmmobilizer' => [
                ['title' => 'Anahtar Kodlama',        'icon' => '🔑', 'labor' => 'Yedek Kumandalı Anahtar Çıkarma & Kodlama', 'price' => 1500, 'color' => 'blue'],
                ['title' => 'İmmobilizer Eşleme',     'icon' => '🛡️', 'labor' => 'İmmobilizer / Çip Yeniden Eşleme', 'price' => 1200, 'color' => 'indigo'],
                ['title' => 'Kontak Tamiri',          'icon' => '🗝️', 'labor' => 'Kontak Şifre Dizme & Kilit Onarımı', 'price' => 850, 'color' => 'amber'],
                ['title' => 'Kapı Kilit Motoru',      'icon' => '🚪', 'labor' => 'Merkezi Kilit Motoru Değişimi', 'price' => 600, 'color' => 'emerald'],
            ],
            'Ses & Multimedya Ustası' => [
                ['title' => 'Android Ekran Montajı',  'icon' => '📺', 'labor' => 'Multimedya Ekran & Canbus Montajı', 'price' => 1200, 'color' => 'indigo'],
                ['title' => 'Geri Görüş Kamerası',    'icon' => '📷', 'labor' => 'HD AHD Geri Görüş Kamera Montajı', 'price' => 650, 'color' => 'blue'],
                ['title' => 'Hoparlör & Amfi',        'icon' => '🔊', 'labor' => 'Ses Sistemi & Komponent Hoparlör Kurulumu', 'price' => 1500, 'color' => 'amber'],
                ['title' => 'Park Sensörü Montajı',   'icon' => '📡', 'labor' => '4 Gözlü Sesli Park Sensörü Montajı', 'price' => 750, 'color' => 'emerald'],
            ],
            'Egzoz Ustası' => [
                ['title' => 'Egzoz Kontrolü & Kaçak', 'icon' => '🔍', 'labor' => 'Egzoz Hattı & Manifold Kaçak Tespiti', 'price' => 350, 'color' => 'blue'],
                ['title' => 'Susturucu Değişimi',     'icon' => '💨', 'labor' => 'Orta / Son Susturucu Kaynak & Montaj', 'price' => 850, 'color' => 'indigo'],
                ['title' => 'Katalizör / DPF Temizliği','icon' => '🧪', 'labor' => 'Katalitik Konvertör & DPF Rejenerasyonu', 'price' => 1800, 'color' => 'amber'],
                ['title' => 'Spiral & Flanş Kaynağı', 'icon' => '🔥', 'labor' => 'Egzoz Spirali Sökme & Gazaltı Kaynak', 'price' => 600, 'color' => 'emerald'],
            ],
            'Radyatör & Petek Ustası' => [
                ['title' => 'Petek Temizliği',        'icon' => '♨️', 'labor' => 'Makineli Kalorifer Peteği İlaçlı Yıkama', 'price' => 750, 'color' => 'rose'],
                ['title' => 'Radyatör Değişimi',      'icon' => '🧊', 'labor' => 'Motor Su Radyatörü Sökme & Montaj', 'price' => 950, 'color' => 'blue'],
                ['title' => 'Antifriz Dolumu & Hava', 'icon' => '🧪', 'labor' => 'Organik Antifriz Basımı & Sistem Havası Alma', 'price' => 400, 'color' => 'emerald'],
                ['title' => 'Termostat & Hortum',     'icon' => '🌡️', 'labor' => 'Termostat & Su Hortumu Kaçak Onarımı', 'price' => 550, 'color' => 'amber'],
            ],
            'Torna & Kaynak Ustası' => [
                ['title' => 'Kırık Civata Çıkarma',   'icon' => '🔩', 'labor' => 'Motor Bloğu / Porya Kırık Civata Alma', 'price' => 600, 'color' => 'rose'],
                ['title' => 'Diş Açma & Helicoil',    'icon' => '⚙️', 'labor' => 'Buji Yuvası / Karter Diş Çekme & Yay', 'price' => 500, 'color' => 'amber'],
                ['title' => 'Kampana / Disk Taşlama', 'icon' => '🪚', 'labor' => 'Torna Tezgahında Disk & Volan Taşlama', 'price' => 750, 'color' => 'blue'],
                ['title' => 'Alüminyum / Argon Kaynak','icon' => '⚡', 'labor' => 'Karter / Şanzıman Gövde TIG Kaynağı', 'price' => 900, 'color' => 'emerald'],
            ],
            'Rot-Balans Lastikçisi' => [
                ['title' => '4 Lastik Değişimi',      'icon' => '🛞', 'labor' => 'Sökme, Takma, Subap & Nitrojen Basımı', 'price' => 600, 'color' => 'blue'],
                ['title' => 'Dinamik Balans Ayarı',   'icon' => '🎯', 'labor' => 'Kurşun Çakma & Hassas Balans', 'price' => 400, 'color' => 'amber'],
                ['title' => 'Rot Ayarı & Açı Testi',  'icon' => '📐', 'labor' => 'Ön Takım Kamber/Kaster & Rot Ayarı', 'price' => 450, 'color' => 'indigo'],
                ['title' => 'Lastik Yama & Fitil',    'icon' => '🩹', 'labor' => 'İçten Mantar Yama Tamiri', 'price' => 250, 'color' => 'emerald'],
            ],
            'Periyodik Hızlı Bakım' => [
                ['title' => 'Periyodik Yağ Bakımı',   'icon' => '🛢️', 'labor' => 'Motor Yağı, Yağ/Hava/Polen Filtresi Değişimi', 'price' => 600, 'color' => 'emerald'],
                ['title' => 'Sıvı & Kayış Kontrolleri','icon' => '🔍', 'labor' => 'Fren/Direksiyon Sıvısı & V Kayışı Kontrolü', 'price' => 350, 'color' => 'blue'],
                ['title' => 'Buji & Ateşleme Bakımı', 'icon' => '⚡', 'labor' => 'Buji Takımı Değişimi & Boğaz Temizliği', 'price' => 500, 'color' => 'amber'],
                ['title' => 'Kışlık / Yazlık Bakım',  'icon' => '❄️', 'labor' => '30 Nokta Genel Servis Check-Up', 'price' => 550, 'color' => 'indigo'],
            ],
            'Oto LPG & Gaz Sistemleri' => [
                ['title' => 'LPG Filtre & Genel Bakım','icon' => '⛽', 'labor' => 'LPG Sıvı/Gaz Filtresi Değişimi & Kaçak Testi', 'price' => 450, 'color' => 'emerald'],
                ['title' => 'LPG Gaz Kaçak Kontrolü', 'icon' => '🔍', 'labor' => 'Dedektör ile Boru & Tank Kaçak Taraması', 'price' => 300, 'color' => 'rose'],
                ['title' => 'LPG Enjektör Değişimi',  'icon' => '🧪', 'labor' => 'LPG Enjektör Kütüğü Değişimi & Kalibrasyon', 'price' => 850, 'color' => 'indigo'],
                ['title' => 'LPG Regülatör & Harita', 'icon' => '💻', 'labor' => 'Regülatör Değişimi & Yol Ayarı (AFR)', 'price' => 1200, 'color' => 'amber'],
            ],
        ];

        return $actions[$branchName] ?? [
            ['title' => 'Genel Arıza Tespiti', 'icon' => '🔍', 'labor' => 'Detaylı Kontrol & Arıza Tespiti', 'price' => 500, 'color' => 'blue'],
            ['title' => 'Periyodik Bakım',     'icon' => '🔧', 'labor' => 'Periyodik Servis & Kontrol',       'price' => 600, 'color' => 'emerald'],
            ['title' => 'Parça Değişimi',      'icon' => '⚙️', 'labor' => 'Yedek Parça Montaj İşçiliği',     'price' => 750, 'color' => 'indigo'],
            ['title' => 'Kontrol & Test',      'icon' => '📋', 'labor' => 'Güvenlik & Yol Testi',            'price' => 350, 'color' => 'amber'],
        ];
    }
}
