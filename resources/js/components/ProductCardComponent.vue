<template>
  <div>
    <div class="row mb-4">
      <div class="col-12">
        <h4>Categorias</h4>
        <div class="d-flex flex-wrap gap-2">
          <button v-for="cat in categories" :key="cat.id" class="btn btn-outline-primary btn-sm" @click="filterByCategory(cat.id)">
            {{ cat.name }}
          </button>
          <button class="btn btn-outline-secondary btn-sm" @click="filterByCategory(null)">Todas</button>
        </div>
      </div>
    </div>
    <div class="row">
      <div v-for="product in filteredProducts" :key="product.id" class="col-12 col-md-4 col-lg-3 mb-4">
        <product-card :product="product" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import ProductCard from './ProductCard.vue'

const products = ref([])
const categories = ref([])
const selectedCategory = ref(null)

const fetchProducts = async () => {
  const res = await fetch('/api/products')
  products.value = await res.json()
}
const fetchCategories = async () => {
  const res = await fetch('/api/categories')
  categories.value = await res.json()
}

const filterByCategory = (catId) => {
  selectedCategory.value = catId
}

const filteredProducts = computed(() => {
  if (!selectedCategory.value) return products.value
  return products.value.filter(p => p.category_id === selectedCategory.value)
})

onMounted(() => {
  fetchProducts()
  fetchCategories()
})
</script>
