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
            <img :src="productImage" class="img-fluid w-100 rounded-3 mb-2"/>

            <div v-if="product?.is_combo" class="badge combo-badge mb-2 d-inline-block">
              Combo
            </div>

            <h5 class="fw-bold text-primary mt-2">{{ product?.name }}</h5>

            <p class="text-muted small">
              {{ product?.description }}
            </p>

            <h5 class="fw-bold">
              R$ {{ formatPrice(finalPrice) }}
            </h5>
          </div>

          <!-- RIGHT -->
          <div class="col-md-7 scroll">

            <!-- COMBO ITEMS -->
            <div v-if="product?.is_combo && product?.combo_items?.length">

              <div
                v-for="item in product.combo_items"
                :key="item.id"
                class="border rounded p-3 mb-3"
              >
                <div class="fw-semibold mb-2">
                  {{ item.quantity || 1 }}x {{ item.name }}
                </div>

                <!-- OPTIONS -->
                <div v-if="getItemOptions(item.id)">
                  
                  <label class="small fw-semibold">
                    {{ getItemOptions(item.id).name }}
                  </label>

                  <!-- SELECT -->
                  <select
                    v-if="getItemOptions(item.id).type === 'select'"
                    class="form-select form-select-sm"
                    @change="updateComboItemSelection(item.id, $event.target.value)"
                  >
                    <option
                      v-for="choice in getItemOptions(item.id).options"
                      :key="choice.id"
                      :value="choice.id"
                    >
                      {{ choice.name }}
                    </option>
                  </select>

                  <!-- RADIO -->
                  <div v-if="getItemOptions(item.id).type === 'radio'">
                    <div
                      v-for="choice in getItemOptions(item.id).options"
                      :key="choice.id"
                      class="form-check"
                    >
                      <input
                        type="radio"
                        class="form-check-input"
                        :name="'combo_' + item.id"
                        :checked="isChoiceSelected(item.id, choice.id)"
                        @change="updateComboItemSelection(item.id, choice.id)"
                      />
                      <label class="form-check-label">
                        {{ choice.name }}
                      </label>
                    </div>
                  </div>

                  <!-- CHECKBOX -->
                  <div v-if="getItemOptions(item.id).type === 'checkbox'">
                    <div
                      v-for="choice in getItemOptions(item.id).options"
                      :key="choice.id"
                      class="form-check"
                    >
                      <input
                        type="checkbox"
                        class="form-check-input"
                        :checked="isChoiceSelected(item.id, choice.id)"
                        @change="updateComboMultiSelection(item.id, choice.id, $event.target.checked)"
                      />
                      <label class="form-check-label">
                        {{ choice.name }}
                      </label>
                    </div>
                  </div>

                </div>
              </div>

            </div>

          </div>

          <!-- BOTÃO -->
          <div class="col-12 mt-3">
            <button class="btn btn-primary w-100" @click="addToCart">
              Adicionar
            </button>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  show: Boolean,
  product: Object
})

const emit = defineEmits(['update:show'])

const comboItemSelections = ref({})

const productImage = computed(() => {
  return props.product?.images?.[0]?.url || '/images/default.png'
})

const formattedCategory = computed(() => {
  return props.product?.category?.name || ''
})

/**
 * 🔥 PEGA OPTION GROUP PELO COMBO ITEM
 */
const getItemOptions = (itemId) => {
  return props.product?.option_groups?.find(
    g => g.combo_item_id === itemId
  ) || null
}

/**
 * 🔥 SELECT / RADIO
 */
const updateComboItemSelection = (itemId, choiceId) => {
  const group = getItemOptions(itemId)
  if (!group) return

  const choice = group.options?.find(c => c.id == choiceId)

  comboItemSelections.value[itemId] = {
    selected: choiceId,
    choicePrice: choice?.price || 0
  }
}

/**
 * 🔥 CHECKBOX
 */
const updateComboMultiSelection = (itemId, choiceId, checked) => {
  const group = getItemOptions(itemId)
  if (!group) return

  const choice = group.options?.find(c => c.id == choiceId)
  if (!choice) return

  if (!comboItemSelections.value[itemId]) {
    comboItemSelections.value[itemId] = {
      selected: [],
      prices: {}
    }
  }

  const state = comboItemSelections.value[itemId]

  if (checked) {
    state.selected.push(choiceId)
    state.prices[choiceId] = choice.price || 0
  } else {
    state.selected = state.selected.filter(i => i !== choiceId)
    delete state.prices[choiceId]
  }
}

/**
 * 🔥 CHECK SELECTION
 */
const isChoiceSelected = (itemId, choiceId) => {
  const sel = comboItemSelections.value[itemId]?.selected

  if (Array.isArray(sel)) {
    return sel.includes(choiceId)
  }

  return sel === choiceId
}

/**
 * 💰 PREÇO FINAL
 */
const finalPrice = computed(() => {
  let price = parseFloat(props.product?.price || 0)

  Object.values(comboItemSelections.value).forEach(sel => {
    if (sel.choicePrice) {
      price += sel.choicePrice
    }

    if (sel.prices) {
      Object.values(sel.prices).forEach(p => price += p)
    }
  })

  return price
})

const formatPrice = (v) => {
  return Number(v).toFixed(2).replace('.', ',')
}

const addToCart = () => {
  console.log('combo selections:', comboItemSelections.value)
  close()
}

const close = () => {
  emit('update:show', false)
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