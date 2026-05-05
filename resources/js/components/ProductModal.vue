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
            {{ formattedCategory }}
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
                :src="productImage"
                class="img-fluid w-100 rounded-3 mb-2"
              />
            </div>

            <!-- Badge de COMBO -->
            <div v-if="product?.is_combo" class="badge combo-badge mb-2 d-inline-block">
              <i class="bi bi-bullseye"></i>
              Combo - Economize até 15%
            </div>

            <h5 class="fw-bold text-primary mt-2">{{ product?.name }}</h5>
            <p class="text-muted small">
              {{ product?.description || 'Delicioso combo com itens selecionados' }}
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
              
              <!-- Cashback para produtos normais -->
              <span v-if="product?.cashback && !product?.is_combo" class="badge bg-warning text-dark mt-2 d-inline-block">
                {{ product.cashback }}% cashback
              </span>
            </div>
          </div>

          <!-- RIGHT - Área rolável -->
          <div class="col-md-7 scroll">
            
            <!-- SEÇÃO: Itens do Combo (para combos) -->
            <div class="col-md-12" v-if="product?.is_combo && product?.combo_items?.length">
              <div class="mb-4">
                <h6 class="fw-bold text-primary text-center mb-4">
                  Itens do Combo
                </h6>

                <div
                  v-for="(item, index) in product.combo_items"
                  :key="item.id || index"
                  class="border rounded p-3 mb-3"
                >
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                      <div class="fw-semibold">
                        {{ item.quantity || 1 }}x {{ item.name }}
                        <span v-if="item.required === 1" class="badge bg-primary ms-2">Obrigatório</span>
                      </div>
                      <small class="text-muted">
                        Incluso no combo
                      </small>
                    </div>
                  </div>
                  
                  <!-- OPÇÕES DO ITEM DO COMBO (se existirem) -->
                  <div v-if="getItemOptions(item.id)" class="mt-2 pt-2 border-top">
                    <div class="mb-2">
                      <label class="form-label fw-semibold small">{{ getItemOptions(item.id).name || 'Opções' }}</label>
                      
                      <!-- SELECT (dropdown) -->
                      <div v-if="getItemOptions(item.id).type === 'select'">
                        <select 
                          class="form-select form-select-sm"
                          :value="getSelectionValue(item.id)"
                          @change="updateComboItemSelection(item.id, $event.target.value)"
                        >
                          <option 
                            v-for="choice in getItemOptions(item.id).choices" 
                            :key="choice.id" 
                            :value="choice.id"
                          >
                            {{ choice.name }} 
                            {{ choice.price > 0 ? `(+ R$ ${formatPrice(choice.price)})` : '' }}
                          </option>
                        </select>
                      </div>
                      
                      <!-- RADIO -->
                      <div v-if="getItemOptions(item.id).type === 'radio'" class="d-flex flex-column gap-1 mt-2">
                        <div v-for="choice in getItemOptions(item.id).choices" :key="choice.id" class="form-check">
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
                          </label>
                        </div>
                      </div>
                      
                      <!-- CHECKBOX -->
                      <div v-if="getItemOptions(item.id).type === 'checkbox'" class="d-flex flex-column gap-1 mt-2">
                        <div v-for="choice in getItemOptions(item.id).choices" :key="choice.id" class="form-check">
                          <input
                            type="checkbox"
                            class="form-check-input"
                            :value="choice.id"
                            :checked="isChoiceSelected(item.id, choice.id)"
                            @change="updateComboMultiSelection(item.id, choice.id, $event.target.checked)"
                          />
                          <label class="form-check-label">
                            {{ choice.name }}
                            <span v-if="choice.price > 0" class="text-primary">(+ R$ {{ formatPrice(choice.price) }})</span>
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Resumo das escolhas -->
                  <div v-if="getItemChoicesSummary(item)" class="mt-2 pt-2 border-top bg-white rounded p-2">
                    <small class="text-muted fw-semibold">📋 Suas escolhas:</small>
                    <div class="small mt-1">
                      {{ getItemChoicesSummary(item) }}
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- SEÇÃO: Grupos de Opções (para produtos normais e combos que têm option_groups) -->
            <div class="col-md-12" v-if="product?.option_groups?.length">
              <div
                v-for="group in product.option_groups"
                :key="group.id"
                class="mb-4"
              >
                <h6 class="fw-bold text-primary text-center mb-4">
                  {{ group.name }}
                  <span v-if="!group.required" class="text-muted small">(opcional)</span>
                </h6>

                <div
                  v-for="option in getGroupOptions(group.id)"
                  :key="option.id"
                  class="d-flex align-items-center border gap-1 rounded p-0 mb-2 option-item pe-3"
                  :class="{ active: isNormalOptionSelected(option) }"
                  @click="selectNormalOption(option)"
                >
                  <img
                    :src="option.image || productImage"
                    class="me-3 cover"
                    width="80"
                    height="80"
                    style="object-fit: cover;"
                  />

                  <div class="flex-grow-1 py-2">
                    <div class="fw-semibold">
                      {{ option.name }}
                    </div>
                    <small class="text-muted" v-if="option.description">
                      {{ option.description }}
                    </small>
                    <div v-if="option.price > 0" class="text-primary small fw-bold">
                      + R$ {{ formatPrice(option.price) }}
                    </div>
                  </div>

                  <input
                    type="radio"
                    :checked="isNormalOptionSelected(option)"
                    class="me-3"
                    :disabled="group.type === 'checkbox'"
                  />
                </div>
              </div>
            </div>

            <!-- Adicionais Extras para Combo (se o backend enviar) -->
            <div class="col-md-12" v-if="product?.is_combo && product?.combo_addons?.length">
              <div class="mb-4">
                <h6 class="fw-bold text-primary text-center mb-4">
                  Adicionais Extras
                </h6>

                <div
                  v-for="addon in product.combo_addons"
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

            <!-- Adicionais para produtos normais -->
            <div class="col-md-12" v-if="!product?.is_combo && product?.option_groups?.length">
              <div
                v-for="group in product.option_groups"
                :key="`addon_${group.id}`"
                class="mb-4"
                v-if="group.type === 'checkbox'"
              >
                <h6 class="fw-bold text-primary text-center mb-3">
                  {{ group.name }}
                  <span class="text-muted small">(adicione à vontade)</span>
                </h6>

                <div
                  v-for="item in getGroupOptions(group.id)"
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

          <!-- BOTÃO ADICIONAR -->
          <div class="col-12 d-flex mt-3">
            <button 
              class="btn btn-primary font-15 w-100 py-2"
              :disabled="!canSubmit"
              @click="addToCart"
              style="background: #6f42c1; border-color: #6f42c1;"
            >
              <span class="me-2 bi" :class="product?.is_combo ? 'bi-gift' : 'bi-cart-plus'"></span> 
              {{ isEditing ? 'Atualizar' : (product?.is_combo ? 'Adicionar Combo' : 'Adicionar ao Carrinho') }}
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

  // Estados
  const selectedNormalOptions = ref({}) // Para radio/select de produtos normais
  const aditionalState = ref({}) // Para checkbox (adicionais)
  const comboAddonsState = ref({})
  const comboItemSelections = ref({})

  // Computed
  const productImage = computed(() => {
    return props.product?.images?.[0]?.url || props.product?.path_image || '/images/default.png'
  })

  const formattedCategory = computed(() => {
    if (!props.product?.category?.name) return ''
    return props.product.category.name
      .toLowerCase()
      .replace(/\b\w/g, l => l.toUpperCase())
  })

  const isCombo = computed(() => props.product?.is_combo === true)

  const currentOldPrice = computed(() => {
    return props.product?.old_price || null
  })

  const discount = computed(() => {
    const old = currentOldPrice.value
    const curr = finalPrice.value
    if (!old || old <= curr) return 0
    return Math.round(100 - (curr / old) * 100)
  })

  const finalPrice = computed(() => {
    let price = parseFloat(props.product?.price || 0)
    
    if (isCombo.value) {
      // Adiciona preço dos addons do combo
      Object.entries(comboAddonsState.value).forEach(([id, quantity]) => {
        const addon = props.product?.combo_addons?.find(a => a.id === parseInt(id))
        if (addon && addon.price) {
          price += parseFloat(addon.price) * quantity
        }
      })
      
      // Adiciona preço das escolhas dos itens do combo
      Object.values(comboItemSelections.value).forEach(selection => {
        if (selection?.choicePrice) {
          price += parseFloat(selection.choicePrice)
        }
        if (selection?.choicesPrices) {
          Object.values(selection.choicesPrices).forEach(p => {
            price += parseFloat(p)
          })
        }
      })
    } else {
      // Adiciona preço das opções selecionadas (radio/select)
      Object.values(selectedNormalOptions.value).forEach(option => {
        if (option?.price) {
          price += parseFloat(option.price)
        }
      })
      
      // Adiciona preço dos adicionais (checkbox)
      Object.entries(aditionalState.value).forEach(([id, quantity]) => {
        const option = findOptionById(parseInt(id))
        if (option && option.price) {
          price += parseFloat(option.price) * quantity
        }
      })
    }
    
    return price
  })

  const isEditing = computed(() => {
    return props.product?.cartItemId || props.product?.isEditing
  })

  const canSubmit = computed(() => {
    // Verifica grupos obrigatórios para produtos normais
    if (!isCombo.value && props.product?.option_groups?.length) {
      const requiredGroups = props.product.option_groups.filter(g => g.required === 1 && g.type !== 'checkbox')
      for (const group of requiredGroups) {
        if (!selectedNormalOptions.value[group.id]) {
          return false
        }
      }
    }
    return true
  })

  // Funções auxiliares
  const findOptionById = (id) => {
    if (!props.product?.option_groups) return null
    for (const group of props.product.option_groups) {
      const options = getGroupOptions(group.id)
      const found = options.find(opt => opt.id === id)
      if (found) return found
    }
    return null
  }

  const getGroupOptions = (groupId) => {
    // Se o backend tiver uma relação de opções, você precisa ajustar aqui
    // Por enquanto, retorna vazio - você precisa implementar baseado na sua estrutura
    return []
  }

  const getItemOptions = (itemId) => {
    // Busca opções para um item específico do combo
    // Por enquanto, retorna null - você precisa implementar baseado na sua estrutura
    return null
  }

  // Funções para opções normais (radio/select)
  const isNormalOptionSelected = (option) => {
    return selectedNormalOptions.value[option.group_id]?.id === option.id
  }

  const selectNormalOption = (option) => {
    if (option.group_id) {
      selectedNormalOptions.value[option.group_id] = option
    }
  }

  // Funções para adicionais (checkbox)
  const getAditionalQty = (id) => aditionalState.value[id] || 0
  
  const increaseAditional = (item) => {
    const current = aditionalState.value[item.id] || 0
    aditionalState.value[item.id] = current + 1
  }
  
  const decreaseAditional = (id) => {
    const current = aditionalState.value[id] || 0
    if (current <= 0) return
    aditionalState.value[id] = current - 1
  }

  // Funções para opções do combo
  const getSelectionValue = (itemId) => {
    return comboItemSelections.value[itemId]?.selected || null
  }

  const isChoiceSelected = (itemId, choiceId) => {
    return comboItemSelections.value[itemId]?.selected === choiceId
  }

  const updateComboItemSelection = (itemId, choiceId) => {
    comboItemSelections.value[itemId] = {
      selected: choiceId,
      choicePrice: 0 // Você precisa buscar o preço da choice
    }
  }

  const updateComboMultiSelection = (itemId, choiceId, isChecked) => {
    // Implementar se necessário
  }

  const getItemChoicesSummary = (item) => {
    const selection = comboItemSelections.value[item.id]
    if (!selection) return ''
    return selection.selected ? 'Selecionado' : ''
  }

  // Funções para combo addons
  const getComboAddonQty = (id) => comboAddonsState.value[id] || 0
  
  const increaseComboAddon = (addon) => {
    const current = comboAddonsState.value[addon.id] || 0
    comboAddonsState.value[addon.id] = current + 1
  }
  
  const decreaseComboAddon = (id) => {
    const current = comboAddonsState.value[id] || 0
    if (current <= 0) return
    comboAddonsState.value[id] = current - 1
  }

  // Adicionar ao carrinho
  const addToCart = () => {
    if (!props.product) return

    const itemToAdd = {
      id: props.product.id,
      name: props.product.name,
      description: props.product.description,
      price: finalPrice.value,
      originalPrice: props.product.price,
      oldPrice: currentOldPrice.value,
      image: productImage.value,
      cashback: props.product.cashback || 0,
      quantity: 1,
      isCombo: isCombo.value,
      selectedOptions: selectedNormalOptions.value,
      selectedAddons: aditionalState.value,
      comboSelections: comboItemSelections.value,
      comboAddons: comboAddonsState.value
    }

    if (isEditing.value && props.product.cartItemId) {
      cart.updateItem({ ...itemToAdd, id: props.product.cartItemId })
      toast.success(`${props.product.name} atualizado!`)
    } else {
      cart.add(itemToAdd)
      toast.success(`${props.product.name} adicionado ao carrinho!`)
    }

    close()
  }

  // Reset estado
  const resetState = () => {
    selectedNormalOptions.value = {}
    aditionalState.value = {}
    comboAddonsState.value = {}
    comboItemSelections.value = {}
  }

  // Watch para abrir modal
  watch(() => props.show, async (newShow) => {
    if (newShow && props.product) {
      resetState()
      await nextTick()
      console.log('Produto carregado:', props.product)
    }
  })

  const close = () => {
    emit('update:show', false)
    setTimeout(() => resetState(), 100)
  }

  const formatPrice = (value) => {
    if (value === null || value === undefined) return '0,00'
    let number = typeof value === 'string' ? parseFloat(value.replace(',', '.')) : value
    if (isNaN(number)) return '0,00'
    return number.toFixed(2).replace('.', ',')
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
  margin: 0 auto;
}

.image img {
  max-height: 200px;
  object-fit: cover;
}

.z-in {
  z-index: 20;
}

.modal-dialog {
  width: 100%;
  margin: 0 auto;
  max-width: 980px;
}

.modal-content {
  height: auto;
  max-height: 90vh;
  overflow-y: auto;
}

.cover {
  object-fit: cover;
  border-radius: 8px;
}

.option-item {
  cursor: pointer;
  transition: .2s;
}

.option-item:hover {
  border-color: #6f42c1;
}

.option-item.active {
  border-color: #6f42c1;
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
  background: #6f42c1;
  border-radius: 10px;
}

.min-width-30 {
  min-width: 30px;
}

@media (max-width: 680px) {
  .scroll {
    height: 290px;
  }
}
</style>