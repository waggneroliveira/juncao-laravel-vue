<script setup>
import { RouterView } from 'vue-router'
import { ref, watch, computed, onMounted } from 'vue'
import { useCartStore } from '@/stores/useCartStore'
import { useToast } from 'vue-toastification'
import { useUserStore } from '@/stores/useUserStore'

// Componentes
import Aside from '@/components/Aside.vue'
import Header from '@/components/Header.vue'
import TopBar from '@/components/TopBar.vue'
import ProductCarousel from '@/components/ProductCarousel.vue'
import ProductList from '@/components/ProductList.vue'
import Announcement from '@/components/Announcement.vue'
import Cart from '@/components/Cart.vue'
import ProductModal from '@/components/ProductModal.vue'
import MobileBottomMenu from '@/components/MobileBottomMenu.vue'
import OrderHistoryModal from '@/components/OrderHistoryModal.vue'
import IdentifyModal from '@/components/IdentifyModal.vue'
import Footer from '@/components/Footer.vue'

// Store e Toast
const cart = useCartStore()
const toast = useToast()
const userStore = useUserStore()

// Estados do modal
const showProductModal = ref(false)
const selectedProduct = ref(null)
const showOrderHistoryModal = ref(false)
const showLoginModal = ref(false)

// Produtos e categorias dinâmicos do backend
const products = ref([])
const categories = ref([])

// Buscar produtos e categorias do backend
const fetchProducts = async () => {
  const res = await fetch('/api/products')
  products.value = await res.json()
  
  // Expor produtos globalmente para acesso do Cart
  if (typeof window !== 'undefined') {
    window.products = products.value
  }
}

const fetchCategories = async () => {
  const res = await fetch('/api/categories')
  categories.value = await res.json()
}

// Helper para reconstruir seleções a partir de comboDetails
const rebuildSelectionsFromDetails = (comboItem) => {
  if (!comboItem.comboDetails) return {}
  
  return {
    selectedAddons: comboItem.selectedAddons || [],
    itemCustomizations: comboItem.comboDetails.itemCustomizations || {},
    selectedItems: comboItem.comboDetails.selectedItems || {}
  }
}

// Abrir modal do produto
const openProductModal = (product) => {
  selectedProduct.value = product
  showProductModal.value = true
}

// Função para lidar com o re-pedido - VERSÃO COMPLETA CORRIGIDA
const handleReorder = (order) => {
  console.log('🎯 Reordenando pedido completo:', order.id)
  
  if (!order || !order.items || order.items.length === 0) {
    toast.error('Erro ao reordenar: pedido inválido')
    return
  }
  
  // Processar cada item do pedido
  order.items.forEach((originalItem, index) => {
    console.log(`📦 Processando item ${index + 1}:`, originalItem.name, originalItem.isCombo ? '(COMBO)' : '(NORMAL)')
    
    if (originalItem.isCombo) {
      // 🔥 PARA COMBO: Adicionar com todas as configurações preservadas
      const comboItem = JSON.parse(JSON.stringify(originalItem))
      
      comboItem.productId = comboItem.productId || comboItem.id
      comboItem.finalPrice = comboItem.finalPrice || comboItem.price
      comboItem.basePrice = comboItem.basePrice || comboItem.price
      comboItem.hasComboSelection = true
      comboItem.isComboItem = true
      
      comboItem.isReorder = true
      comboItem.reorderDate = new Date().toISOString()
      comboItem.originalOrderId = order.id
      
      if (!comboItem.itemSelections && comboItem.comboDetails) {
        comboItem.itemSelections = rebuildSelectionsFromDetails(comboItem)
      }
      
      if (comboItem.selectedAddons && comboItem.selectedAddons.length) {
        comboItem.addonsTotalPrice = comboItem.selectedAddons.reduce(
          (sum, addon) => sum + (addon.price * (addon.quantity || 1)), 0
        )
      } else {
        comboItem.selectedAddons = []
        comboItem.addonsTotalPrice = 0
      }
      
      cart.add(comboItem)
    } else {
      // Para produto normal
      cart.add(originalItem)
    }
  })
  
  toast.success(`${order.items.length} item(ns) adicionado(s) ao carrinho!`)
}

// Produtos em destaque para o carrossel
const highlights = computed(() => {
  return products.value.filter(p => p.featured === true)
})

// Helper para nomes das categorias
const getCategoryName = (categoryKey) => {
  const names = {
    'hamburguers': 'Hambúrgueres',
    'pizzas': 'Pizzas',
    'acai': 'Açaí',
    'bebidas': 'Bebidas',
    'entradas': 'Entradas',
    'sobremesas': 'Sobremesas',
    'combos': 'Combos'
  }
  return names[categoryKey] || categoryKey
}

// ========== FUNÇÕES PARA COMBOS ==========

// Verifica se um produto é combo
const isCombo = (product) => {
  return product?.isCombo === true
}

// Calcula o preço total do combo baseado nas escolhas do usuário
const calculateComboPrice = (combo, selections = {}) => {
  let totalPrice = combo.price // Preço base do combo
  
  // Adiciona preço dos addons selecionados
  if (selections.selectedAddons) {
    selections.selectedAddons.forEach(addon => {
      totalPrice += addon.price * addon.quantity
    })
  }
  
  // Adiciona customizações extras dos itens
  if (selections.itemCustomizations) {
    Object.values(selections.itemCustomizations).forEach(custom => {
      if (custom.selectedToppings) {
        custom.selectedToppings.forEach(topping => {
          totalPrice += topping.price
        })
      }
      if (custom.selectedSize && custom.selectedSize.price) {
        const originalItemPrice = combo.comboItems.find(i => i.name === custom.itemName)?.price || 0
        totalPrice += (custom.selectedSize.price - originalItemPrice)
      }
    })
  }
  
  return totalPrice
}

// Prepara o combo para adicionar ao carrinho
const prepareComboForCart = (combo, selections = {}) => {
  return {
    id: `${combo.id}_${Date.now()}`,
    productId: combo.id,
    name: combo.name,
    description: combo.description,
    basePrice: combo.price,
    finalPrice: calculateComboPrice(combo, selections),
    quantity: 1,
    isCombo: true,
    comboItems: combo.comboItems,
    comboAddons: combo.comboAddons,
    selections: selections,
    image: combo.image?.[0] || combo.image,
    savings: combo.savings
  }
}

// Função para adicionar combo ao carrinho
const addComboToCart = (combo, selections = {}) => {
  const comboItem = prepareComboForCart(combo, selections)
  cart.add(comboItem)
  toast.success(`${combo.name} adicionado ao carrinho! Economia de R$ ${combo.savings?.toFixed(2) || '0,00'}`, {
    timeout: 3000
  })
}

// Helper para obter produtos disponíveis para combo
const getAvailableProductsForCombo = () => {
  return products.value.filter(p => !p.isCombo)
}

// ========== FUNÇÕES PARA PRODUTOS NORMAIS ==========

// Calcula preço final baseado nas personalizações
const calculateFinalPrice = (product, customizations) => {
  let finalPrice = product.price
  
  if (customizations.selectedSize) {
    finalPrice = customizations.selectedSize.price
  }
  
  if (customizations.selectedFlavors) {
    customizations.selectedFlavors.forEach(flavor => {
      finalPrice += flavor.price
    })
  }
  
  if (customizations.selectedToppings) {
    customizations.selectedToppings.forEach(topping => {
      finalPrice += topping.price
    })
  }
  
  return finalPrice
}

// Função para adicionar produto normal ao carrinho
const addToCart = (product, customizations = {}) => {
  const cartItem = {
    id: `${product.id}_${Date.now()}`,
    productId: product.id,
    name: product.name,
    basePrice: product.price,
    finalPrice: calculateFinalPrice(product, customizations),
    quantity: 1,
    customizations,
    image: product.image?.[0] || product.image,
    isCombo: false
  }
  
  cart.add(cartItem)
  toast.success(`${product.name} adicionado ao carrinho!`, {
    timeout: 2000
  })
}

// Função unificada para adicionar ao carrinho (produto ou combo)
const addItemToCart = (item, selections = {}) => {
  if (isCombo(item)) {
    addComboToCart(item, selections)
  } else {
    addToCart(item, selections)
  }
}

// Função para lidar com menu mobile
const handleMenuChange = (value) => {
  console.log('Menu alterado:', value)
}

// Função para abrir histórico de pedidos
const openOrderHistory = () => {
  showOrderHistoryModal.value = true
}

// Função para identificar usuário
const handleIdentify = (userData) => {
  console.log('Usuário identificado:', userData)
  showLoginModal.value = false
}

// Monitora mudanças no carrinho
watch(() => cart.items, (newItems) => {
  console.log('Carrinho atualizado:', JSON.parse(JSON.stringify(newItems)))
}, { deep: true })

// Inicialização
onMounted(() => {
  fetchProducts()
  fetchCategories()
})
</script>

<template>
  <div class="container-fluid p-0">
    <Header class="header" />
    
    <MobileBottomMenu 
      @change="handleMenuChange" 
      @open-orders="openOrderHistory"
    />
    
    <Cart />

    <div class="layout">
      <main class="main">

        <!-- Topo CONTIDO -->
        <div class="container">
          <TopBar />          
        </div>

        <div class="container py-0">
  
          <div class="d-flex flex-wrap">
            <!-- PASSANDO AS CATEGORIAS PARA O ASIDE -->
            <Aside :categories="categories" class="aside" />

            <div class="content w-mobile-100">
              
              <Announcement/>
              
              <div class="carousel-breakout">
                <ProductCarousel
                  :products="highlights"
                  @open="openProductModal"
                />
              </div>

              <div class="list-product">
                <ProductList
                  :categories="categories"
                  @open="openProductModal"
                />
              </div>

            </div>
          </div>

        </div>

        <RouterView />

      </main>

      <ProductModal
        v-model:show="showProductModal"
        :product="selectedProduct"
        @add-to-cart="addItemToCart"
      />
    </div>

    <OrderHistoryModal 
      v-if="showOrderHistoryModal"
      v-model="showOrderHistoryModal"
      :user-id="userStore.userId"
      @reorder="handleReorder"
    />
    
    <!-- IdentifyModal para login -->
    <IdentifyModal
      v-model="showLoginModal"
      @submit="handleIdentify"
    />

    <Footer/>
  </div>
</template>

<style scoped>
@media (max-width: 768px) {
  .aside{
    width: 100%;
  }
  .w-mobile-100{
    width: 100%;
  }
  .carousel-breakout{
    margin-left: 0 !important;
    width: 100vw !important;
  }
}
.list-product{
  width: 69vw;
  margin-left: calc(-30vw + 50%);
}
.carousel-breakout {
  width: 90vw;
  margin-left: calc(-30vw + 50%);
  margin-top: -40px;
}

/* opcional: controlar o lado direito */
.carousel-breakout .swiper {
  padding-right: 2rem;
}

@media (max-width: 680px) {
  .carousel-breakout{
    margin-top: 0;
  }
  .carousel-breakout{
    padding-bottom: 10px;
  }
  .swiper-button-prev, .swiper-button-next{
    top: 25px !important;
  }
}
</style>