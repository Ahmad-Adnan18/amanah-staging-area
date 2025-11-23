<template>
  <div class="simple-slider" 
       style="position: relative !important; width: 100% !important; height: 400px !important; overflow: hidden !important; border-radius: 8px !important; background: #000 !important;"
       @mouseenter="pauseAutoPlay" @mouseleave="resumeAutoPlay">
    <!-- Slides Container -->
    <div class="slides-container" 
         style="width: 100% !important; height: 100% !important; position: relative !important; overflow: hidden !important;">
      <div 
        v-for="(item, index) in items" 
        :key="item.id"
        :class="`slide ${index === currentIndex ? 'active' : ''}`"
        style="position: absolute !important; top: 0 !important; left: 0 !important; width: 100% !important; height: 100% !important; opacity: 0 !important; transition: opacity 0.5s ease-in-out !important; display: flex !important; align-items: center !important; justify-content: center !important;"
      >
        <!-- Image Content -->
        <template v-if="item.type === 'image'">
          <img 
            :src="item.file_url" 
            :alt="item.title" 
            style="width: 100% !important; height: 100% !important; object-fit: cover !important; min-width: 100% !important; min-height: 100% !important; max-width: 100% !important; max-height: 100% !important; display: block !important; position: absolute !important; top: 0 !important; left: 0 !important;"
            loading="lazy"
          />
        </template>

        <!-- Video Content -->
        <template v-if="item.type === 'video'">
          <video 
            :src="item.file_url" 
            style="width: 100% !important; height: 100% !important; object-fit: cover !important; min-width: 100% !important; min-height: 100% !important; max-width: 100% !important; max-height: 100% !important; display: block !important; position: absolute !important; top: 0 !important; left: 0 !important;"
            autoplay 
            muted 
            loop 
            playsinline
          ></video>
        </template>

        <!-- External Content (YouTube) -->
        <template v-if="item.type === 'external'">
          <div style="width: 100% !important; height: 100% !important; display: flex !important; align-items: center !important; justify-content: center !important; background: #000 !important; position: relative !important; overflow: hidden !important;">
            <iframe 
              :src="item.embed_url" 
              style="position: absolute !important; top: 0 !important; left: 0 !important; width: 100% !important; height: 100% !important; min-width: 110% !important; min-height: 110% !important; border: none !important; object-fit: cover !important; transform: translate(-50%, -50%) !important; top: 50% !important; left: 50% !important; display: block !important;"
              frameborder="0" 
              allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" 
              allowfullscreen
              loading="lazy"
            ></iframe>
          </div>
        </template>
      </div>
    </div>

    <!-- Indicators -->
    <div class="indicators" 
         style="position: absolute !important; bottom: 1rem !important; left: 50% !important; transform: translateX(-50%) !important; display: flex !important; gap: 0.5rem !important; z-index: 10 !important; background: transparent !important;" 
         v-if="items.length > 1">
      <div 
        v-for="(item, index) in items" 
        :key="`indicator-${item.id}`"
        @click="goToSlide(index)"
        :class="`indicator ${index === currentIndex ? 'active' : ''}`"
        style="width: 8px !important; height: 8px !important; border-radius: 50% !important; background: rgba(255, 255, 255, 0.4) !important; cursor: pointer !important; transition: all 0.3s ease !important;"
      ></div>
    </div>
  </div>
</template>

<script>
import { h } from 'vue';

export default {
  name: 'SimpleSlider',
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
    console.log('SimpleSlider mounted with data:', this.sliderData);
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
          this.loaded = true;
          console.warn('No slider items found from API');
        }
      } catch (error) {
        console.error('Error loading slider items from API:', error);
        this.loaded = true;
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
      }, 500);
    },
    prevSlide() {
      if (this.sliding || this.items.length <= 1) return;

      this.sliding = true;
      const prevIndex = (this.currentIndex - 1 + this.items.length) % this.items.length;
      this.currentIndex = prevIndex;

      this.resetAutoSlide();
      setTimeout(() => {
        this.sliding = false;
      }, 500);
    },
    goToSlide(index) {
      if (this.sliding || index === this.currentIndex) return;

      this.sliding = true;
      this.currentIndex = index;

      this.resetAutoSlide();
      setTimeout(() => {
        this.sliding = false;
      }, 500);
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