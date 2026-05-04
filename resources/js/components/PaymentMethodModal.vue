<template>
  <div
    v-if="modelValue"
    class="modal fade show"
    tabindex="-1"
    style="display: block; background: rgba(0,0,0,.5)"
    @click.self="close"
  >
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content p-0">

        <!-- HEADER -->
        <div class="modal-header d-flex justify-content-between align-items-center mb-3 py-2 px-4">
          <div class="d-flex gap-2">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M0 13.6719V7.65625H19.6875V13.6719C19.6875 14.5947 18.9355 15.3125 18.0469 15.3125H1.64062C0.71875 15.3125 0 14.5947 0 13.6719ZM6.5625 11.3477V12.7148C6.5625 12.9541 6.7334 13.125 6.97266 13.125H11.6211C11.8262 13.125 12.0312 12.9541 12.0312 12.7148V11.3477C12.0312 11.1426 11.8262 10.9375 11.6211 10.9375H6.97266C6.7334 10.9375 6.5625 11.1426 6.5625 11.3477ZM2.1875 11.3477V12.7148C2.1875 12.9541 2.3584 13.125 2.59766 13.125H5.05859C5.26367 13.125 5.46875 12.9541 5.46875 12.7148V11.3477C5.46875 11.1426 5.26367 10.9375 5.05859 10.9375H2.59766C2.3584 10.9375 2.1875 11.1426 2.1875 11.3477ZM19.6875 1.64062V3.28125H0V1.64062C0 0.751953 0.71875 0 1.64062 0H18.0469C18.9355 0 19.6875 0.751953 19.6875 1.64062Z" fill="#595959"/>
            </svg>
            <h5 class="modal-title">Forma de Pagamento</h5>
          </div>
          <button type="button" class="btn-close" @click="close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <div class="row g-2">
              <div class="col-md-12">
                <label class="option-card border" :class="{ 'selected': selectedPayment === 'card' }">
                  <input type="radio" value="card" v-model="selectedPayment">
                  <div>
                    <h6 class="payment-form-title">
                      Cartão 
                      <svg width="16" height="13" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 10.9375V6.125H15.75V10.9375C15.75 11.6758 15.1484 12.25 14.4375 12.25H1.3125C0.574219 12.25 0 11.6758 0 10.9375ZM5.25 9.07812V10.1719C5.25 10.3633 5.38672 10.5 5.57812 10.5H9.29688C9.46094 10.5 9.625 10.3633 9.625 10.1719V9.07812C9.625 8.91406 9.46094 8.75 9.29688 8.75H5.57812C5.38672 8.75 5.25 8.91406 5.25 9.07812ZM1.75 9.07812V10.1719C1.75 10.3633 1.88672 10.5 2.07812 10.5H4.04688C4.21094 10.5 4.375 10.3633 4.375 10.1719V9.07812C4.375 8.91406 4.21094 8.75 4.04688 8.75H2.07812C1.88672 8.75 1.75 8.91406 1.75 9.07812ZM15.75 1.3125V2.625H0V1.3125C0 0.601562 0.574219 0 1.3125 0H14.4375C15.1484 0 15.75 0.601562 15.75 1.3125Z" fill="#595959"/>
                      </svg>
                    </h6>
                    <small>Débito ou crédito presencialmente ou via link de pagamento</small>
                  </div>
                </label>
              </div>

              <div class="col-md-12">
                <label class="option-card border" :class="{ 'selected': selectedPayment === 'pix' }">
                  <input type="radio" value="pix" v-model="selectedPayment">
                  <div>
                    <h6 class="payment-form-title">
                      Pix
                      <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 5.25V0H5.25V5.25H0ZM1.75 1.75V3.5H3.5V1.75H1.75ZM7 0H12.25V5.25H7V0ZM10.5 3.5V1.75H8.75V3.5H10.5ZM0 12.25V7H5.25V12.25H0ZM1.75 8.75V10.5H3.5V8.75H1.75ZM11.375 7H12.25V10.5H9.625V9.625H8.75V12.25H7V7H9.625V7.875H11.375V7ZM11.375 11.375H12.25V12.25H11.375V11.375ZM9.625 11.375H10.5V12.25H9.625V11.375Z" fill="#595959"/>
                      </svg>
                    </h6>
                    <small>Você receberá um código pix para pagamento via WhatsApp</small>
                  </div>
                </label>
              </div>

              <div class="col-md-12">
                <label class="option-card border" :class="{ 'selected': selectedPayment === 'cash' }">
                  <input type="radio" value="cash" v-model="selectedPayment">
                  <div>
                    <h6 class="payment-form-title">
                      Dinheiro 
                      <svg width="18" height="13" viewBox="0 0 18 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.9805 0.628906C17.2812 0.765625 17.5 1.09375 17.5 1.42188V10.1172C17.5 10.6094 17.0625 10.9648 16.5977 10.9648C16.5156 10.9648 16.4062 10.9648 16.3242 10.9375C15.4766 10.6641 14.6289 10.5547 13.7812 10.5547C10.418 10.5547 7.05469 12.25 3.69141 12.25C2.625 12.25 1.55859 12.0859 0.492188 11.6484C0.191406 11.5117 0 11.1836 0 10.8555V2.16016C0 1.66797 0.410156 1.28516 0.875 1.28516C0.957031 1.28516 1.06641 1.3125 1.14844 1.33984C1.99609 1.61328 2.84375 1.72266 3.69141 1.72266C7.05469 1.72266 10.418 0 13.7812 0C14.8477 0 15.9141 0.191406 16.9805 0.628906ZM1.3125 2.76172V4.40234C2.16016 4.40234 2.84375 3.80078 3.00781 3.00781C2.43359 2.95312 1.85938 2.89844 1.3125 2.76172ZM1.3125 10.5547C1.85938 10.7461 2.43359 10.8828 3.03516 10.9102C3.00781 9.98047 2.24219 9.24219 1.3125 9.24219V10.5547ZM8.75 8.75C9.95312 8.75 10.9375 7.60156 10.9375 6.125C10.9375 4.67578 9.95312 3.5 8.75 3.5C7.51953 3.5 6.5625 4.67578 6.5625 6.125C6.5625 7.60156 7.51953 8.75 8.75 8.75ZM16.1875 9.51562V7.95703C15.4492 8.03906 14.8477 8.58594 14.6836 9.29688C15.2031 9.32422 15.6953 9.40625 16.1875 9.51562ZM16.1875 3.0625V1.72266C15.6953 1.55859 15.1758 1.44922 14.6562 1.39453C14.6836 2.24219 15.3398 2.95312 16.1875 3.0625Z" fill="#595959"/>
                      </svg>
                    </h6>
                    <small>O pagamento será realizado presencialmente, em nosso balcão.</small>
                  </div>
                </label>
              </div>
            </div>
          </div>

          <div class="d-flex gap-2 mt-3">
            <button class="btn btn-secondary w-50" @click="close">
              Cancelar
            </button>
            <button class="btn btn-primary w-50" @click="confirmPayment" :disabled="!selectedPayment">
              Confirmar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  modelValue: Boolean,
  selectedValue: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['update:modelValue', 'payment-selected'])

const selectedPayment = ref(props.selectedValue)

watch(() => props.selectedValue, (newVal) => {
  selectedPayment.value = newVal
})

const close = () => {
  emit('update:modelValue', false)
}

const confirmPayment = () => {
  if (selectedPayment.value) {
    emit('payment-selected', selectedPayment.value)
    close()
  }
}
</script>

<style scoped>
.modal-content {
  border-radius: 0px;
  max-width: 500px;
  width: 100%;
}

.modal-title {
  font-size: clamp(1rem, 1.125vw, 1.125rem);
}

.payment-form-title {
  font-size: clamp(0.938rem, 1vw, 1rem);
}

.option-card {
  display: flex;
  gap: 10px;
  padding: 12px;
  border-radius: 8px;
  cursor: pointer;
  align-items: center;
  transition: all 0.2s ease;
  margin-bottom: 8px;
  background: #fff;
}

.option-card:last-child {
  margin-bottom: 0;
}

.option-card:hover {
  background: #f5f5f5;
  border-color: var(--bg-orange) !important;
}

.option-card.selected {
  border-color: var(--primary) !important;
  background: #f9f0f8;
}

.option-card input {
  accent-color: var(--primary);
  margin-right: 8px;
}

.option-card small {
  display: block;
  color: #777;
  font-size: 0.75rem;
  margin-top: 4px;
}

.btn-primary {
  background-color: var(--primary);
  border-color: var(--primary);
}

.btn-primary:hover {
  background-color: var(--bg-hover);
  border-color: var(--bg-hover);
}

.btn-primary:disabled {
  background-color: #ccc;
  border-color: #ccc;
}

.btn-secondary {
  background-color: #6c757d;
  border-color: #6c757d;
}

.btn-secondary:hover {
  background-color: #5a6268;
  border-color: #5a6268;
}
</style>