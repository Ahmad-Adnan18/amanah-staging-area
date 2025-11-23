@props([
    'round' => false,
    'autoplay' => true,
    'pauseOnHover' => true,
    'loop' => true,
    'autoplayDelay' => 3500,
    'gap' => 16,
    'containerPadding' => 24,
])

@php
$config = json_encode([
    'round' => (bool) $round,
    'autoplay' => (bool) $autoplay,
    'pauseOnHover' => (bool) $pauseOnHover,
    'loop' => (bool) $loop,
    'autoplayDelay' => (int) $autoplayDelay,
    'gap' => (int) $gap,
    'containerPadding' => (int) $containerPadding,
]);
@endphp

<div
    x-data="carouselSlider()"
    x-init="initSlider($el.dataset.carouselConfig)"
    x-cloak
    data-carousel-config='{{ $config }}'
    @mouseenter="handleHover(true)"
    @mouseleave="handleHover(false)"
    {{ $attributes->merge(['class' => 'carousel-shell']) }}
    :class="round ? 'carousel-shell--round' : ''"
>
    <div class="carousel-viewport" x-ref="viewport">
        <template x-if="carouselItems.length">
            <div class="carousel-track" x-ref="track" :style="getTrackStyle()" @pointerdown.prevent="handlePointerDown" @pointermove.prevent="handlePointerMove" @pointerup="handlePointerUp" @pointerleave="handlePointerLeave" @pointercancel="handlePointerUp" @transitionend="handleTransitionEnd">
                <template x-for="(item, index) in carouselItems" :key="`slide-${index}-${item.id ?? index}`">
                    <article class="carousel-slide" :class="round ? 'carousel-slide--round' : ''" :style="getSlideStyle(index)">
                        <div class="carousel-media">
                            <template x-if="item.type === 'image'">
                                <img :src="item.file_url" :alt="item.title" class="carousel-media__asset" loading="lazy" decoding="async">
                            </template>
                            <template x-if="item.type === 'video'">
                                <video :src="item.file_url" class="carousel-media__asset" autoplay muted loop playsinline></video>
                            </template>
                            <template x-if="item.type === 'external'">
                                <div class="carousel-external">
                                    <iframe :src="item.embed_url" class="carousel-media__asset" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
                                </div>
                            </template>
                        </div>
                        <div class="carousel-content">
                            <div class="carousel-chip">
                                <span x-text="getSlideInitial(item)"></span>
                            </div>
                            <h3 class="carousel-title" x-text="item.title ?? 'Highlight'">Highlight</h3>
                            <p class="carousel-description" x-show="getSlideDescription(item)" x-text="getSlideDescription(item)"></p>
                        </div>
                    </article>
                </template>
            </div>
        </template>

        <template x-if="loaded && !carouselItems.length">
            <div class="carousel-empty-state">
                <p>Tidak ada konten slider untuk ditampilkan.</p>
            </div>
        </template>
    </div>

    <div x-show="items.length > 1" class="carousel-indicators">
        <template x-for="(item, index) in items" :key="`indicator-${item.id ?? index}`">
            <button type="button" class="carousel-indicator" :class="indicatorClass(index)" @click="goToSlide(index)" :aria-label="`Tampilkan slide ${index + 1}`"></button>
        </template>
    </div>
</div>

<script>
    function carouselSlider() {
        return {
            items: [],
            carouselItems: [],
            currentIndex: 0,
            autoplay: true,
            autoplayDelay: 3500,
            pauseOnHover: true,
            loop: true,
            round: false,
            gap: 16,
            containerPadding: 24,
            dragStartX: 0,
            dragOffset: 0,
            isDragging: false,
            isHovered: false,
            isResetting: false,
            slideInterval: null,
            viewportWidth: 0,
            transitionMs: 500,
            loaded: false,
            resizeHandler: null,

            async initSlider(configJson = null) {
                if (configJson) {
                    try {
                        const config = JSON.parse(configJson);
                        Object.assign(this, {
                            round: config.round ?? this.round,
                            autoplay: config.autoplay ?? this.autoplay,
                            pauseOnHover: config.pauseOnHover ?? this.pauseOnHover,
                            loop: config.loop ?? this.loop,
                            autoplayDelay: config.autoplayDelay ?? this.autoplayDelay,
                            gap: config.gap ?? this.gap,
                            containerPadding: config.containerPadding ?? this.containerPadding,
                        });
                    } catch (error) {
                        console.warn('Carousel config parsing failed:', error);
                    }
                }

                this.resizeHandler = this.measure.bind(this);
                await this.fetchItems();
                this.$nextTick(() => {
                    this.measure();
                    window.addEventListener('resize', this.resizeHandler, { passive: true });
                });
            },

            async fetchItems() {
                try {
                    const response = await fetch('/api/slider-items');
                    const result = await response.json();
                    this.items = Array.isArray(result.data) && result.success ? result.data : [];
                } catch (error) {
                    console.error('Error loading slider:', error);
                    this.items = [];
                } finally {
                    this.loaded = true;
                    this.buildCarouselItems();
                    this.startAutoplay();
                }
            },

            buildCarouselItems() {
                if (this.loop && this.items.length > 1) {
                    const first = this.items[0] ?? {};
                    const clone = { ...first, id: `clone-${first.id ?? '0'}` };
                    this.carouselItems = [...this.items, clone];
                } else {
                    this.carouselItems = [...this.items];
                }

                if (this.currentIndex >= this.carouselItems.length && this.carouselItems.length > 0) {
                    this.currentIndex = 0;
                }
            },

            teardown() {
                this.clearAutoplay();
                if (this.resizeHandler) {
                    window.removeEventListener('resize', this.resizeHandler);
                }
            },

            measure() {
                if (!this.$refs.viewport) return;
                this.viewportWidth = this.$refs.viewport.clientWidth;
            },

            itemWidth() {
                if (!this.viewportWidth) {
                    return 320;
                }

                if (this.round) {
                    return Math.min(this.viewportWidth, 420);
                }

                const innerWidth = Math.max(this.viewportWidth - this.containerPadding * 2, 260);
                return Math.min(innerWidth, 460);
            },

            trackItemOffset() {
                return this.itemWidth() + this.gap;
            },

            currentIndexWithDrag() {
                if (!this.trackItemOffset()) return this.currentIndex;
                return this.currentIndex - this.dragOffset / this.trackItemOffset();
            },

            getTrackStyle() {
                const offset = -(this.currentIndex * this.trackItemOffset()) + this.dragOffset;
                return {
                    transform: `translate3d(${offset}px, 0, 0)`,
                    gap: `${this.gap}px`,
                    padding: `0 ${this.containerPadding}px`,
                    transition: this.isDragging || this.isResetting ? 'none' : `transform ${this.transitionMs}ms cubic-bezier(0.2, 0.8, 0.2, 1)`
                };
            },

            getSlideStyle(index) {
                const width = this.itemWidth();
                const fractional = this.currentIndexWithDrag();
                const delta = index - fractional;
                const rotate = Math.max(-90, Math.min(90, delta * 35));
                const opacity = Math.abs(delta) >= 2 ? 0.35 : 1;

                return {
                    width: `${width}px`,
                    height: this.round ? `${width}px` : 'auto',
                    transform: `perspective(1200px) rotateY(${rotate}deg)` + (this.round ? '' : ' translateZ(0)'),
                    opacity,
                    transition: this.isDragging
                        ? 'transform 0s linear, opacity 0s linear'
                        : `transform ${this.transitionMs}ms cubic-bezier(0.2, 0.8, 0.2, 1), opacity ${this.transitionMs}ms ease`
                };
            },

            handlePointerDown(event) {
                if (!this.carouselItems.length) return;
                this.isDragging = true;
                this.dragStartX = event.clientX;
                this.dragOffset = 0;
                this.clearAutoplay();
                event.target.setPointerCapture?.(event.pointerId);
            },

            handlePointerMove(event) {
                if (!this.isDragging) return;
                this.dragOffset = event.clientX - this.dragStartX;
            },

            handlePointerUp(event) {
                if (!this.isDragging) return;
                this.isDragging = false;
                event.target.releasePointerCapture?.(event.pointerId);
                const moved = this.snapAfterDrag();
                this.dragOffset = 0;
                if (!moved) {
                    this.startAutoplay();
                }
            },

            handlePointerLeave(event) {
                if (this.isDragging) {
                    this.handlePointerUp(event);
                }
            },

            snapAfterDrag() {
                const threshold = Math.min(140, this.itemWidth() * 0.25);
                if (this.dragOffset < -threshold) {
                    this.nextSlide();
                    return true;
                }

                if (this.dragOffset > threshold) {
                    this.prevSlide();
                    return true;
                }

                return false;
            },

            handleTransitionEnd(event) {
                if (event.target !== this.$refs.track || event.propertyName !== 'transform') return;
                if (!this.loop || this.carouselItems.length <= this.items.length) return;

                const lastIndex = this.carouselItems.length - 1;
                if (this.currentIndex === lastIndex) {
                    this.isResetting = true;
                    this.currentIndex = 0;
                    this.dragOffset = 0;
                    requestAnimationFrame(() => {
                        requestAnimationFrame(() => {
                            this.isResetting = false;
                        });
                    });
                }
            },

            nextSlide() {
                if (!this.carouselItems.length) return;
                const lastIndex = this.carouselItems.length - 1;
                if (this.currentIndex >= lastIndex) {
                    if (!this.loop) return;
                    this.currentIndex = lastIndex;
                } else {
                    this.currentIndex += 1;
                }
                this.resetAutoplay();
            },

            prevSlide() {
                if (!this.carouselItems.length) return;
                if (this.currentIndex === 0) {
                    if (!this.loop) return;
                    this.isResetting = true;
                    this.currentIndex = Math.max(this.items.length - 1, 0);
                    requestAnimationFrame(() => {
                        requestAnimationFrame(() => {
                            this.isResetting = false;
                        });
                    });
                } else {
                    this.currentIndex -= 1;
                }
                this.resetAutoplay();
            },

            goToSlide(index) {
                if (!this.items.length) return;
                const safeIndex = Math.max(0, Math.min(index, this.items.length - 1));
                this.currentIndex = safeIndex;
                this.resetAutoplay();
            },

            handleHover(state) {
                if (!this.pauseOnHover) return;
                this.isHovered = state;
                if (state) {
                    this.clearAutoplay();
                } else {
                    this.startAutoplay();
                }
            },

            startAutoplay() {
                if (!this.autoplay || this.items.length <= 1) {
                    this.clearAutoplay();
                    return;
                }

                this.clearAutoplay();
                this.slideInterval = setInterval(() => {
                    if (this.pauseOnHover && this.isHovered) return;
                    this.nextSlide();
                }, this.autoplayDelay);
            },

            clearAutoplay() {
                if (this.slideInterval) {
                    clearInterval(this.slideInterval);
                    this.slideInterval = null;
                }
            },

            resetAutoplay() {
                if (!this.autoplay || this.items.length <= 1) return;
                this.startAutoplay();
            },

            indicatorClass(index) {
                return this.currentIndicatorIndex() === index ? 'is-active' : '';
            },

            currentIndicatorIndex() {
                if (!this.items.length) return 0;
                return this.currentIndex % this.items.length;
            },

            getSlideDescription(item) {
                return item?.description || item?.subtitle || item?.caption || '';
            },

            getSlideInitial(item) {
                const source = item?.initial || item?.category || item?.title;
                return source ? source.toString().trim().charAt(0).toUpperCase() : '•';
            }
        }
    }

</script>

<style>
    .carousel-shell {
        position: relative;
        width: 100%;
        overflow: hidden;
        border-radius: 24px;
        border: none;
        background: transparent;
        color: #f5f5f5;
        touch-action: pan-y;
        user-select: none;
        padding: 1.5rem 0;
    }

    .carousel-shell--round {
        border-radius: 999px;
        padding: 2rem 0;
        border: none;
        background: transparent;
    }

    .carousel-viewport {
        width: 100%;
        overflow: hidden;
        position: relative;
    }

    .carousel-track {
        display: flex;
        align-items: stretch;
        width: max-content;
        cursor: grab;
        touch-action: pan-y;
        perspective: 1200px;
    }

    .carousel-track:active {
        cursor: grabbing;
    }

    .carousel-slide {
        position: relative;
        flex-shrink: 0;
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        overflow: hidden;
        min-height: 320px;
        background: rgba(255, 255, 255, 0.02);
        backdrop-filter: blur(12px);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
    }

    .carousel-shell--round .carousel-slide {
        border-radius: 50%;
        min-height: auto;
        aspect-ratio: 1 / 1;
    }

    .carousel-media {
        position: absolute;
        inset: 0;
        overflow: hidden;
    }

    .carousel-media__asset {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .carousel-external {
        width: 100%;
        height: 100%;
    }

    .carousel-content {
        position: relative;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        z-index: 2;
    }

    .carousel-chip {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        color: #fff;
    }

    .carousel-title {
        font-size: 1.25rem;
        font-weight: 800;
        margin: 0;
        color: #fff;
        letter-spacing: -0.02em;
    }

    .carousel-description {
        font-size: 0.95rem;
        margin: 0;
        color: rgba(255, 255, 255, 0.85);
    }

    .carousel-empty-state {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 240px;
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.95rem;
    }

    .carousel-indicators {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .carousel-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        border: none;
        background: rgba(255, 255, 255, 0.35);
        cursor: pointer;
        transition: transform 0.2s ease, background-color 0.2s ease;
    }

    .carousel-indicator.is-active {
        background: #fff;
        transform: scale(1.2);
    }

    .carousel-indicator:focus-visible {
        outline: 2px solid #fff;
        outline-offset: 3px;
    }

    @media (max-width: 768px) {
        .carousel-shell {
            border-radius: 18px;
            padding: 1rem 0;
        }

        .carousel-slide {
            min-height: 260px;
        }

        .carousel-content {
            padding: 1.25rem;
        }

        .carousel-title {
            font-size: 1.1rem;
        }

        .carousel-chip {
            width: 40px;
            height: 40px;
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .carousel-track,
        .carousel-slide {
            transition: none !important;
        }
    }

</style>
