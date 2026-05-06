<template>
  <div class="mb-4" v-if="highlightProducts.length > 0">
    <h5 class="mb-2 mb-md-4 title-section-product">Destaques</h5>
      
    <Swiper
      :modules="[Navigation]"
      :space-between="12"
      :slides-per-view="1.2"
      :slides-per-group="1"
      navigation
      :loop="true"
      class="pb-2"
      :breakpoints="breakpoints"
    >
      <SwiperSlide
        v-for="product in highlightProducts"
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
import { computed } from 'vue'
import ProductCard from './ProductCard.vue'
// Swiper
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Navigation } from 'swiper/modules'

// CSS do Swiper
import 'swiper/css'
import 'swiper/css/navigation'

// Definir props corretamente
const props = defineProps({
  products: {
    type: Array,
    default: () => []
  },
  breakpoints: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['add', 'open'])

// Computed para produtos em destaque
const highlightProducts = computed(() => {
  if (!props.products || !props.products.length) return []
  return props.products.filter(product => product.highlights === true || product.highlights === 1)
})

// Breakpoints padrão
const breakpoints = {
  640: { slidesPerView: 1.5, slidesPerGroup: 1 },
  768: { slidesPerView: 2, slidesPerGroup: 1 },
  1024: { slidesPerView: 3, slidesPerGroup: 1 },
  1279: { slidesPerView: 3.90, slidesPerGroup: 1 },
  1360: { slidesPerView: 3.98, slidesPerGroup: 1 },
}
</script>

<style scoped>
</style>