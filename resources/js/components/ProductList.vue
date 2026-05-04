<template>
  <div>
    <ProductSection
      v-for="category in categories"
      :key="category.id"
      :title="category.name"
      :products="category.products"
      :category-id="category.id"
      @add="$emit('add', $event)"
      @open="$emit('open', $event)"
    />
  </div>
</template>

<script setup>
import ProductSection from './ProductSection.vue'

defineProps({
  categories: Array
})

defineEmits(['add', 'open'])
</script>