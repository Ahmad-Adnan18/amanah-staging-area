<div x-data="minimalSlider()" x-init="initSlider" @mouseenter="pauseAutoPlay" @mouseleave="resumeAutoPlay" @touchstart.passive="handleTouchStart" @touchmove.passive="handleTouchMove" @touchend="handleTouchEnd" class="minimal-slider">

    <!-- Slider Container -->
    <div class="slider-container" :style="getContainerStyle()">
        <template x-for="(item, index) in items" :key="item.id">

            <div class="slide">

                <!-- Image -->
                <template x-if="item.type === 'image'">
                    <img :src="item.file_url" :alt="item.title" class="slide-content" loading="lazy">
                </template>

                <!-- Video -->
                <template x-if="item.type === 'video'">
                    <video :src="item.file_url" class="slide-content" autoplay muted loop playsinline></video>
                </template>

                <!-- YouTube -->
                <template x-if="item.type === 'external'">
                    <div class="external-container">
                        <iframe :src="item.embed_url" class="external-content" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
                    </div>
                </template>

                <!-- [BARU] Judul Kegiatan di Ujung Kiri -->
                <div class="slide-title-overlay">
                    <span x-text="item.title"></span>
                </div>

            </div>
        </template>
    </div>

    <!-- Indicators -->
    <div x-show="items.length > 1" class="indicators">
        <template x-for="(item, index) in items" :key="`indicator-${item.id}`">
            <div @click="goToSlide(index)" :class="`indicator ${index === currentIndex ? 'active' : ''}`"></div>
        </template>
    </div>
</div>

<script>
    function minimalSlider() {
        return {
            items: []
            , currentIndex: 0
            , loaded: false
            , isAnimating: false, // Diganti dari 'sliding'
            autoPlay: true
            , autoPlayInterval: 5000
            , slideInterval: null,

            // Touch/Swipe Variables
            touchStartX: 0
            , touchMoveX: 0
            , dragOffset: 0
            , isDragging: false
            , minSwipeDistance: 50, // Minimal jarak geser untuk pindah slide

            async initSlider() {
                try {
                    const response = await fetch('/api/slider-items');
                    const result = await response.json();
                    this.items = result.success ? result.data : [];
                    this.loaded = true;

                    if (this.items.length > 1 && this.autoPlay) {
                        this.startAutoSlide();
                    }
                } catch (error) {
                    console.error('Error loading slider:', error);
                    this.loaded = true;
                }
            },

            // [NEW] Helper untuk mendapatkan lebar slide
            slideWidth() {
                // $el adalah elemen root komponen (div.minimal-slider)
                return this.$el.clientWidth;
            },

            // [NEW] Fungsi inti untuk mengatur style container
            getContainerStyle() {
                // Offset dasar berdasarkan slide saat ini
                let baseOffset = -this.currentIndex * this.slideWidth();
                // Offset total adalah offset dasar + tarikan jari
                let totalOffset = baseOffset + this.dragOffset;

                return {
                    transform: `translateX(${totalOffset}px)`,
                    // Transisi dimatikan saat dragging, dan dihidupkan saat snap/slide
                    transition: this.isDragging ? 'none' : 'transform 0.4s cubic-bezier(0.4, 0, 0.2, 1)'
                };
            },

            // === TOUCH EVENTS (MODIFIED) ===
            handleTouchStart(e) {
                if (this.isAnimating) return; // Jangan mulai jika sedang animasi
                this.isDragging = true;
                this.touchStartX = e.changedTouches[0].screenX;
                this.pauseAutoPlay();
            },

            handleTouchMove(e) {
                if (!this.isDragging) return;
                this.touchMoveX = e.changedTouches[0].screenX;
                this.dragOffset = this.touchMoveX - this.touchStartX;
            },

            handleTouchEnd(e) {
                if (!this.isDragging) return;
                this.isDragging = false;

                const swipeDistance = this.touchStartX - this.touchMoveX;

                // Cek apakah geserannya cukup jauh
                if (Math.abs(swipeDistance) > this.minSwipeDistance) {
                    if (swipeDistance > 0) {
                        this.nextSlide(); // Geser ke kiri -> next
                    } else {
                        this.prevSlide(); // Geser ke kanan -> prev
                    }
                }

                // Jika tidak cukup jauh, dragOffset = 0 akan membuatnya "snap back"
                this.dragOffset = 0;
                this.resumeAutoPlay();
            },

            // [REMOVED] handleSwipe() dan snapBack() digabung ke handleTouchEnd

            // === NAVIGASI (MODIFIED) ===
            // Fungsi ini sekarang hanya mengubah index dan state
            // Pergerakan visual diurus oleh getContainerStyle()

            nextSlide() {
                if (this.isAnimating || this.items.length <= 1) return;
                this.isAnimating = true;
                this.currentIndex = (this.currentIndex + 1) % this.items.length;
                this.resetAutoSlide();
                setTimeout(() => this.isAnimating = false, 400); // 400ms = durasi transisi
            },

            prevSlide() {
                if (this.isAnimating || this.items.length <= 1) return;
                this.isAnimating = true;
                this.currentIndex = (this.currentIndex - 1 + this.items.length) % this.items.length;
                this.resetAutoSlide();
                setTimeout(() => this.isAnimating = false, 400);
            },

            goToSlide(index) {
                if (this.isAnimating || index === this.currentIndex) return;
                this.isAnimating = true;
                this.currentIndex = index;
                this.resetAutoSlide();
                setTimeout(() => this.isAnimating = false, 400);
            },

            // === AUTOPLAY (Unchanged, but uses new nextSlide) ===
            startAutoSlide() {
                if (this.items.length <= 1 || !this.autoPlay) return;
                this.slideInterval = setInterval(() => this.nextSlide(), this.autoPlayInterval);
            }
            , resetAutoSlide() {
                this.clearAutoSlide();
                if (this.autoPlay) this.startAutoSlide();
            }
            , clearAutoSlide() {
                if (this.slideInterval) clearInterval(this.slideInterval);
                this.slideInterval = null;
            }
            , pauseAutoPlay() {
                this.autoPlay = false;
                this.clearAutoSlide();
            }
            , resumeAutoPlay() {
                if (this.items.length > 1 && !this.isDragging) { // Hanya resume jika tidak sedang di-drag
                    this.autoPlay = true;
                    this.startAutoSlide();
                }
            }
        }
    }

</script>

<style>
    .minimal-slider {
        position: relative;
        width: 100%;
        height: 400px;
        overflow: hidden;
        /* PENTING: Sembunyikan slide di samping */
        border-radius: 8px;
        background: #000;
        /* Izinkan scroll vertikal, tapi tangkap swipe horizontal */
        touch-action: pan-y;
        -webkit-user-select: none;
        user-select: none;
        /* 2px solid darkred */
        border: 4px solid #8B0000;
        /* Pastikan border tidak menambah ukuran elemen */
        box-sizing: border-box;
    }

    .slider-container {
        /* [MODIFIED] Menggunakan flexbox */
        display: flex;
        width: 100%;
        height: 100%;
        position: relative;
        /* Transisi akan di-handle oleh inline style dari AlpineJS */
    }

    .slide {
        /* [MODIFIED] Mengatur agar tiap slide memenuhi container flex */
        flex-shrink: 0;
        width: 100%;
        height: 100%;
        position: relative;
        /* Agar konten internal bisa di-posisikan */
        display: flex;
        align-items: center;
        justify-content: center;
        /* [REMOVED] position: absolute, opacity, transition, z-index */
    }

    /* [REMOVED] .slide.active tidak lagi diperlukan untuk transisi */

    /* [REMOVED] Semua class .fade-enter-* dan .fade-leave-* dihapus */

    .slide-content {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0;
        left: 0;
    }

    .external-container {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #000;
        position: relative;
        overflow: hidden;
    }

    .external-content {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 100%;
        height: 100%;
        min-width: 110%;
        min-height: 110%;
        border: none;
        transform: translate(-50%, -50%);
    }

    /* Indicators (Tidak berubah) */
    .indicators {
        position: absolute;
        bottom: 1rem;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 0.5rem;
        z-index: 10;
    }

    .indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.4);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .indicator.active {
        background: white;
        transform: scale(1.2);
    }

    .indicator:hover {
        background: rgba(255, 255, 255, 0.8);
        transform: scale(1.1);
    }

    .slide-title-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        /* Padding: atas 1.5rem, kiri/kanan 1rem, bawah 1rem */
        padding: 1.5rem 1rem 1rem;
        z-index: 5;

        /* Gradien modern agar teks mudah dibaca di atas gambar apapun */
        background: linear-gradient(to top,
                rgba(0, 0, 0, 0.6) 0%,
                /* Lebih gelap di bawah */
                rgba(0, 0, 0, 0.4) 50%,
                transparent 100%
                /* Transparan di atas */
            );

        color: white;
        font-size: 0.875rem;
        /* 14px */
        font-weight: 500;

        /* Mencegah teks terseleksi saat swipe */
        -webkit-user-select: none;
        user-select: none;

        /* Bayangan teks tipis untuk kontras */
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);

        /* Jika judul terlalu panjang, tampilkan ... */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;

        /* Pastikan tidak mengganggu event sentuhan */
        pointer-events: none;
    }

    /* Sesuaikan ukuran font dan padding di mobile */
    @media (max-width: 768px) {
        .slide-title-overlay {
            font-size: 0.75rem;
            /* 12px */
            padding: 1rem 0.75rem 0.75rem;
        }
    }

    /* Responsive (Tidak berubah) */
    @media (max-width: 768px) {
        .minimal-slider {
            height: 250px;
            border-radius: 6px;
        }

        .indicators {
            bottom: 0.5rem;
        }

        .indicator {
            width: 6px;
            height: 6px;
        }
    }

    @media (max-width: 480px) {
        .minimal-slider {
            height: 200px;
        }
    }

    /* [BARU] Sembunyikan slider di layar desktop (lebih besar dari 768px) */
    @media (min-width: 769px) {
        .minimal-slider {
            display: none;
        }
    }

</style>
