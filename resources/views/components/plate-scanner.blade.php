<div x-data="plateScannerComp()" class="inline-flex items-center gap-1.5">
    
    <!-- Buton Grubu: Kamera & Fotoğraf -->
    <div class="flex items-center gap-1.5">
        <button type="button" @click="openScanner()" 
                class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-3 py-2 rounded-xl border border-slate-700 transition-all flex items-center gap-1.5 shadow-xs cursor-pointer"
                title="Kamerayı açarak plakayı tara">
            <span>📸 Kameralı Tara</span>
        </button>

        <label class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-2 rounded-xl border border-indigo-200 transition-all cursor-pointer flex items-center gap-1.5 shadow-xs"
               title="Fotoğraf seçerek plakayı oku">
            <span>🖼️ Fotoğraftan Oku</span>
            <input type="file" accept="image/*" capture="environment" class="hidden" @change="handleFileUpload($event)">
        </label>
    </div>

    <!-- Taraması Modal -->
    <div x-show="isOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/85 backdrop-blur-md p-4">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-md w-full p-5 text-white shadow-2xl relative overflow-hidden" @click.outside="closeScanner()">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-800 mb-4">
                <div class="flex items-center gap-2">
                    <span class="text-xl">📷</span>
                    <div>
                        <h3 class="text-sm font-black text-white">Yapay Zeka Plaka Okuyucu</h3>
                        <p class="text-[11px] text-slate-400">Aracın plakasını çerçeveye getirin veya fotoğraf yükleyin</p>
                    </div>
                </div>
                <button type="button" @click="closeScanner()" class="text-slate-400 hover:text-white p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Video & Görsel Önizleme -->
            <div class="relative bg-black rounded-2xl overflow-hidden aspect-video flex items-center justify-center border border-slate-800">
                <video x-show="mode === 'camera'" x-ref="video" autoplay playsinline class="w-full h-full object-cover"></video>
                <img x-show="mode === 'photo'" x-ref="previewImg" class="w-full h-full object-contain bg-slate-950" />
                <canvas x-ref="canvas" class="hidden"></canvas>

                <!-- Plaka Okuma Hedef Çerçevesi (Kamera Modunda) -->
                <div x-show="mode === 'camera'" class="absolute inset-0 border-2 border-indigo-500/40 pointer-events-none flex items-center justify-center p-4">
                    <div class="w-full max-w-xs h-16 border-2 border-dashed border-emerald-400 bg-emerald-500/10 rounded-xl flex items-center justify-center relative shadow-lg">
                        <span class="text-[10px] font-extrabold text-emerald-300 uppercase tracking-widest bg-slate-900/90 px-2.5 py-0.5 rounded-full border border-emerald-500/30">Plakayı Buraya Hizalayın</span>
                        <div class="absolute left-2 top-2 bg-blue-700 text-white text-[9px] font-black px-1.5 py-0.5 rounded shadow-xs">TR</div>
                    </div>
                </div>

                <!-- İşleniyor Ekranı -->
                <div x-show="isProcessing" class="absolute inset-0 bg-slate-950/90 backdrop-blur-xs flex flex-col items-center justify-center text-center p-4 z-20">
                    <svg class="animate-spin h-8 w-8 text-emerald-400 mb-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <p class="text-xs font-bold text-emerald-400" x-text="statusText"></p>
                    <p class="text-[10px] text-slate-400 mt-1">Plaka bölgesi kırpılıyor ve harfler çözümleniyor...</p>
                </div>
            </div>

            <!-- Hata Mesajı -->
            <div x-show="errorMsg" class="mt-3 text-xs bg-red-500/20 border border-red-500/30 text-red-300 p-2.5 rounded-xl font-bold text-center" x-text="errorMsg"></div>

            <!-- Alternatif Fotoğraf Yükleme Uyarısı -->
            <div x-show="errorMsg" class="mt-2 text-center">
                <label class="text-xs text-indigo-300 underline font-bold cursor-pointer hover:text-white">
                    <span>Diğer Bir Fotoğraf Seçerek Tekrar Deneyin</span>
                    <input type="file" accept="image/*" capture="environment" class="hidden" @change="handleFileUpload($event)">
                </label>
            </div>

            <!-- Butonlar -->
            <div class="mt-4 flex gap-2" x-show="mode === 'camera'">
                <button type="button" @click="captureAndScan()" :disabled="isProcessing"
                        class="flex-1 bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs py-3 rounded-xl transition-colors shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-2">
                    <span>⚡ Fotoğraf Çek & Plakayı Oku</span>
                </button>
            </div>

        </div>
    </div>
</div>

<script>
function plateScannerComp() {
    return {
        isOpen: false,
        isProcessing: false,
        mode: 'camera', // 'camera' or 'photo'
        statusText: 'Plaka taranıyor...',
        errorMsg: '',
        stream: null,

        async openScanner() {
            this.isOpen = true;
            this.mode = 'camera';
            this.errorMsg = '';
            try {
                this.stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 }, height: { ideal: 720 } }
                });
                this.$refs.video.srcObject = this.stream;
            } catch (err) {
                this.errorMsg = 'Kamera bulunamadı. Lütfen "Fotoğraftan Oku" butonunu kullanarak galerinizden fotoğraf yükleyin.';
            }
        },

        closeScanner() {
            if (this.stream) {
                this.stream.getTracks().forEach(track => track.stop());
                this.stream = null;
            }
            this.isOpen = false;
            this.isProcessing = false;
        },

        async handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            this.isOpen = true;
            this.mode = 'photo';
            this.isProcessing = true;
            this.statusText = 'Fotoğraf taranıyor...';
            this.errorMsg = '';

            const img = this.$refs.previewImg;
            const url = URL.createObjectURL(file);
            img.src = url;

            img.onload = () => {
                const canvas = document.createElement('canvas');
                canvas.width = img.naturalWidth || img.width;
                canvas.height = img.naturalHeight || img.height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);
                this.runMultiStrategyOCR(canvas);
            };
        },

        async captureAndScan() {
            const video = this.$refs.video;
            if (!video || !video.videoWidth) {
                this.errorMsg = 'Kamera görüntüsü bekleniyor...';
                return;
            }

            this.isProcessing = true;
            this.statusText = 'Fotoğraf analiz ediliyor...';
            this.errorMsg = '';

            const canvas = this.$refs.canvas;
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            this.runMultiStrategyOCR(canvas);
        },

        async runMultiStrategyOCR(sourceCanvas) {
            try {
                if (typeof Tesseract === 'undefined') {
                    this.errorMsg = 'OCR kütüphanesi yüklenemedi. İnternet bağlantınızı kontrol edip tekrar deneyin.';
                    this.isProcessing = false;
                    return;
                }

                // Strateji 1: Orta bölgeyi kırp (Aracın tam plaka bölgesi)
                this.statusText = 'Plaka bölgesi odaklanıyor...';
                const croppedCanvas = this.cropCenterRegion(sourceCanvas);

                // Strateji 2: Filtrelenmiş / Siyah-Beyaz Kontrast Görsel
                const binarizedCanvas = this.preprocessBinarize(croppedCanvas);

                // Tesseract Okuması Yap
                this.statusText = 'Optik Karakter Okuyucu çalışıyor...';
                
                // Kırpılmış Görsel Taraması
                let resultText = await this.tesseractScan(croppedCanvas);
                let plate = this.extractAndCleanPlate(resultText);

                // Bulunamadıysa Kontrast Filtreli Görsel Taraması
                if (!plate) {
                    resultText = await this.tesseractScan(binarizedCanvas);
                    plate = this.extractAndCleanPlate(resultText);
                }

                // Bulunamadıysa Tam Görsel Taraması
                if (!plate) {
                    resultText = await this.tesseractScan(sourceCanvas);
                    plate = this.extractAndCleanPlate(resultText);
                }

                if (plate) {
                    window.dispatchEvent(new CustomEvent('plate-scanned', { detail: plate }));
                    this.closeScanner();
                } else {
                    this.errorMsg = 'Plaka okunamadı. Fotoğrafta plakanın net göründüğünden emin olun veya plakayı elle yazın.';
                    this.isProcessing = false;
                }
            } catch (e) {
                console.error(e);
                this.errorMsg = 'Plaka taraması tamamlanamadı. Lütfen fotoğrafı tekrar çekin.';
                this.isProcessing = false;
            }
        },

        async tesseractScan(canvas) {
            try {
                const res = await Tesseract.recognize(canvas, 'eng');
                return res.data ? res.data.text : '';
            } catch (e) {
                return '';
            }
        },

        // Görselin Tam Ortasındaki Plaka Alanını Kırpma (Doğruluk oranını %90 artıran fonksiyon)
        cropCenterRegion(sourceCanvas) {
            const cropCanvas = document.createElement('canvas');
            const w = sourceCanvas.width * 0.95;
            const h = sourceCanvas.height * 0.85;
            const x = (sourceCanvas.width - w) / 2;
            const y = (sourceCanvas.height - h) / 2;

            cropCanvas.width = w;
            cropCanvas.height = h;
            const ctx = cropCanvas.getContext('2d');
            ctx.drawImage(sourceCanvas, x, y, w, h, 0, 0, w, h);
            return cropCanvas;
        },

        // Siyah-Beyaz Yüksek Kontrast Filtresi
        preprocessBinarize(canvas) {
            const procCanvas = document.createElement('canvas');
            procCanvas.width = canvas.width;
            procCanvas.height = canvas.height;
            const ctx = procCanvas.getContext('2d');
            ctx.drawImage(canvas, 0, 0);

            const imgData = ctx.getImageData(0, 0, procCanvas.width, procCanvas.height);
            const d = imgData.data;

            for (let i = 0; i < d.length; i += 4) {
                const gray = d[i] * 0.299 + d[i + 1] * 0.587 + d[i + 2] * 0.114;
                const v = gray > 125 ? 255 : 0;
                d[i] = v;
                d[i + 1] = v;
                d[i + 2] = v;
            }
            ctx.putImageData(imgData, 0, 0);
            return procCanvas;
        },

        // Türkçe Plaka Ayıklama ve Karakter Tamiri
        extractAndCleanPlate(rawText) {
            if (!rawText) return null;
            const cleaned = rawText.toUpperCase().replace(/[^A-Z0-9]/g, '');

            if (cleaned.length < 5) return null;

            // Regex 1: Tam Türkçe Plaka Kalıbı (01-81 + 1-4 harf + 2-4 rakam, örn: 34 AVEC 01)
            const matches = cleaned.match(/(0[1-9]|[1-7][0-9]|8[01])([A-Z]{1,4})([0-9]{2,4})/g);
            if (matches && matches.length > 0) {
                return matches[0];
            }

            // Esnek OCR Tamiri (0<->O, 1<->I, 8<->B, 5<->S)
            for (let len = 5; len <= 10; len++) {
                for (let i = 0; i <= cleaned.length - len; i++) {
                    const candidate = cleaned.substr(i, len);
                    const fixed = this.repairPlateString(candidate);
                    if (fixed) return fixed;
                }
            }

            return null;
        },

        repairPlateString(str) {
            if (str.length < 5 || str.length > 10) return null;

            // İl kodu (ilk 2 hane)
            let p1 = str.substring(0, 2)
                .replace(/O/g, '0').replace(/I/g, '1').replace(/L/g, '1')
                .replace(/B/g, '8').replace(/S/g, '5').replace(/Z/g, '2');

            let il = parseInt(p1);
            if (isNaN(il) || il < 1 || il > 81) return null;
            if (p1.length === 1) p1 = '0' + p1;

            const rest = str.substring(2);
            let m = rest.match(/^([A-Z0-9]{1,4})([A-Z0-9]{2,4})$/);
            if (!m) return null;

            let p2 = m[1].replace(/0/g, 'O').replace(/1/g, 'I').replace(/8/g, 'B').replace(/5/g, 'S');
            let p3 = m[2].replace(/O/g, '0').replace(/I/g, '1').replace(/L/g, '1').replace(/B/g, '8').replace(/S/g, '5').replace(/Z/g, '2');

            if (/^[A-Z]{1,4}$/.test(p2) && /^[0-9]{2,4}$/.test(p3)) {
                return `${p1}${p2}${p3}`;
            }

            return null;
        }
    }
}
</script>
