<template>
  <div class="card product-card h-100 cursor-pointer">
    <div class="product-content">
      <div class="position-relative product-image-wrapper">
        <!-- Badge de COMBO -->
        <span
          class="badge combo position-absolute top-0 start-0 m-2 d-flex align-items-center gap-1"
          v-if="product.isCombo"
        >
          <i class="bi bi-bullseye"></i>
          COMBO
        </span>
        
        <span class="badge cashback position-absolute top-0 start-0 m-2 small" v-else-if="product.cashback">
          {{ product.cashback }}% cashback
        </span>

        <img :src="product.images?.[0] || product.image" class="card-img-top" @click="handleClick"/>
      </div>

      <div class="card-body d-flex flex-column product-info">
        <h6 class="mb-1">{{ product.name }}</h6>

        <p class="text-muted small mb-0 mb-md-2 product-description">
          {{ product.description }}
        </p>

        <div class="mt-auto d-flex justify-content-between align-items-end">
          <div class="info-price">
            <div v-if="hasVariations" class="d-flex flex-column">
              <p class="mb-0 text-dark a-partir">A partir de</p>
              <strong class="text-dark price">R$ {{ formatPrice(minPrice) }}</strong>
            </div>
            <div v-else>
              <strong class="text-dark price">R$ {{ formatPrice(product.price) }}</strong>
            </div>
            
            <div class="d-flex gap-1" v-if="product.oldPrice && !hasVariations">
              <div class="text-muted small text-decoration-line-through">
                R$ {{ formatPrice(product.oldPrice) }}
              </div>
              <div class="percent bg-primary rounded-1 text-white d-flex justify-content-center align-items-center px-1">
                {{ Math.round(((product.oldPrice - product.price) / product.oldPrice) * 100) }}%
              </div>
            </div>
          </div>
          
          <button class="btn btn-sm btn-primary border-1 px-2 px-md-2 py-1" @click.stop="handleClick">
            <svg class="svg-car" width="32" height="34" viewBox="0 0 32 34" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M22.6039 3.84615H13.9929V6.64335H22.6039V3.84615ZM28.3446 3.4965V13.1119L10.3332 16.993L6.81706 0H1.43517C1.05454 0 0.689498 0.18419 0.420351 0.512051C0.151205 0.839912 0 1.28459 0 1.74825C0 2.21192 0.151205 2.65659 0.420351 2.98445C0.689498 3.31231 1.05454 3.4965 1.43517 3.4965H4.62125L9.25684 26.2238H28.3446V22.7273H11.4957L11.0221 20.4021L31.2149 16.0489V3.4965H28.3446ZM9.68739 27.972C9.26162 27.972 8.84541 28.1258 8.49139 28.414C8.13737 28.7021 7.86144 29.1117 7.69851 29.5909C7.53557 30.07 7.49294 30.5973 7.576 31.106C7.65907 31.6147 7.8641 32.0819 8.16517 32.4487C8.46624 32.8154 8.84982 33.0652 9.26741 33.1664C9.68501 33.2676 10.1179 33.2156 10.5112 33.0172C10.9046 32.8187 11.2408 32.4826 11.4773 32.0513C11.7139 31.6201 11.8401 31.1131 11.8401 30.5944C11.8401 29.8989 11.6133 29.2319 11.2096 28.7401C10.8059 28.2483 10.2583 27.972 9.68739 27.972ZM26.9094 27.972C26.4837 27.972 26.0674 28.1258 25.7134 28.414C25.3594 28.7021 25.0835 29.1117 24.9205 29.5909C24.7576 30.07 24.715 30.5973 24.798 31.106C24.8811 31.6147 25.0861 32.0819 25.3872 32.4487C25.6883 32.8154 26.0719 33.0652 26.4894 33.1664C26.907 33.2676 27.3399 33.2156 27.7332 33.0172C28.1266 32.8187 28.4628 32.4826 28.6994 32.0513C28.9359 31.6201 29.0622 31.1131 29.0622 30.5944C29.0622 29.8989 28.8354 29.2319 28.4317 28.7401C28.0279 28.2483 27.4804 27.972 26.9094 27.972Z" fill="var(--primary)"/>
            </svg>
            <span class="ms-1 text-primary">+</span> 
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { computed } from 'vue'
  import { useCartStore } from '@/stores/useCartStore'

  const props = defineProps({
    product: Object
  })

  const emit = defineEmits(['open', 'add'])
  const cart = useCartStore()

  // Verifica se é combo
  const isCombo = computed(() => {
    return props.product?.isCombo === true
  })

  // Verifica se o produto tem variações (tamanhos ou opções com preços diferentes)
  const hasVariations = computed(() => {
    // Combos sempre abrem modal
    if (isCombo.value) return false
    
    // Verifica se tem tamanhos com preços diferentes
    if (props.product.customization?.hasSize && props.product.customization?.sizes?.length > 0) {
      const sizes = props.product.customization.sizes
      const firstPrice = sizes[0]?.price
      return sizes.some(size => size.price !== firstPrice)
    }
    
    // Verifica se tem opções com preços diferentes
    if (props.product.options && props.product.options.length > 0) {
      for (const group of props.product.options) {
        if (group.items && group.items.length > 0) {
          const firstPrice = group.items[0]?.price
          if (group.items.some(item => item.price !== firstPrice)) {
            return true
          }
        }
      }
    }
    
    return false
  })

  // Calcula o menor preço entre as variações
  const minPrice = computed(() => {
    let lowestPrice = props.product.price || 0
    
    // Verifica tamanhos
    if (props.product.customization?.hasSize && props.product.customization?.sizes?.length > 0) {
      const sizes = props.product.customization.sizes
      const lowestSizePrice = Math.min(...sizes.map(s => s.price))
      if (lowestSizePrice < lowestPrice) {
        lowestPrice = lowestSizePrice
      }
    }
    
    // Verifica opções
    if (props.product.options && props.product.options.length > 0) {
      for (const group of props.product.options) {
        if (group.items && group.items.length > 0) {
          const lowestOptionPrice = Math.min(...group.items.map(i => i.price || 0))
          if (lowestOptionPrice < lowestPrice) {
            lowestPrice = lowestOptionPrice
          }
        }
      }
    }
    
    return lowestPrice
  })

  // Verifica se o produto tem opções de personalização
  const hasCustomizations = () => {
    // Combos SEMPRE abrem modal
    if (isCombo.value) return true
    
    // Verifica se tem opções (sabores)
    if (props.product.options && props.product.options.length > 0) return true
    
    // Verifica se tem tamanhos
    if (props.product.customization?.hasSize && props.product.customization?.sizes?.length > 0) return true
    
    // Verifica se tem sabores (pizza, açaí)
    if (props.product.customization?.hasFlavors && props.product.customization?.flavors?.length > 0) return true
    
    // Verifica se tem adicionais/toppings
    if (props.product.aditionals && props.product.aditionals.length > 0) return true
    if (props.product.customization?.hasToppings && props.product.customization?.toppings?.length > 0) return true
    
    // Verifica se tem nível de picância
    if (props.product.customization?.hasSpiciness && props.product.customization?.spicinessLevels?.length > 0) return true
    
    return false
  }

  // Prepara o produto para adicionar direto (sem personalizações)
  const prepareSimpleProduct = () => {
    return {
      id: props.product.id,
      name: props.product.name,
      description: props.product.description,
      price: props.product.price,
      originalPrice: props.product.price,
      oldPrice: props.product.oldPrice,
      image: props.product.images?.[0] || props.product.image,
      cashback: props.product.cashback || 0,
      cuisineType: props.product.cuisineType,
      customization: props.product.customization,
      selectedOption: null,
      selectedSize: null,
      selectedFlavors: [],
      aditionals: [],
      isCombo: false
    }
  }

  const handleClick = () => {
    if (hasCustomizations()) {
      // Produto com personalizações OU combo -> abre modal
      emit('open', props.product)
    } else {
      // Produto simples -> adiciona direto ao carrinho
      const simpleProduct = prepareSimpleProduct()
      cart.add(simpleProduct)
      // Opcional: mostrar toast de sucesso
      // toast.success(`${props.product.name} adicionado ao carrinho!`)
    }
  }

  const formatPrice = (value) => {
    if (!value && value !== 0) return '0,00'
    return value.toFixed(2).replace('.', ',')
  }
</script>

<style scoped>
  .btn.btn-primary{
    background: transparent !important;
  }
  .a-partir{
    font-size: clamp(0.625rem, 0.813vw, 0.813rem);
  }
  .combo {
    background: #FF8C00 !important;
    color: white !important;
    font-weight: 600;
    font-size: clamp(0.625rem, 0.813vw, 0.813rem);
  }
  .svg-car{
    width: 18px;
    height: 20px;
  }
  .product-card {
    cursor: pointer;
  }
  .price{
    font-size: clamp(0.875rem, 1.25vw, 1rem);
  }
  .cashback{
    background: #D9FFE6;
    color: #2F2B2B;
    font-size: clamp(0.75rem, 0.875vw, 0.875rem);
  }
  .product-card img {
    height: 250px;
    object-fit: cover;
  }
  .percent{
    font-size: clamp(0.625rem, 0.75vw, 0.75rem);
    font-weight: 600;
  }
  h6{
    font-size: clamp(0.875rem, 1.25vw, 1rem);
    font-weight: 500;
  }
</style>

<!-- ESTILO GLOBAL para o contexto do list-product -->
<style>
  /* Layout horizontal para mobile APENAS quando o product-card estiver dentro de .list-product */
  @media (max-width: 768px) {
    .list-product{
      width: 100% !important;
      margin: 0 !important;
    }
    .svg-car{
      width: 16px !important;
      height: 18px !important;
    }
      .list-product .product-card .product-content {
      display: flex !important;
      flex-direction: row !important;
      gap: 12px !important;
    }

    .list-product .product-card .product-image-wrapper {
      flex: 0 0 120px !important;
    }

    .list-product .product-card img {
      height: 100px !important;
      width: 100px !important;
      object-fit: cover !important;
      border-radius: 8px !important;
    }

    .list-product .product-card .product-info {
      flex: 1 !important;
      padding: 0 !important;
    }

    .list-product .product-card .product-description {
      display: -webkit-box !important;
      -webkit-line-clamp: 2 !important;
      -webkit-box-orient: vertical !important;
      overflow: hidden !important;
      text-overflow: ellipsis !important;
    }

    .list-product .product-card {
      padding: 12px !important;
    }

    .list-product .product-card .cashback {
      font-size: 0.625rem !important;
      padding: 2px 6px !important;
    }

    /* Ajuste do grid para mobile */
    .list-product .product-grid {
      display: flex !important;
      flex-direction: column !important;
      gap: 12px !important;
    }

    .list-product .product-item {
      width: 100% !important;
    }
  }

  @media (max-width: 475px) {
    .list-product .product-card .product-description, 
    .text-muted.small.text-decoration-line-through,
    .text-muted.small.mb-0.mb-md-2.product-description{
      font-size: 0.75rem !important;
    }
    .list-product .product-card .product-image-wrapper{
      flex: 0 0 100px !important;
    }
  }
</style>