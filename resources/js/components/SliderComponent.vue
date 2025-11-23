<template>
  <div class="minimal-slider" @mouseenter="pauseAutoPlay" @mouseleave="resumeAutoPlay">
    <!-- Slider Container -->
    <div class="slider-container">
      <transition-group name="fade" tag="div" class="slides-container">
        <div 
          v-for="(item, index) in items" 
          :key="item.id"
          :class="`slide ${index === currentIndex ? 'active' : ''}`"
        >
          <!-- Image Content -->
          <template v-if="item.type === 'image'">
            <img 
              :src="item.file_url" 
              :alt="item.title" 
              class="slide-content" 
              loading="lazy"
            />
          </template>

          <!-- Video Content -->
          <template v-if="item.type === 'video'">
            <video 
              :src="item.file_url" 
              class="slide-content" 
              autoplay 
              muted 
              loop 
              playsinline
            ></video>
          </template>

          <!-- External Content (YouTube) -->
          <template v-if="item.type === 'external'">
            <div class="external-container">
              <iframe 
                :src="item.embed_url" 
                class="external-content" 
                frameborder="0" 
                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen
                loading="lazy"
              ></iframe>
            </div>
          </template>
        </div>
      </transition-group>
    </div>

    <!-- Simple indicators -->
    <div v-if="items.length > 1" class="indicators">
      <div 
        v-for="(item, index) in items" 
        :key="`indicator-${item.id}`"
        @click="goToSlide(index)"
        :class="`indicator ${index === currentIndex ? 'active' : ''}`"
      ></div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SliderComponent',
  props: {
    sliderData: {
      type: Array,
      default: () => []
    },
    autoPlayInterval: {
      type: Number,
      default: 5000
    }
  },
  data() {
    return {
      items: [],
      currentIndex: 0,
      loaded: false,
      sliding: false,
      autoPlay: true,
      slideInterval: null
    };
  },
  mounted() {
    console.log('Slider component mounted with data:', this.sliderData);
    if (this.sliderData && this.sliderData.length > 0) {
      console.log('Using data passed from props');
      this.items = this.sliderData;
      this.loaded = true;
      if (this.autoPlay && this.items.length > 1) {
        this.startAutoSlide();
      }
    } else {
      console.log('No data from props, fetching from API');
      this.initSlider();
    }
  },
  beforeUnmount() {
    this.clearAutoSlide();
  },
  methods: {
    async initSlider() {
      try {
        console.log('Attempting to fetch slider data from API');
        // Fetch from API (we already handle props in mounted hook)
        const response = await fetch('/api/slider-items');
        console.log('API response status:', response.status);
        
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        console.log('API response data:', result);
        
        if (result.success && result.data) {
          this.items = result.data;
          console.log('Slider items loaded from API:', this.items);
        } else {
          console.error('API response format is not as expected:', result);
          this.items = [];
        }

        if (this.items.length > 0) {
          this.loaded = true;
          if (this.autoPlay && this.items.length > 1) {
            this.startAutoSlide();
          }
        } else {
          this.loaded = true; // Show empty state
          console.warn('No slider items found from API');
        }
      } catch (error) {
        console.error('Error loading slider items from API:', error);
        this.loaded = true; // Show empty state instead of loading forever
      }
    },
    nextSlide() {
      if (this.sliding || this.items.length <= 1) return;

      this.sliding = true;
      const nextIndex = (this.currentIndex + 1) % this.items.length;
      this.currentIndex = nextIndex;

      this.resetAutoSlide();
      setTimeout(() => {
        this.sliding = false;
      }, 500); // Reduced transition time for smoother feel
    },
    prevSlide() {
      if (this.sliding || this.items.length <= 1) return;

      this.sliding = true;
      const prevIndex = (this.currentIndex - 1 + this.items.length) % this.items.length;
      this.currentIndex = prevIndex;

      this.resetAutoSlide();
      setTimeout(() => {
        this.sliding = false;
      }, 500); // Reduced transition time for smoother feel
    },
    goToSlide(index) {
      if (this.sliding || index === this.currentIndex) return;

      this.sliding = true;
      this.currentIndex = index;

      this.resetAutoSlide();
      setTimeout(() => {
        this.sliding = false;
      }, 500); // Reduced transition time for smoother feel
    },
    startAutoSlide() {
      if (this.items.length <= 1 || !this.autoPlay) return;

      this.slideInterval = setInterval(() => {
        this.nextSlide();
      }, this.autoPlayInterval);
    },
    resetAutoSlide() {
      this.clearAutoSlide();
      if (this.autoPlay) {
        this.startAutoSlide();
      }
    },
    clearAutoSlide() {
      if (this.slideInterval) {
        clearInterval(this.slideInterval);
        this.slideInterval = null;
      }
    },
    pauseAutoPlay() {
      this.autoPlay = false;
      this.clearAutoSlide();
    },
    resumeAutoPlay() {
      if (this.items.length > 1) {
        this.autoPlay = true;
        this.startAutoSlide();
      }
    }
  },
  watch: {
    sliderData: {
      handler(newData) {
        this.items = newData;
        if (this.items.length > 1) {
          this.startAutoSlide();
        }
      },
      deep: true
    }
  }
};
</script>

<style scoped>
.minimal-slider {
  position: relative;
  width: 100%;
  height: 400px;
  overflow: hidden;
  border-radius: 8px;
  background: #000;
}

.slider-container {
  width: 100%;
  height: 100%;
  overflow: hidden;
  position: relative;
}

.slides-container {
  width: 100%;
  height: 100%;
  position: relative;
}

.slide {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  transition: opacity 0.5s ease-in-out;
  display: flex;
  align-items: center;
  justify-content: center;
}

.slide.active {
  opacity: 1;
  z-index: 2;
}

/* Transisi fade */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.slide-content {
  width: 100% !important;
  height: 100% !important;
  object-fit: cover !important;
  /* Pastikan gambar/video mengisi kontainer penuh */
  min-width: 100% !important;
  min-height: 100% !important;
  max-width: 100% !important;
  max-height: 100% !important;
  display: block !important;
  position: absolute !important;
  top: 0 !important;
  left: 0 !important;
}

.external-container {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #000;
}

.external-content {
  position: absolute !important;
  top: 0 !important;
  left: 0 !important;
  width: 100% !important;
  height: 100% !important;
  min-width: 110% !important;
  min-height: 110% !important;
  border: none !important;
  object-fit: cover !important;
  transform: translate(-50%, -50%) !important;
  top: 50% !important;
  left: 50% !important;
  /* Pastikan iframe mengisi kontainer */
  display: block !important;
}

.indicators {
  position: absolute;
  bottom: 1rem;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 0.5rem;
  z-index: 10;
  background: transparent;
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

/* Responsive Design */
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
</style>