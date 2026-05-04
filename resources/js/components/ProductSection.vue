<template>
  <div :id="`category-${categoryId}`" class="mb-4 mb-md-5">
    <h5 class="mb-2 mb-md-4 title-section-product">{{ title }}</h5>

    <div class="product-grid">
      <div
        v-for="product in products"
        :key="product.id"
        class="product-item"
      >
        <ProductCard :product="product" @add="$emit('add', $event)" @open="$emit('open', $event)" />
      </div>
    </div>
  </div>
</template>

<script setup>
  import ProductCard from './ProductCard.vue'

  defineProps({
    title: String,
    products: Array,
    categoryId: [Number, String]  // Nova prop para o ID da categoria
  })

  defineEmits(['add', 'open'])
</script>

<style scoped>
  .product-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr); 
    gap: 12px;
  }

  .product-item {
    width: 100%;
  }
  ::v-deep(.product-card img) {
    height: 200px;
  }
  ::v-deep(h6) {
    font-size: clamp(0.875rem, 0.938rem, 0.938rem);
  }

  @media (min-width: 768px) {
    .product-grid {
      grid-template-columns: repeat(3, 1fr); /* tablet */
    }
  }

  @media (min-width: 992px) {
    .product-grid {
      grid-template-columns: repeat(4, 1fr); /* desktop */
    }
  }
</style>