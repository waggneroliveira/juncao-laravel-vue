<template>
  <div
    v-if="modelValue"
    class="modal fade show"
    tabindex="-1"
    style="display: block; background: rgba(0,0,0,.5)"
    @click.self="close"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content p-0">

        <!-- HEADER -->
        <div class="modal-header d-flex justify-content-between align-items-center mb-3 py-2 px-4">
          <div class="d-flex gap-2">
            <svg width="24" height="27" viewBox="0 0 24 27" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12.3556 14.8065C16.3556 14.6323 19.5556 11.3661 19.5556 7.40323C19.5556 3.30968 16.1778 0 12 0C7.82222 0 4.44444 3.30968 4.44444 7.40323C4.44444 11.3661 7.64444 14.5887 11.6444 14.8065C5.06667 15.0242 0 20.1629 0 27H1.77778C1.77778 20.9032 6.17778 16.5484 12 16.5484C17.8222 16.5484 22.2222 20.9032 22.2222 27H24C24 20.1629 18.9333 15.0242 12.3556 14.8065ZM6.22222 7.44677C6.22222 4.31129 8.8 1.78548 12 1.78548C15.2 1.78548 17.7778 4.31129 17.7778 7.44677C17.7778 10.5823 15.2 13.1081 12 13.1081C8.8 13.1081 6.22222 10.5387 6.22222 7.44677Z" fill="#595959"/>
            </svg>
            <h5 class="modal-title">Selecione a forma de entrega</h5>
          </div>
          <button type="button" class="btn-close" @click="close"></button>
        </div>

        <div class="modal-body" :class="{ 'loading-overlay': isLoading }">
          <div class="step-section">
            <div class="mb-3">
              <label class="form-label">Como deseja receber seu pedido?</label>
              <div class="row g-2">
                <!-- Delivery -->
                <div class="col-md-12">
                  <label class="option-card border">
                    <input type="radio" value="delivery" v-model="selectedMethod" :disabled="isLoading">
                    <div>
                      <h6 class="payment-form-title">
                        Delivery 
                        <svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M1.75 9.81067H15.75V13.81067H1.75V9.81067Z" fill="#595959" stroke="#595959" stroke-width="1.5"/>
                          <path d="M3.75 15.8107C4.85457 15.8107 5.75 14.9152 5.75 13.8107C5.75 12.7061 4.85457 11.8107 3.75 11.8107C2.64543 11.8107 1.75 12.7061 1.75 13.8107C1.75 14.9152 2.64543 15.8107 3.75 15.8107Z" fill="#595959"/>
                          <path d="M13.75 15.8107C14.8546 15.8107 15.75 14.9152 15.75 13.8107C15.75 12.7061 14.8546 11.8107 13.75 11.8107C12.6454 11.8107 11.75 12.7061 11.75 13.8107C11.75 14.9152 12.6454 15.8107 13.75 15.8107Z" fill="#595959"/>
                          <path d="M12.75 5.81067L16.75 1.81067V7.81067H12.75V5.81067Z" fill="#595959" stroke="#595959" stroke-width="1.5"/>
                          <path d="M12.75 1.81067H7.75V5.81067H12.75V1.81067Z" fill="#595959" stroke="#595959" stroke-width="1.5"/>
                          <path d="M8.75 5.81067H0.75V9.81067H8.75V5.81067Z" fill="#595959" stroke="#595959" stroke-width="1.5"/>
                        </svg>
                      </h6>
                      <small>Entrega no endereço • 30-45 min</small>
                    </div>
                  </label>
                </div>

                <!-- Retirada no Local -->
                <div class="col-md-12">
                  <label class="option-card border">
                    <input type="radio" value="pickup" v-model="selectedMethod" :disabled="isLoading">
                    <div>
                      <h6 class="payment-form-title">
                        Retirada no Local 
                        <svg width="18" height="15" viewBox="0 0 18 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M17.1004 12.353H0.900391V14.1177H17.1004V12.353Z" fill="#595959"/>
                          <path d="M7.20039 7.05884H5.40039V12.353H7.20039V7.05884Z" fill="#595959"/>
                          <path d="M12.5998 7.05884H10.7998V12.353H12.5998V7.05884Z" fill="#595959"/>
                          <path d="M6.3 7.05883C7.29411 7.05883 8.1 6.26874 8.1 5.29412C8.1 4.3195 7.29411 3.52942 6.3 3.52942C5.30589 3.52942 4.5 4.3195 4.5 5.29412C4.5 6.26874 5.30589 7.05883 6.3 7.05883Z" stroke="#595959" stroke-width="1.5"/>
                          <path d="M11.7004 7.05883C12.6945 7.05883 13.5004 6.26874 13.5004 5.29412C13.5004 4.3195 12.6945 3.52942 11.7004 3.52942C10.7063 3.52942 9.90039 4.3195 9.90039 5.29412C9.90039 6.26874 10.7063 7.05883 11.7004 7.05883Z" stroke="#595959" stroke-width="1.5"/>
                          <path d="M15.3002 1.76471H2.7002V5.29412H15.3002V1.76471Z" stroke="#595959" stroke-width="1.5"/>
                        </svg>
                      </h6>
                      <small>Você retira no balcão • 15-30 min</small>
                    </div>
                  </label>
                </div>

                <!-- Consumo no Local -->
                <div class="col-md-12">
                  <label class="option-card border">
                    <input type="radio" value="local" v-model="selectedMethod" :disabled="isLoading">
                    <div>
                      <h6 class="payment-form-title">
                        Consumo no Local
                        <svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M0.75 12.75V4.75H16.75V12.75H0.75Z" stroke="#595959" stroke-width="1.5"/>
                          <path d="M4.75 4.75V0.75H12.75V4.75" stroke="#595959" stroke-width="1.5"/>
                          <path d="M8.75 8.75L6.75 12.75H10.75L8.75 8.75Z" fill="#595959"/>
                        </svg>
                      </h6>
                      <small>Pagamento presencial</small>
                    </div>
                  </label>
                </div>
              </div>
            </div>

            <div class="d-flex gap-2 mt-3">
              <button class="btn btn-secondary w-50" @click="close" :disabled="isLoading">
                Cancelar
              </button>
              <button class="btn btn-primary w-50" @click="confirmSelection" :disabled="isLoading || !selectedMethod">
                <span v-if="isLoading">Confirmando...</span>
                <span v-else>Confirmar</span>
              </button>
            </div>
          </div>

          <div v-if="isLoading" class="loading-spinner position-absolute d-flex justify-content-center align-items-center w-100 h-100">
            <div class="spinner-border" role="status">
              <span class="visually-hidden">Carregando...</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <AddressModal 
    v-model="showAddressModal"
    @address-selected="handleAddressSelected"
  />
</template>

<script setup>
import { ref, watch } from 'vue'
import { useToast } from 'vue-toastification'
import AddressModal from './AddressModal.vue'
import { useUserStore } from '@/stores/useUserStore'

const toast = useToast()
const props = defineProps({
  modelValue: Boolean
})

const emit = defineEmits(['update:modelValue', 'method-selected'])

const userStore = useUserStore()

// Muda de deliveryMethod para selectedMethod para evitar conflito
const selectedMethod = ref('')
const isLoading = ref(false)
const showAddressModal = ref(false)
const pendingMethod = ref(null)

const deliveryMethodsOptions = {
  delivery: { value: 'delivery', label: 'Entrega Delivery', timeEstimate: '30-45 min' },
  pickup: { value: 'pickup', label: 'Retirada na loja', timeEstimate: '15-30 min' },
  local: { value: 'local', label: 'Consumo no Local', timeEstimate: null }
}

// Carrega o método atual salvo no localStorage
const loadCurrentMethod = () => {
  const savedMethod = localStorage.getItem('selectedDeliveryMethod')
  if (savedMethod) {
    try {
      const method = JSON.parse(savedMethod)
      selectedMethod.value = method.value
    } catch (e) {
      console.error('Erro ao carregar método:', e)
    }
  } else {
    selectedMethod.value = ''
  }
}

const hasSelectedAddress = () => {
  const savedAddressId = localStorage.getItem('selectedAddressId')
  const savedAddress = localStorage.getItem('selectedAddress')
  return !!(savedAddressId && savedAddress)
}

const handleAddressSelected = async (address) => {
  showAddressModal.value = false
  
  if (address && pendingMethod.value === 'delivery') {
    const methodData = deliveryMethodsOptions[pendingMethod.value]
    window.dispatchEvent(new CustomEvent('addresses-updated'))
    emit('method-selected', methodData)
    close()
    toast.success(`Endereço selecionado: ${formatAddress(address)}`, { timeout: 3000 })
  } else if (!address && pendingMethod.value === 'delivery') {
    toast.warning('Para entrega em domicílio, é necessário selecionar um endereço!', { timeout: 3000 })
    loadCurrentMethod()
  }
  
  pendingMethod.value = null
  isLoading.value = false
}

const formatAddress = (address) => {
  if (!address) return ''
  const parts = []
  if (address.street) parts.push(address.street)
  if (address.number) parts.push(address.number)
  if (address.neighborhood) parts.push(address.neighborhood)
  if (address.city) parts.push(address.city)
  return parts.join(', ')
}

const confirmSelection = async () => {
  if (!selectedMethod.value) {
    toast.warning('Selecione uma forma de entrega!', { timeout: 3000 })
    return
  }

  isLoading.value = true

  if (selectedMethod.value === 'delivery') {
    // 🔥 REMOVIDA a verificação de login aqui!
    // O usuário pode selecionar delivery mesmo sem estar logado
    // O cadastro será feito depois no IdentifyModal
    
    const hasAddress = hasSelectedAddress()
    
    if (!hasAddress) {
      // Se não tem endereço, abre o modal de endereço APENAS se o usuário já estiver logado
      // ou se estiver vindo de um fluxo onde o cadastro já foi iniciado
      if (userStore.isLogged) {
        pendingMethod.value = selectedMethod.value
        showAddressModal.value = true
        isLoading.value = false
        return
      } else {
        // Se não está logado, apenas emite o método selecionado
        // O IdentifyModal vai pedir cadastro depois
        const methodData = deliveryMethodsOptions[selectedMethod.value]
        emit('method-selected', methodData)
        close()
        isLoading.value = false
        return
      }
    }
  }

  const methodData = deliveryMethodsOptions[selectedMethod.value]
  emit('method-selected', methodData)
  close()
  
  isLoading.value = false
}

const close = () => {
  emit('update:modelValue', false)
}

// Quando abrir o modal, carrega o método atual
watch(() => props.modelValue, (open) => {
  if (open) {
    loadCurrentMethod()
    isLoading.value = false
    pendingMethod.value = null
    showAddressModal.value = false
  }
})
</script>

<style scoped>
.modal-content {
  border-radius: 0px;
  max-width: 500px;
  width: 100%;
}

.modal-body {
  position: relative;
  min-height: 300px;
}

.modal-body input {
  height: 42px;
}

.option-card {
  display: flex;
  gap: 12px;
  padding: 12px;
  border-radius: 8px;
  cursor: pointer;
  align-items: center;
  border: 1px solid #e5e5e5;
  margin-bottom: 8px;
  transition: all 0.2s ease;
}

.option-card:last-child {
  margin-bottom: 0;
}

.option-card:hover {
  background: #f5f5f5;
  border-color: var(--bg-orange);
}

.option-card input {
  accent-color: #A4268E;
  width: 18px;
  height: 18px;
  margin: 0;
}

.option-card:has(input:checked) {
  border-color: var(--bg-orange);
  background: #FFF8F0;
}

.payment-form-title {
  font-size: clamp(0.938rem, 1vw, 1rem);
  font-weight: 600;
  margin-bottom: 4px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.option-card small {
  display: block;
  color: #777;
  font-size: 0.75rem;
}

.form-label {
  font-weight: 500;
  margin-bottom: 12px;
  display: block;
  font-size: 0.875rem;
  color: #333;
}

.step-section {
  transition: all 0.3s ease;
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.btn-primary {
  background-color: var(--primary);
  border: none;
  color: #FFF;
  font-weight: 500;
  height: 42px;
}

.btn-primary:hover,
.btn-primary:focus {
  background-color: var(--bg-hover);
  color: #FFF;
}

.btn-secondary {
  background-color: #6c757d;
  border: none;
  color: #fff;
  font-weight: 500;
  height: 42px;
}

.btn-secondary:hover {
  background-color: #5a6268;
}

.loading-spinner {
  top: 0;
  left: 0;
  background: rgba(255, 255, 255, 0.8);
}

.spinner-border {
  color: #A4268E;
}

.loading-overlay {
  position: relative;
  opacity: 0.6;
  pointer-events: none;
}

.modal-title {
  font-size: clamp(1rem, 1.125vw, 1.125rem);
  font-weight: 600;
}

.svg {
  width: 20px;
}

.btn-close {
  background: transparent;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
  opacity: 0.7;
}

.btn-close:hover {
  opacity: 1;
}
</style>