<template>
  <div class="mb-4">
    <h5 class="mb-2 mb-md-4 title-section-product">Destaques</h5>
      
    <Swiper
      :modules="[]"
      :space-between="12"
      :slides-per-view="1.2"
      :slides-per-group="1"
      navigation
      :loop="true"
      class="pb-2"
      :breakpoints="breakpoints"
    >
      <SwiperSlide
        v-for="product in products"
        :key="product.id"
      >
        <ProductCard
          :product="product"
          @add="$emit('add', $event)"
          @open="$emit('open', $event)"
        />
      </SwiperSlide>
    </Swiper>
  </div>
</template>

<script setup>
import ProductCard from './ProductCard.vue'

// Swiper
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Navigation } from 'swiper/modules'

// CSS do Swiper
import 'swiper/css'
import 'swiper/css/navigation'

defineProps({
  products: Array,
  onOpen: Function
})

// Breakpoints
const breakpoints = {
  640: { slidesPerView: 1.5, slidesPerGroup: 1 },
  768: { slidesPerView: 2, slidesPerGroup: 1 },
  1024: { slidesPerView: 3, slidesPerGroup: 1 },
  1279: { slidesPerView: 3.90, slidesPerGroup: 1 },
  1360: { slidesPerView: 3.98, slidesPerGroup: 1 },
};
</script>

<style scoped>
</style>