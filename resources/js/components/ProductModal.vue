<template>
  <div
    v-if="show"
    class="modal fade show d-block"
    tabindex="-1"
    style="background: rgba(0,0,0,.6)"
    @click.self="close"
  >
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content p-0 rounded-0 h-100">

        <!-- HEADER -->
        <div class="modal-header d-flex justify-content-between align-items-center mb-4 position-relative">
          <span class="ms-2 d-flex gap-1 fw-medium text-capitalize">
            <i class="bi bi-cup-straw me-1"></i>
            {{ product?.category }}
          </span>
          <div class="d-flex justify-content-end align-items-center w-100 z-in">
            <button class="btn-close" @click="close"></button>
          </div>
        </div>

        <div class="row pb-5 px-4">
          <!-- LEFT -->
          <div class="col-md-5 text-center">
            <div class="image">
              <img
                :src="product?.images?.[0] || product?.image"
                class="img-fluid w-100 rounded-3 mb-2"
              />
            </div>

            <!-- Badge de COMBO -->
            <div v-if="product?.isCombo" class="badge combo-badge mb-2 d-inline-block">
              <i class="bi bi-bullseye"></i>
              Combo - Economize R$ {{ formatPrice(product?.savings || 0) }}
            </div>

            <!-- BADGES DE INFORMAÇÕES -->
            <div class="badge d-none bg-warning-subtle text-dark border border-warning-subtle w-100 mb-1">
              <div class="specifications-badges d-flex justify-content-center align-items-center gap-2 flex-wrap">
                <small v-if="product?.specifications?.preparationTime" class="badge-spec">
                  <i class="bi bi-clock"></i>
                  {{ product.specifications.preparationTime }}min
                </small>

                <small v-if="product?.specifications?.calories" class="badge-spec">
                  <i class="bi bi-fire"></i>
                  {{ product.specifications.calories }}kcal
                </small>

                <small v-if="product?.specifications?.serves" class="badge-spec">
                  <i class="bi bi-people"></i>
                  {{ product.specifications.serves }} pessoa(s)
                </small>

                <small v-if="product?.specifications?.isVegetarian" class="badge-spec veg">
                  <i class="bi bi-flower1"></i>
                  Vegetariano
                </small>

                <small v-if="product?.specifications?.isVegan" class="badge-spec vegan">
                  <i class="bi bi-tree"></i>
                  Vegano
                </small>

                <small v-if="product?.specifications?.isGlutenFree" class="badge-spec gluten">
                  <i class="bi bi-ban"></i>
                  Sem Glúten
                </small>
              </div>
            </div>

            <!-- Alérgicos -->
            <div 
              v-if="product?.specifications?.allergens?.length" 
              class="allergens-warning mb-2 px-2 py-1 rounded d-flex justify-content-center align-items-center bg-danger-subtle text-danger"
            >
              <small class="badge-spec">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Alérgicos: {{ product.specifications.allergens.join(', ') }}
              </small>
            </div>

            <span v-if="product?.cashback && !product?.isCombo" class="badge bg-warning text-dark mb-2">
              {{ product.cashback }}% cashback
            </span>

            <h5 class="fw-bold text-primary">{{ product?.name }}</h5>
            <p class="text-muted small">
              {{ product?.description }}
            </p>

            <div class="mt-2">
              <h5 class="fw-bold">
                R$ {{ formatPrice(finalPrice) }}
              </h5>

              <div class="text-muted small" v-if="currentOldPrice">
                <s>R$ {{ formatPrice(currentOldPrice) }}</s>
                <span class="badge bg-primary rounded-1 ms-2">
                  {{ discount }}%
                </span>
              </div>
              
              <!-- Economia do combo -->
              <div v-if="product?.isCombo && product?.savings" class="text-success small mt-1">
                <i class="bi bi-tag"></i> Você economiza R$ {{ formatPrice(product.savings) }} neste combo!
              </div>
            </div>
          </div>

          <!-- RIGHT -->
          <div class="col-md-7 scroll">
            
            <!-- SEÇÃO: Itens do Combo -->
            <div class="col-md-12" v-if="product?.isCombo && product?.comboItems?.length">
              <div class="mb-4">
                <h6 class="fw-bold text-primary text-center mb-4">
                  Itens do Combo
                </h6>

                <div
                  v-for="(item, index) in product.comboItems"
                  :key="index"
                  class="border rounded p-3 mb-3"
                  :class="{ 'bg-light': item.options }"
                >
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                      <div class="fw-semibold">
                        {{ item.quantity }}x {{ item.name }}
                        <span v-if="item.required" class="badge bg-primary ms-2">Obrigatório</span>
                      </div>
                      <small class="text-muted" v-if="item.price !== undefined">
                        {{ item.price > 0 ? `+ R$ ${formatPrice(item.price)}` : 'Incluso no combo' }}
                      </small>
                      <small class="text-muted" v-else>
                        Incluso no combo
                      </small>
                    </div>
                  </div>
                  
                  <!-- RENDERIZAÇÃO DINÂMICA DAS OPÇÕES -->
                  <div v-if="item.options" class="mt-2 pt-2 border-top">
                    
                    <!-- SELECT (dropdown) -->
                    <div v-if="item.options.type === 'select'" class="mb-2">
                      <label class="form-label fw-semibold small">{{ item.options.title }}</label>
                      <select 
                        class="form-select form-select-sm"
                        :value="getSelectionValue(item.id)"
                        @change="updateComboItemSelection(item.id, $event.target.value)"
                      >
                        <option v-for="choice in item.options.choices" :key="choice.id" :value="choice.id">
                          {{ choice.name }} {{ choice.price > 0 ? `(+ R$ ${formatPrice(choice.price)})` : '' }}
                        </option>
                      </select>
                    </div>
                    
                    <!-- RADIO (botões de opção) -->
                    <div v-if="item.options.type === 'radio'" class="mb-2">
                      <label class="form-label fw-semibold small">{{ item.options.title }}</label>
                      <div class="d-flex flex-column gap-1">
                        <div v-for="choice in item.options.choices" :key="choice.id" class="form-check">
                          <input
                            type="radio"
                            class="form-check-input"
                            :name="`combo_item_${item.id}`"
                            :value="choice.id"
                            :checked="getSelectionValue(item.id) === choice.id"
                            @change="updateComboItemSelection(item.id, choice.id)"
                          />
                          <label class="form-check-label">
                            {{ choice.name }}
                            <span v-if="choice.price > 0" class="text-primary">(+ R$ {{ formatPrice(choice.price) }})</span>
                            <small v-if="choice.description" class="text-muted d-block">{{ choice.description }}</small>
                          </label>
                        </div>
                      </div>
                    </div>
                    
                    <!-- CHECKBOX (múltipla escolha com limite) -->
                    <div v-if="item.options.type === 'checkbox' || item.options.type === 'multicheckbox'" class="mb-2">
                      <label class="form-label fw-semibold small">
                        {{ item.options.title }}
                        <span class="text-muted" v-if="item.options.maxSelections">
                          (máx. {{ item.options.maxSelections }})
                        </span>
                      </label>
                      <div class="d-flex flex-column gap-1">
                        <div v-for="choice in item.options.choices" :key="choice.id" class="d-flex align-items-center justify-content-between">
                          <div class="form-check">
                            <input
                              type="checkbox"
                              class="form-check-input"
                              :value="choice.id"
                              :checked="isChoiceSelected(item.id, choice.id)"
                              :disabled="isCheckboxDisabled(item.id, choice.id)"
                              @change="updateComboMultiSelection(item.id, choice.id, $event.target.checked)"
                            />
                            <label class="form-check-label">
                              {{ choice.name }}
                              <span v-if="choice.price > 0" class="text-primary">(+ R$ {{ formatPrice(choice.price) }})</span>
                            </label>
                          </div>
                          
                          <!-- Para itens que permitem quantidade (ex: rolinhos) -->
                          <div v-if="choice.maxPerOption" class="d-flex align-items-center gap-2">
                            <button 
                              class="btn btn-sm btn-outline-danger" 
                              :disabled="isDecreaseDisabled(item.id, choice.id)"
                              @click="decreaseChoiceQuantity(item.id, choice.id)"
                            >-</button>
                            <span class="fw-bold min-width-30 text-center">
                              {{ getChoiceQuantity(item.id, choice.id) }}
                            </span>
                            <button 
                              class="btn btn-sm btn-outline-success" 
                              :disabled="isIncreaseDisabled(item.id, choice.id)"
                              @click="increaseChoiceQuantity(item.id, choice.id)"
                            >+</button>
                          </div>
                        </div>
                      </div>
                      <div v-if="item.options.maxSelections" class="small text-muted mt-1">
                        Selecionados: {{ getTotalSelectionsCount(item.id) }} / {{ item.options.maxSelections }}
                      </div>
                    </div>
                    
                  </div>
                  
                  <!-- Mostra resumo das escolhas -->
                  <div v-if="getItemChoicesSummary(item)" class="mt-2 pt-2 border-top bg-white rounded p-2">
                    <small class="text-muted fw-semibold">📋 Suas escolhas:</small>
                    <div class="small mt-1">
                      {{ getItemChoicesSummary(item) }}
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- SEÇÃO: Addons do Combo -->
            <div class="col-md-12" v-if="product?.isCombo && product?.comboAddons?.length">
              <div class="mb-4">
                <h6 class="fw-bold text-primary text-center mb-4">
                  Adicionais Extras
                </h6>

                <div
                  v-for="addon in product.comboAddons"
                  :key="addon.id"
                  class="d-flex align-items-center justify-content-between border rounded px-3 py-2 mb-2"
                >
                  <div class="fw-medium">
                    {{ addon.name }}
                    <small class="text-muted ms-1">
                      (+R$ {{ formatPrice(addon.price) }})
                    </small>
                  </div>

                  <div class="d-flex align-items-center gap-2">
                    <button
                      class="btn btn-sm btn-outline-danger"
                      @click="decreaseComboAddon(addon.id)"
                    >
                      -
                    </button>

                    <span class="fw-bold">
                      {{ getComboAddonQty(addon.id) }}
                    </span>

                    <button
                      class="btn btn-sm btn-outline-success"
                      @click="increaseComboAddon(addon)"
                    >
                      +
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Opções (Sabores/Tamanhos) - apenas para produtos normais -->
            <div class="col-md-12" v-if="!product?.isCombo && hasOptions">
              <div
                v-for="group in optionGroups"
                :key="group.title"
                class="mb-4"
              >
                <h6 class="fw-bold text-primary text-center mb-4">
                  {{ group.title }}
                </h6>

                <div
                  v-for="item in group.items"
                  :key="item.id"
                  class="d-flex align-items-center border gap-1 rounded p-0 mb-2 option-item pe-3"
                  :class="{ active: isOptionSelected(item) }"
                  @click="selectOption(item)"
                >
                  <img
                    :src="item.image || product?.images?.[0]"
                    class="me-3 cover"
                    width="105"
                    height="105"
                  />

                  <div class="flex-grow-1">
                    <div class="fw-semibold">
                      {{ item.name }}
                    </div>
                    <small class="text-muted">
                      {{ item.description }}
                    </small>
                    <div v-if="item.price > 0" class="text-primary small fw-bold">
                      + R$ {{ formatPrice(item.price) }}
                    </div>
                  </div>

                  <input
                    type="radio"
                    :checked="isOptionSelected(item)"
                  />
                </div>
              </div>
            </div>

            <!-- Opção de Tamanhos -->
            <div class="col-md-12" v-if="!product?.isCombo && hasSizes">
              <div class="mb-4">
                <h6 class="fw-bold text-primary text-center mb-4">
                  Tamanhos
                </h6>

                <div
                  v-for="size in product?.customization?.sizes"
                  :key="size.id"
                  class="d-flex align-items-center border gap-1 rounded p-0 mb-2 option-item pe-3"
                  :class="{ active: selectedSize?.id === size.id }"
                  @click="selectedSize = size"
                >
                  <div class="flex-grow-1 p-2 p-md-3">
                    <div class="fw-semibold">
                      {{ size.name }}
                    </div>
                    <small class="text-muted" v-if="size.description">
                      {{ size.description }}
                    </small>
                    <div class="text-primary fw-bold">
                      R$ {{ formatPrice(size.price) }}
                      <span v-if="size.oldPrice" class="text-muted small ms-2">
                        <s>R$ {{ formatPrice(size.oldPrice) }}</s>
                      </span>
                    </div>
                  </div>

                  <input
                    type="radio"
                    :checked="selectedSize?.id === size.id"
                    class="me-3"
                  />
                </div>
              </div>
            </div>

            <!-- Sabores -->
            <div class="col-md-12" v-if="!product?.isCombo && hasFlavors">
              <div class="mb-4">
                <h6 class="fw-bold text-primary text-center mb-4">
                  Sabores (máx. {{ maxFlavors }})
                </h6>

                <div
                  v-for="flavor in product?.customization?.flavors"
                  :key="flavor.id"
                  class="d-flex align-items-center border gap-1 rounded p-0 mb-2 option-item pe-3"
                  :class="{ active: isFlavorSelected(flavor) }"
                  @click="toggleFlavor(flavor)"
                >
                  <div class="flex-grow-1 p-2 p-md-3">
                    <div class="fw-semibold">
                      {{ flavor.name }}
                      <span v-if="flavor.isRecommended" class="badge bg-primary ms-2">
                        Recomendado
                      </span>
                    </div>
                    <small class="text-muted">
                      {{ flavor.description }}
                    </small>
                    <div v-if="flavor.price > 0" class="text-primary small fw-bold">
                      + R$ {{ formatPrice(flavor.price) }}
                    </div>
                  </div>

                  <input
                    type="checkbox"
                    :checked="isFlavorSelected(flavor)"
                    :disabled="maxFlavorsReached && !isFlavorSelected(flavor)"
                    class="me-3"
                  />
                </div>
              </div>
            </div>
            
            <!-- Adicionais -->
            <div class="col-md-12" v-if="!product?.isCombo && hasAditionals">
              <div
                v-for="group in aditionalGroups"
                :key="group.title"
                class="mb-4"
              >
                <h6 class="fw-bold text-primary text-center mb-3">
                  {{ group.title }}
                </h6>

                <div
                  v-for="item in group.items"
                  :key="item.id"
                  class="d-flex align-items-center justify-content-between border rounded px-3 py-2 mb-2"
                >
                  <div class="fw-medium font-15">
                    {{ item.name }}
                    <small class="text-muted ms-1" v-if="item.price > 0">
                      (+R$ {{ formatPrice(item.price) }})
                    </small>
                  </div>

                  <div class="d-flex align-items-center gap-2">
                    <button
                      class="btn btn-sm btn-outline-danger"
                      @click="decreaseAditional(item.id)"
                    >
                      -
                    </button>

                    <span class="fw-bold">
                      {{ getAditionalQty(item.id) }}
                    </span>

                    <button
                      class="btn btn-sm btn-outline-success"
                      @click="increaseAditional(item)"
                    >
                      +
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 d-flex mt-3">
            <button 
              class="btn btn-sm btn-primary font-15 w-100 py-2"
              :disabled="!canSubmit"
              @click="addToCart"
            >
              <span class="me-2 bi" :class="product?.isCombo ? 'bi-gift' : 'bi-cart-plus'"></span> 
              {{ isEditing ? 'Atualizar' : (product?.isCombo ? 'Adicionar Combo' : 'Adicionar ao Carrinho') }}
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref, computed, watch, nextTick } from 'vue'
  import { useCartStore } from '@/stores/useCartStore'
  import { useToast } from 'vue-toastification'

  const cart = useCartStore()
  const toast = useToast()

  const props = defineProps({
    show: Boolean,
    product: Object
  })

  const emit = defineEmits(['update:show'])

  // Estados para personalizações de produtos normais
  const selectedOption = ref(null)
  const selectedSize = ref(null)
  const selectedFlavors = ref([])
  const aditionalState = ref({})
  
  // Estados para combos
  const comboAddonsState = ref({})
  const comboItemSelections = ref({})
  
  // Estados originais para edição
  const originalSelectedOption = ref(null)
  const originalSelectedSize = ref(null)
  const originalSelectedFlavors = ref([])
  const originalAditionalsState = ref({})

  // Computed properties
  const hasOptions = computed(() => {
    return props.product?.options?.length > 0 && !props.product?.isCombo
  })

  const hasSizes = computed(() => {
    return props.product?.customization?.hasSize && props.product?.customization?.sizes?.length > 0 && !props.product?.isCombo
  })

  const hasFlavors = computed(() => {
    return props.product?.customization?.hasFlavors && props.product?.customization?.flavors?.length > 0 && !props.product?.isCombo
  })

  const hasAditionals = computed(() => {
    return ((props.product?.aditionals?.length > 0) || 
           (props.product?.customization?.hasToppings && props.product?.customization?.toppings?.length > 0)) &&
           !props.product?.isCombo
  })

  const isCombo = computed(() => {
    return props.product?.isCombo === true
  })

  const maxFlavors = computed(() => {
    return props.product?.customization?.maxFlavors || 
           (props.product?.cuisineType === 'pizza' ? 2 : 
           (props.product?.cuisineType === 'acai' ? 3 : 0))
  })

  const maxFlavorsReached = computed(() => {
    return selectedFlavors.value.length >= maxFlavors.value
  })

  const aditionalGroups = computed(() => {
    if (props.product?.aditionals?.length > 0) {
      return props.product.aditionals
    }
    
    if (props.product?.customization?.hasToppings && props.product?.customization?.toppings?.length > 0) {
      return [{
        title: 'Adicionais',
        items: props.product.customization.toppings
      }]
    }
    
    return []
  })

  const optionGroups = computed(() => {
    if (props.product?.options?.length > 0) {
      return props.product.options
    }
    return []
  })

  const currentPrice = computed(() => {
    if (selectedSize.value) {
      return selectedSize.value.price
    }
    return props.product?.price || 0
  })

  const currentOldPrice = computed(() => {
    if (selectedSize.value && selectedSize.value.oldPrice) {
      return selectedSize.value.oldPrice
    }
    return props.product?.oldPrice || null
  })

  const discount = computed(() => {
    const old = currentOldPrice.value
    const curr = currentPrice.value
    if (!old || old <= curr) return 0
    return Math.round(100 - (curr / old) * 100)
  })

  const finalPrice = computed(() => {
    let price = currentPrice.value
    
    if (!isCombo.value) {
      selectedFlavors.value.forEach(flavor => {
        price += flavor.price || 0
      })
      
      Object.entries(aditionalState.value).forEach(([id, quantity]) => {
        const item = findAditionalById(parseInt(id))
        if (item && item.price) {
          price += item.price * quantity
        }
      })
    } else {
      price = props.product?.price || 0
      
      // Adiciona preço dos addons
      Object.entries(comboAddonsState.value).forEach(([id, quantity]) => {
        const addon = props.product?.comboAddons?.find(a => a.id === parseInt(id))
        if (addon && addon.price) {
          price += addon.price * quantity
        }
      })
      
      // Adiciona preço das escolhas dos itens
      Object.values(comboItemSelections.value).forEach(selection => {
        if (!selection) return
        if (selection.type === 'select' || selection.type === 'radio') {
          if (selection.choicePrice) {
            price += selection.choicePrice
          }
        } else if (selection.type === 'checkbox' || selection.type === 'multicheckbox') {
          Object.entries(selection.quantities || {}).forEach(([choiceId, qty]) => {
            price += (selection.choicePrices?.[choiceId] || 0) * qty
          })
        }
      })
    }
    
    return price
  })

  const isEditing = computed(() => {
    if (props.product?.isEditing) return true
    if (props.product?.originalSelectedOption !== null && 
        props.product?.originalSelectedOption !== undefined) return true
    if (props.product?.originalSelectedSize !== null && 
        props.product?.originalSelectedSize !== undefined) return true
    if (props.product?.originalSelectedFlavors && 
        props.product.originalSelectedFlavors.length > 0) return true
    return false
  })
    
  const canSubmit = computed(() => {
    if (isEditing.value) return true
    if (isCombo.value) {
      // Verifica se todas as opções obrigatórias foram selecionadas
      if (!props.product?.comboItems) return true
      
      for (const item of props.product.comboItems) {
        if (item.options && item.options.required) {
          const selection = comboItemSelections.value[item.id]
          if (!selection) return false
          
          if (item.options.type === 'select' || item.options.type === 'radio') {
            if (!selection.selected) return false
          } else if (item.options.type === 'checkbox' || item.options.type === 'multicheckbox') {
            const totalCount = getTotalSelectionsCount(item.id)
            if (totalCount === 0) return false
          }
        }
      }
      return true
    }
    if (hasOptions.value && !selectedOption.value) return false
    if (hasSizes.value && !selectedSize.value) return false
    if (hasFlavors.value && selectedFlavors.value.length === 0) return false
    return true
  })

  const formattedCategory = computed(() => {
    if (!props.product?.category) return ''
    return props.product.category
      .toLowerCase()
      .replace(/\b\w/g, l => l.toUpperCase())
  })

  // Funções auxiliares
  const findAditionalById = (id) => {
    for (const group of aditionalGroups.value) {
      const item = group.items.find(i => i.id === id)
      if (item) return item
    }
    return null
  }

  const isOptionSelected = (item) => selectedOption.value === item.id
  const isFlavorSelected = (flavor) => selectedFlavors.value.some(f => f.id === flavor.id)

  const selectOption = (item) => { selectedOption.value = item.id }
  
  const toggleFlavor = (flavor) => {
    const index = selectedFlavors.value.findIndex(f => f.id === flavor.id)
    if (index > -1) {
      selectedFlavors.value.splice(index, 1)
    } else {
      if (!maxFlavorsReached.value) {
        selectedFlavors.value.push(flavor)
      }
    }
  }

  const getAditionalQty = (id) => aditionalState.value[id] || 0
  
  const increaseAditional = (item) => {
    const current = aditionalState.value[item.id] || 0
    const maxStock = item.stock || 99
    if (current >= maxStock) return
    aditionalState.value[item.id] = current + 1
  }
  
  const decreaseAditional = (id) => {
    const current = aditionalState.value[id] || 0
    if (current <= 0) return
    aditionalState.value[id] = current - 1
  }

  // ========== FUNÇÕES PARA OPÇÕES DINÂMICAS DO COMBO ==========
  
  // Função segura para obter o valor da seleção
  const getSelectionValue = (itemId) => {
    return comboItemSelections.value[itemId]?.selected || null
  }

  // Verifica se um checkbox está selecionado
  const isChoiceSelected = (itemId, choiceId) => {
    const selections = comboItemSelections.value[itemId]
    if (!selections) return false
    return selections.selected?.includes(choiceId) || false
  }

  // Inicializa as seleções dos itens do combo
  const initComboItemSelections = () => {
    // REMOVA ESTA LINHA QUE ESTÁ CAUSANDO O ERRO
    // if (!props.product?.isCombo) 
    //   console.log('Produto não é combo, pulando inicialização de seleções')
    //   return
    
    // SUBSTITUA POR ESTA VERIFICAÇÃO CORRETA:
    if (!props.product?.isCombo) {
      console.log('Produto não é combo, pulando inicialização de seleções')
      return
    }
    
    const selections = {}
    
    props.product.comboItems.forEach(item => {
      if (item.options) {
        if (item.options.type === 'select' || item.options.type === 'radio') {
          const defaultChoice = item.options.choices.find(c => c.default) || item.options.choices[0]
          selections[item.id] = {
            type: item.options.type,
            selected: defaultChoice?.id || null,
            choicePrice: defaultChoice?.price || 0,
            choiceName: defaultChoice?.name || ''
          }
        } else if (item.options.type === 'checkbox' || item.options.type === 'multicheckbox') {
          selections[item.id] = {
            type: item.options.type,
            selected: [],
            quantities: {},
            choicePrices: {},
            maxSelections: item.options.maxSelections || 99
          }
        }
      }
    })
    
    comboItemSelections.value = selections
  }

  // Verifica se um checkbox está desabilitado (limite atingido e não está selecionado)
  const isCheckboxDisabled = (itemId, choiceId) => {
    const selections = comboItemSelections.value[itemId]
    if (!selections) return true
    
    const item = props.product?.comboItems?.find(i => i.id === itemId)
    if (!item) return true
    
    const isSelected = selections.selected?.includes(choiceId) || false
    const currentTotal = getTotalSelectionsCount(itemId)
    const maxSelections = item.options?.maxSelections || 99
    
    return !isSelected && currentTotal >= maxSelections
  }

  // Verifica se os botões de quantidade estão desabilitados
  const isIncreaseDisabled = (itemId, choiceId) => {
    const selections = comboItemSelections.value[itemId]
    if (!selections) return true
    
    const item = props.product?.comboItems?.find(i => i.id === itemId)
    if (!item) return true
    
    const choice = item.options?.choices?.find(c => c.id === choiceId)
    if (!choice) return true
    
    const currentQty = selections.quantities?.[choiceId] || 0
    const currentTotal = getTotalSelectionsCount(itemId)
    const maxSelections = item.options?.maxSelections || 99
    const maxPerOption = choice.maxPerOption || 99
    
    return currentTotal >= maxSelections || currentQty >= maxPerOption
  }

  const isDecreaseDisabled = (itemId, choiceId) => {
    const selections = comboItemSelections.value[itemId]
    if (!selections) return true
    
    const currentQty = selections.quantities?.[choiceId] || 0
    return currentQty <= 0
  }

  // Atualiza seleção única (select/radio)
  const updateComboItemSelection = (itemId, choiceId) => {
    const item = props.product?.comboItems?.find(i => i.id === itemId)
    if (!item) return
    
    const choice = item.options?.choices?.find(c => c.id === parseInt(choiceId))
    if (choice && comboItemSelections.value[itemId]) {
      comboItemSelections.value[itemId].selected = choiceId
      comboItemSelections.value[itemId].choicePrice = choice.price || 0
      comboItemSelections.value[itemId].choiceName = choice.name
      toast.success(`${item.name}: ${choice.name} selecionado!`, { timeout: 1500 })
    }
  }

  // Atualiza seleção múltipla (checkbox)
  const updateComboMultiSelection = (itemId, choiceId, isChecked) => {
    const item = props.product?.comboItems?.find(i => i.id === itemId)
    if (!item) return
    
    const selections = comboItemSelections.value[itemId]
    if (!selections) return
    
    const choice = item.options?.choices?.find(c => c.id === choiceId)
    if (!choice) return
    
    const currentTotal = getTotalSelectionsCount(itemId)
    const maxSelections = item.options?.maxSelections || 99
    
    if (isChecked) {
      if (currentTotal < maxSelections) {
        selections.selected.push(choiceId)
        if (choice.maxPerOption) {
          selections.quantities[choiceId] = 1
        } else {
          selections.quantities[choiceId] = 1
        }
        selections.choicePrices[choiceId] = choice.price || 0
        toast.success(`${choice.name} adicionado!`, { timeout: 1000 })
      } else {
        toast.warning(`Máximo de ${maxSelections} itens permitidos! Desmarque algum item para adicionar outro.`, { timeout: 3000 })
      }
    } else {
      const index = selections.selected.indexOf(choiceId)
      if (index > -1) {
        selections.selected.splice(index, 1)
        delete selections.quantities[choiceId]
        delete selections.choicePrices[choiceId]
        toast.info(`${choice.name} removido`, { timeout: 1000 })
      }
    }
  }

  // Aumenta quantidade de uma escolha
  const increaseChoiceQuantity = (itemId, choiceId) => {
    const item = props.product?.comboItems?.find(i => i.id === itemId)
    if (!item) return
    
    const selections = comboItemSelections.value[itemId]
    if (!selections) return
    
    const choice = item.options?.choices?.find(c => c.id === choiceId)
    if (!choice) return
    
    const currentQty = selections.quantities?.[choiceId] || 0
    const currentTotal = getTotalSelectionsCount(itemId)
    const maxSelections = item.options?.maxSelections || 99
    const maxPerOption = choice.maxPerOption || 99
    
    if (currentTotal >= maxSelections) {
      toast.warning(`Máximo de ${maxSelections} itens atingido! Desmarque algum item para adicionar mais.`, { timeout: 3000 })
      return
    }
    
    if (currentQty >= maxPerOption) {
      toast.warning(`Máximo de ${maxPerOption} ${choice.name} permitido!`, { timeout: 2000 })
      return
    }
    
    if (!selections.selected.includes(choiceId)) {
      selections.selected.push(choiceId)
    }
    
    selections.quantities[choiceId] = currentQty + 1
    selections.choicePrices[choiceId] = (choice.price || 0)
  }

  // Diminui quantidade de uma escolha
  const decreaseChoiceQuantity = (itemId, choiceId) => {
    const selections = comboItemSelections.value[itemId]
    if (!selections) return
    
    const currentQty = selections.quantities?.[choiceId] || 0
    
    if (currentQty <= 1) {
      delete selections.quantities[choiceId]
      delete selections.choicePrices[choiceId]
      const index = selections.selected.indexOf(choiceId)
      if (index > -1) {
        selections.selected.splice(index, 1)
      }
    } else {
      selections.quantities[choiceId] = currentQty - 1
    }
  }

  // Calcula total de seleções
  const getTotalSelectionsCount = (itemId) => {
    const selections = comboItemSelections.value[itemId]
    if (!selections) return 0
    
    if (selections.type === 'checkbox' || selections.type === 'multicheckbox') {
      return Object.values(selections.quantities || {}).reduce((sum, qty) => sum + qty, 0)
    }
    
    return selections.selected ? 1 : 0
  }

  // Retorna quantidade de uma escolha específica
  const getChoiceQuantity = (itemId, choiceId) => {
    return comboItemSelections.value[itemId]?.quantities?.[choiceId] || 0
  }

  // Gera resumo das escolhas do item
  const getItemChoicesSummary = (item) => {
    const selections = comboItemSelections.value[item.id]
    if (!selections) return ''
    
    const choices = []
    
    if (selections.type === 'select' || selections.type === 'radio') {
      if (selections.choiceName) {
        choices.push(selections.choiceName)
      }
    } else if (selections.type === 'checkbox' || selections.type === 'multicheckbox') {
      Object.entries(selections.quantities || {}).forEach(([choiceId, qty]) => {
        const choice = item.options?.choices?.find(c => c.id === parseInt(choiceId))
        if (choice && qty > 0) {
          choices.push(`${qty}x ${choice.name}`)
        }
      })
    }
    
    return choices.join(', ')
  }

  // Prepara as seleções do combo para adicionar ao carrinho
const prepareComboSelections = () => {
  const selections = {}
  
  Object.entries(comboItemSelections.value).forEach(([itemId, data]) => {
    const item = props.product?.comboItems?.find(i => i.id === itemId)
    if (!item || !data) return
    
    if (data.type === 'select' || data.type === 'radio') {
      // Busca o choice completo pelo ID
      const choice = item.options?.choices?.find(c => c.id === data.selected)
      if (choice) {
        selections[itemId] = {
          choice: {
            id: choice.id,
            name: choice.name,
            price: choice.price || 0
          },
          quantity: 1,
          price: choice.price || 0
        }
      } else if (data.choiceName) {
        // Fallback se não encontrar o choice
        selections[itemId] = {
          choice: {
            id: data.selected,
            name: data.choiceName,
            price: data.choicePrice || 0
          },
          quantity: 1,
          price: data.choicePrice || 0
        }
      }
    } else if (data.type === 'checkbox' || data.type === 'multicheckbox') {
      selections[itemId] = []
      Object.entries(data.quantities || {}).forEach(([choiceId, qty]) => {
        const choice = item.options?.choices?.find(c => c.id === parseInt(choiceId))
        if (choice && qty > 0) {
          selections[itemId].push({
            choice: {
              id: choice.id,
              name: choice.name,
              price: choice.price || 0
            },
            quantity: qty,
            price: (choice.price || 0) * qty
          })
        }
      })
    }
  })
  
  return selections
}

  // Funções para combo addons
  const getComboAddonQty = (id) => comboAddonsState.value[id] || 0
  
  const increaseComboAddon = (addon) => {
    const current = comboAddonsState.value[addon.id] || 0
    const maxStock = addon.maxQuantity || 99
    if (current >= maxStock) return
    comboAddonsState.value[addon.id] = current + 1
    toast.success(`${addon.name} adicionado ao combo!`, { timeout: 1500 })
  }
  
  const decreaseComboAddon = (id) => {
    const current = comboAddonsState.value[id] || 0
    if (current <= 0) return
    const addon = props.product?.comboAddons?.find(a => a.id === id)
    comboAddonsState.value[id] = current - 1
    if (addon) {
      toast.info(`${addon.name} removido do combo`, { timeout: 1500 })
    }
  }

  // Prepara combo para adicionar ao carrinho
const prepareComboForCart = () => {
  const selectedAddons = []
  Object.entries(comboAddonsState.value).forEach(([id, quantity]) => {
    if (quantity > 0) {
      const addon = props.product?.comboAddons?.find(a => a.id === parseInt(id))
      if (addon) {
        selectedAddons.push({
          id: addon.id,
          name: addon.name,
          price: addon.price || 0,
          quantity: quantity
        })
      }
    }
  })

  const itemSelections = prepareComboSelections()
  
  // Log para debug
  console.log('Preparando combo para o carrinho:', {
    comboName: props.product.name,
    itemSelections: itemSelections,
    selectedAddons: selectedAddons
  })

  return {
    id: `${props.product.id}_${Date.now()}`,
    productId: props.product.id,
    name: props.product.name,
    description: props.product.description,
    basePrice: props.product.price,
    finalPrice: finalPrice.value,
    quantity: 1,
    isCombo: true,
    comboItems: props.product.comboItems,
    comboAddons: props.product.comboAddons,
    selectedAddons: selectedAddons,
    itemSelections: itemSelections,
    image: props.product.images?.[0] || props.product.image,
    savings: props.product.savings,
    cashback: props.product.cashback || 0
  }
}

  // Prepara produto normal
  const prepareNormalProductForCart = () => {
    const updatedAditionals = aditionalGroups.value.map(group => ({
      title: group.title,
      items: group.items.map(item => ({
        id: item.id,
        name: item.name,
        price: item.price || 0,
        stock: item.stock || 99,
        quantity: aditionalState.value[item.id] || 0
      }))
    }))

    return {
      id: props.product.id,
      name: props.product.name,
      description: props.product.description,
      price: finalPrice.value,
      originalPrice: props.product.price,
      oldPrice: currentOldPrice.value,
      image: props.product.images?.[0] || props.product.image,
      cashback: props.product.cashback || 0,
      selectedOption: selectedOption.value,
      selectedSize: selectedSize.value,
      selectedFlavors: selectedFlavors.value,
      originalSelectedOption: originalSelectedOption.value,
      originalSelectedSize: originalSelectedSize.value,
      originalSelectedFlavors: originalSelectedFlavors.value,
      aditionals: updatedAditionals,
      customization: props.product.customization,
      cuisineType: props.product.cuisineType,
      isCombo: false
    }
  }

// No ProductModal.vue - Substitua a função addToCart e o watch

function addToCart() {
  if (!props.product) return

  if (isCombo.value) {
    const comboItem = prepareComboForCart()
    console.log('Adicionando/Atualizando combo:', comboItem)
    
    if (isEditing.value && props.product.cartItemId) {
      // MODO EDIÇÃO - Atualiza o item existente
      // ⭐ IMPORTANTE: Mantém o ID original do item do carrinho
      const updatedItem = {
        ...comboItem,
        id: props.product.cartItemId, // Mantém o ID original do carrinho
        cartItemId: props.product.cartItemId,
        quantity: 1
      }
      
      console.log('Atualizando item com ID:', props.product.cartItemId)
      cart.updateItem(updatedItem)
      toast.success(`${props.product.name} atualizado com sucesso!`, { timeout: 3000 })
    } else {
      // MODO ADIÇÃO - Adiciona novo item (o cartStore gera o ID)
      cart.add(comboItem)
      toast.success(`${props.product.name} adicionado ao carrinho!`, { timeout: 3000 })
    }
  } else {
    const productToAdd = prepareNormalProductForCart()

    if (isEditing.value && props.product.cartItemId) {
      // MODO EDIÇÃO - Produto normal
      // ⭐ IMPORTANTE: Mantém o ID original do item do carrinho
      const updatedItem = {
        ...productToAdd,
        id: props.product.cartItemId, // Mantém o ID original do carrinho
        cartItemId: props.product.cartItemId,
        quantity: 1
      }
      cart.updateItem(updatedItem)
      toast.success(`${props.product.name} atualizado com sucesso!`, { timeout: 2000 })
    } else {
      cart.add(productToAdd)
      toast.success(`${props.product.name} adicionado ao carrinho!`, { timeout: 2000 })
    }
  }

  close()
}

// ⭐ CORRIGIR O WATCH PARA CARREGAR OS DADOS CORRETAMENTE
watch(
  () => props.show,
  async (newShow) => {
    if (newShow && props.product) {
      // Reset antes de inicializar
      resetState()
      await nextTick()
      
      if (props.product.isCombo) {
        console.log('🔄 Inicializando combo para edição:', props.product.name)
        
        // Se veio com selections preenchidas (modo edição)
        if (props.product.comboItemSelections && Object.keys(props.product.comboItemSelections).length > 0) {
          console.log('📦 Carregando seleções existentes:', props.product.comboItemSelections)
          comboItemSelections.value = JSON.parse(JSON.stringify(props.product.comboItemSelections))
        }
        
        // Se veio com addons preenchidos
        if (props.product.comboAddonsState && Object.keys(props.product.comboAddonsState).length > 0) {
          console.log('➕ Carregando addons existentes:', props.product.comboAddonsState)
          comboAddonsState.value = JSON.parse(JSON.stringify(props.product.comboAddonsState))
        }
        
        // Se não tinha seleções, inicializa com defaults
        if (Object.keys(comboItemSelections.value).length === 0) {
          console.log('🆕 Inicializando seleções padrão')
          initComboItemSelections()
        }
        
        // ⭐ CARREGAR SELEÇÕES EXISTENTES DO ITEM (para edição)
        if (props.product.itemSelections && Object.keys(props.product.itemSelections).length > 0) {
          console.log('🔄 Carregando itemSelections existentes do produto:', props.product.itemSelections)
          // Converter itemSelections para o formato do modal
          Object.entries(props.product.itemSelections).forEach(([itemId, selectionData]) => {
            if (comboItemSelections.value[itemId]) {
              if (Array.isArray(selectionData)) {
                // Formato checkbox
                comboItemSelections.value[itemId].selected = selectionData.map(s => s.choice.id)
                comboItemSelections.value[itemId].quantities = selectionData.reduce((acc, s) => {
                  acc[s.choice.id] = s.quantity
                  return acc
                }, {})
                comboItemSelections.value[itemId].choicePrices = selectionData.reduce((acc, s) => {
                  acc[s.choice.id] = s.choice.price
                  return acc
                }, {})
              } else if (selectionData.choice) {
                // Formato select/radio
                comboItemSelections.value[itemId].selected = selectionData.choice.id
                comboItemSelections.value[itemId].choicePrice = selectionData.choice.price || 0
                comboItemSelections.value[itemId].choiceName = selectionData.choice.name
              }
            }
          })
        }
        
        // ⭐ CARREGAR ADDONS EXISTENTES
        if (props.product.selectedAddons && props.product.selectedAddons.length > 0) {
          console.log('➕ Carregando selectedAddons existentes:', props.product.selectedAddons)
          props.product.selectedAddons.forEach(addon => {
            comboAddonsState.value[addon.id] = addon.quantity
          })
        }
        
      } else {
        // Carrega dados do produto normal
        console.log('🍔 Inicializando produto normal para edição:', props.product.name)
        
        if (props.product.selectedOption) {
          selectedOption.value = props.product.selectedOption
          originalSelectedOption.value = props.product.selectedOption
        }
        if (props.product.selectedSize) {
          selectedSize.value = props.product.selectedSize
          originalSelectedSize.value = props.product.selectedSize
        }
        if (props.product.selectedFlavors && props.product.selectedFlavors.length) {
          selectedFlavors.value = [...props.product.selectedFlavors]
          originalSelectedFlavors.value = [...props.product.selectedFlavors]
        }

        // ⭐ CARREGAR ADICIONAIS EXISTENTES
        const state = {}
        const originalState = {}
        
        if (props.product.aditionals && props.product.aditionals.length > 0) {
          props.product.aditionals.forEach(group => {
            group.items.forEach(item => {
              const quantity = item.quantity || 0
              state[item.id] = quantity
              originalState[item.id] = quantity
            })
          })
        }
        
        // ⭐ SE VEIO COM aditionalsState, USA ELE
        if (props.product.aditionalsState && Object.keys(props.product.aditionalsState).length > 0) {
          console.log('📦 Carregando aditionalsState existentes:', props.product.aditionalsState)
          Object.entries(props.product.aditionalsState).forEach(([id, quantity]) => {
            state[parseInt(id)] = quantity
          })
        }
        
        aditionalState.value = state
        originalAditionalsState.value = originalState
      }
    }
  }
)
  // Reset completo do estado
  const resetState = () => {
    selectedOption.value = null
    selectedSize.value = null
    selectedFlavors.value = []
    aditionalState.value = {}
    comboAddonsState.value = {}
    comboItemSelections.value = {}
    
    originalSelectedOption.value = null
    originalSelectedSize.value = null
    originalSelectedFlavors.value = []
    originalAditionalsState.value = {}
  }

  // Watch para quando o modal abre
  watch(
    () => props.show,
    async (newShow) => {
      if (newShow && props.product) {
        // Reset antes de inicializar
        resetState()
        await nextTick()
        
        if (props.product.isCombo) {
          // Se veio com selections preenchidas (modo edição)
          if (props.product.comboItemSelections && Object.keys(props.product.comboItemSelections).length > 0) {
            console.log('Carregando seleções existentes:', props.product.comboItemSelections)
            comboItemSelections.value = JSON.parse(JSON.stringify(props.product.comboItemSelections))
          }
          
          // Se veio com addons preenchidos
          if (props.product.comboAddonsState && Object.keys(props.product.comboAddonsState).length > 0) {
            console.log('Carregando addons existentes:', props.product.comboAddonsState)
            comboAddonsState.value = JSON.parse(JSON.stringify(props.product.comboAddonsState))
          }
          
          // Se não tinha seleções, inicializa com defaults
          if (Object.keys(comboItemSelections.value).length === 0) {
            initComboItemSelections()
          }
        } else {
          // Carrega dados do produto normal
          if (props.product.selectedOption) {
            selectedOption.value = props.product.selectedOption
            originalSelectedOption.value = props.product.selectedOption
          }
          if (props.product.selectedSize) {
            selectedSize.value = props.product.selectedSize
            originalSelectedSize.value = props.product.selectedSize
          }
          if (props.product.selectedFlavors && props.product.selectedFlavors.length) {
            selectedFlavors.value = [...props.product.selectedFlavors]
            originalSelectedFlavors.value = [...props.product.selectedFlavors]
          }

          const state = {}
          const originalState = {}
          
          if (props.product.aditionals && props.product.aditionals.length > 0) {
            props.product.aditionals.forEach(group => {
              group.items.forEach(item => {
                const quantity = item.quantity || 0
                state[item.id] = quantity
                originalState[item.id] = quantity
              })
            })
          }
          
          if (props.product.customization?.hasToppings && props.product.customization?.toppings) {
            props.product.customization.toppings.forEach(item => {
              if (state[item.id] === undefined) {
                state[item.id] = 0
                originalState[item.id] = 0
              }
            })
          }
          
          aditionalState.value = state
          originalAditionalsState.value = originalState
        }
      }
    }
  )

  const close = () => {
    emit('update:show', false)
    // Reset após fechar
    setTimeout(() => {
      resetState()
    }, 100)
  }

  const formatPrice = (value) => {
    if (!value && value !== 0) return '0,00'
    return value.toFixed(2).replace('.', ',')
  }
</script>

<style scoped>
.badge-spec {
  font-size: 0.75rem;
  font-weight: 400;
}

.combo-badge {
  background: #FF8C00;
  color: white;
  font-size: clamp(0.75rem, 0.875vw, 0.875rem);
}

.font-15 {
  font-size: 0.938rem;
}

.scroll {
  height: 450px;
  overflow-y: auto;
  padding-right: 8px;
}

.image {
  max-width: 320px;
}

.z-in {
  z-index: 20;
}

.modal-dialog {
  width: 100%;
  margin: 0 auto;
  max-width: 980px;
  height: auto;
  /* max-height: 90vh; */
}

.modal-content {
  height: 600px;
  overflowY: auto;
}

.cover {
  object-fit: cover;
}

.option-item {
  cursor: pointer;
  transition: .2s;
}

.option-item:hover {
  border-color: var(--bg-primary-hover);
}

.option-item.active {
  border-color: var(--bg-primary-hover);
  background: #f8f0ff;
}

.scroll::-webkit-scrollbar {
  width: 6px;
}

.scroll::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.scroll::-webkit-scrollbar-thumb {
  background: var(--primary);
  border-radius: 10px;
}

.min-width-30 {
  min-width: 30px;
}
@media (max-width: 680px) {
  .scroll{
    height: 290px;
  }
}
</style>