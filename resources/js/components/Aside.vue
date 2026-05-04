<template>
  <aside class="aside position-relative" :class="{ 'has-sticky': isSticky }">

    <!-- Menu Desktop -->
    <div class="menu w-100 d-none d-md-flex flex-column">
      <a 
        href="#" 
        v-for="category in categories" 
        :key="category.id"
        class="menu-link" 
        :class="{ 'active': activeCategory === category.id }"
        @click.prevent="scrollToCategory(category.id)"
      >
        {{ category.name }}
      </a>
    </div>

    <!-- Mobile Swiper -->
    <div class="mobile-swiper-container" :class="{ 'sticky-mobile': isSticky }">
      <Swiper
        v-if="categories.length"
        :modules="[Navigation]"
        class="d-md-none menu-swiper"
        :class="{ 'sticky-active': isSticky }"
        :slides-per-view="'auto'"
        space-between="8"
        navigation
        :auto-height="false"
        :resize-observer="true"
        @swiper="onSwiperInit"
      >
        <SwiperSlide v-for="category in categories" :key="category.id">
          <a 
            href="#" 
            class="menu-link" 
            :class="{ 'active text-primary': activeCategory === category.id }"
            @click.prevent="scrollToCategory(category.id)"
          >
            {{ category.name }}
          </a>
        </SwiperSlide>
      </Swiper>
    </div>

    <!-- Banner -->
    <div class="mt-5 text-start d-none d-md-block">
      <img 
        src="@/assets/images/anuncio.png" 
        class="img-fluid"
      />
    </div>

  </aside>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch, nextTick } from 'vue'
// Swiper
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Navigation } from 'swiper/modules'

// CSS do Swiper
import 'swiper/css'
import 'swiper/css/navigation'

const props = defineProps({
  categories: {
    type: Array,
    required: true,
    default: () => []
  }
})

const activeCategory = ref(null)
const isSticky = ref(false)
const initialOffsetTop = ref(0)
let swiperInstance = null

// Função para capturar instância do Swiper
const onSwiperInit = (swiper) => {
  swiperInstance = swiper
}

// Função para verificar scroll e ativar/desativar sticky
const checkSticky = () => {
  // Só aplica sticky no mobile
  if (window.innerWidth > 680) {
    isSticky.value = false
    return
  }

  const scrollY = window.scrollY
  
  // Ativa sticky quando o scroll passa da posição do componente
  if (scrollY >= initialOffsetTop.value) {
    if (!isSticky.value) {
      isSticky.value = true
      // Quando ficar sticky, reseta a posição do Swiper
      nextTick(() => {
        if (swiperInstance) {
          swiperInstance.update()
        }
      })
    }
  } else {
    if (isSticky.value) {
      isSticky.value = false
      // Quando sair do sticky, reseta também
      nextTick(() => {
        if (swiperInstance) {
          swiperInstance.update()
        }
      })
    }
  }
}

// Função para salvar a posição inicial do container
const saveInitialPosition = () => {
  const aside = document.querySelector('.aside')
  
  if (aside) {
    // Salva a posição original do elemento
    initialOffsetTop.value = aside.offsetTop
    
    // Após salvar, verifica o estado atual do scroll
    checkSticky()
  }
}

// Função para rolar até a categoria
const scrollToCategory = (categoryId) => {
  activeCategory.value = categoryId
  
  // Procura o elemento da categoria pelo ID
  const element = document.getElementById(`category-${categoryId}`)
  
  if (element) {
    // Calcula a posição com offset para não ficar colado no topo
    const offset = 80 // Altura do header + um pouco mais
    const elementPosition = element.getBoundingClientRect().top
    const offsetPosition = elementPosition + window.pageYOffset - offset
    
    window.scrollTo({
      top: offsetPosition,
      behavior: 'smooth'
    })
  }
  
  // No mobile, scrolla o Swiper para mostrar a categoria ativa
  if (window.innerWidth <= 680 && swiperInstance) {
    nextTick(() => {
      const activeIndex = props.categories.findIndex(cat => cat.id === categoryId)
      if (activeIndex !== -1) {
        swiperInstance.slideTo(activeIndex, 300)
      }
    })
  }
}

// Função para atualizar a categoria ativa baseado no scroll
const updateActiveCategoryOnScroll = () => {
  const scrollPosition = window.scrollY + 100 // Offset para considerar o header fixo
  
  // Variável para armazenar a categoria atual
  let currentCategory = null
  
  // Percorre todas as categorias
  for (let i = 0; i < props.categories.length; i++) {
    const category = props.categories[i]
    const element = document.getElementById(`category-${category.id}`)
    
    if (element) {
      const elementTop = element.offsetTop
      const elementBottom = elementTop + element.offsetHeight
      
      // Se o scroll está dentro da seção da categoria
      if (scrollPosition >= elementTop - 100 && scrollPosition < elementBottom - 100) {
        currentCategory = category.id
        break
      }
    }
  }
  
  // Se encontrou uma categoria, atualiza o active
  if (currentCategory && activeCategory.value !== currentCategory) {
    activeCategory.value = currentCategory
    
    // No mobile, atualiza a posição do Swiper para o slide ativo
    if (window.innerWidth <= 680 && swiperInstance && !isSticky.value) {
      const activeIndex = props.categories.findIndex(cat => cat.id === currentCategory)
      if (activeIndex !== -1) {
        const activeSlide = swiperInstance.slides[activeIndex]
        if (activeSlide) {
          // Só centraliza se o slide não estiver visível
          const slideLeft = activeSlide.offsetLeft
          const containerWidth = swiperInstance.width
          const slideWidth = activeSlide.offsetWidth
          
          if (slideLeft < swiperInstance.translate * -1 || 
              slideLeft + slideWidth > (swiperInstance.translate * -1) + containerWidth) {
            swiperInstance.slideTo(activeIndex, 300)
          }
        }
      }
    }
  }
  
  // Se não encontrou nenhuma categoria e tem pelo menos uma, marca a primeira
  if (!currentCategory && props.categories.length > 0 && scrollPosition < 200) {
    activeCategory.value = props.categories[0].id
  }
  
  // Verifica sticky
  checkSticky()
}

// Função throttle para melhor performance
let scrollTimeout
const handleScroll = () => {
  if (scrollTimeout) {
    cancelAnimationFrame(scrollTimeout)
  }
  scrollTimeout = requestAnimationFrame(() => {
    updateActiveCategoryOnScroll()
  })
}

// Verifica se as seções existem e atualiza o active inicial
const initialize = () => {
  nextTick(() => {
    if (props.categories.length > 0) {
      // Salva a posição inicial do container
      saveInitialPosition()
      
      // Força uma verificação do scroll atual
      setTimeout(() => {
        updateActiveCategoryOnScroll()
      }, 100)
    }
  })
}

// Observa mudanças nas categorias
watch(() => props.categories, () => {
  initialize()
}, { immediate: true })

// Observa quando a tela é redimensionada
const handleResize = () => {
  saveInitialPosition()
  if (swiperInstance) {
    setTimeout(() => {
      swiperInstance.update()
    }, 100)
  }
}

// Adiciona evento de scroll
onMounted(() => {
  initialize()
  window.addEventListener('scroll', handleScroll)
  window.addEventListener('resize', handleResize)
})

// Limpa evento ao desmontar
onUnmounted(() => {
  if (scrollTimeout) {
    cancelAnimationFrame(scrollTimeout)
  }
  window.removeEventListener('scroll', handleScroll)
  window.removeEventListener('resize', handleResize)
  swiperInstance = null
})
</script>

<style scoped>
.aside {
  width: 224px;
  height: 100%;
}

/* Menu desktop */
.menu-link {
  padding: 10px 0;
  color: var(--text-medium);
  text-decoration: none;
  border-bottom: 1px solid var(--text-light);
  font-size: clamp(0.875rem, 1vw, 1rem);
  transition: 0.2s;
  width: 75%;
  display: block;
  cursor: pointer;
  font-weight: 500;
}

.menu-link:hover {
  color: var(--primary);
}

.menu-link.active {
  color: var(--primary);
  font-weight: 500;
  border-bottom: 2px solid var(--primary);
}

/* Mobile Swiper styling */
.mobile-swiper-container {
  transition: all 0.3s ease;
  overflow: clip;
}
.menu-swiper {
  padding: 10px 0;
  overflow: visible !important;
}

.menu-swiper .swiper-slide {
  width: auto;
}

.menu-swiper .menu-link {
  white-space: nowrap;
  padding: 8px 0;
  width: auto;
  border-bottom: 2px solid transparent;
  margin-right: 16px;
}

.menu-swiper .menu-link.active {
  border-bottom: 2px solid var(--primary);
}
/* Quando o sticky está ativo no mobile */
@media (max-width: 680px) {
  .mobile-swiper-container.sticky-mobile {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    background: var(--bg-ouro);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    padding: 0 16px;
  }
  
  /* Adiciona um espaçamento no aside para não "pular" o conteúdo */
  .aside.has-sticky {
    padding-top: 60px;
  }
  
  /* Estilos para quando o carrossel está fixo/sticky */
  .mobile-swiper-container.sticky-mobile .menu-swiper.sticky-active .menu-link {
    color: var(--text-dark) !important;
  }
  
  .mobile-swiper-container.sticky-mobile .menu-swiper.sticky-active .menu-link.active {
    border-bottom: 2px solid var(--text-dark);
    padding-bottom: 2px;
  }
  
  .mobile-swiper-container.sticky-mobile .menu-swiper.sticky-active :deep(.swiper-button-prev) {
    filter: brightness(0);
  }
  
  .mobile-swiper-container.sticky-mobile .menu-swiper.sticky-active :deep(.swiper-button-next) {
    filter: brightness(0);
  }
  .menu-swiper {
    padding-bottom: 30px;
  }
  
  /* Cores originais no mobile normal (sem sticky) */
  .menu-swiper .menu-link {
    color: var(--color-grey);
  }
  
  .menu-swiper .menu-link.active {
    border-bottom: 2px solid var(--primary);
    padding-bottom: 2px;
  }
  
  :deep(.swiper-button-prev svg),
  :deep(.swiper-button-next svg) {
    display: none;
  }
  
  :deep(.swiper-button-prev) {
    left: 40%;
  }
  
  :deep(.swiper-button-next) {
    right: 40%;
  }
  
  :deep(.swiper-button-prev),
  :deep(.swiper-button-next) {
    top: 70px;
    transform: translateX(-50%);
  }
  
  :deep(.swiper-button-prev::after),
  :deep(.swiper-button-next::after) {
    content: '';
  }
  
  :deep(.swiper-button-prev),
  :deep(.swiper-button-next) {
    width: 32px;
    height: 32px;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
  }

  :deep(.swiper-button-prev) {
    width: 25px;
    height: 25px;

    background-color: var(--primary);

    -webkit-mask: url('@/assets/images/left.svg') no-repeat center;
    mask: url('@/assets/images/left.svg') no-repeat center;

    -webkit-mask-size: contain;
    mask-size: contain;
  }

  :deep(.swiper-button-next) {
    width: 25px;
    height: 25px;
        background-color: var(--primary);

    -webkit-mask: url('@/assets/images/right.svg') no-repeat center;
    mask: url('@/assets/images/right.svg') no-repeat center;

    -webkit-mask-size: contain;
    mask-size: contain;
  }
}

</style>