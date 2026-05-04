<template>
  <div
    v-if="modelValue"
    class="modal fade show"
    tabindex="-1"
    style="display: block; background: rgba(0,0,0,.5)"
    @click.self="close"
  >
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">

        <!-- HEADER -->
        <div class="modal-header py-2">
            <h5 class="modal-title">
                <i class="bi bi-geo-alt me-2"></i>Meus Endereços
            </h5>
            <button type="button" class="btn-close" @click="close"></button>
        </div>

        <div class="modal-body">

          <!-- LISTA -->
          <div v-if="view === 'list'" class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="address-title">Endereços cadastrados</h6>
                <!-- No lugar do botão Novo Endereço existente -->
                <button
                    v-if="addresses.length < 2"
                    class="btn btn-outline-primary btn-sm"
                    @click="showForm"
                >
                    <i class="bi bi-plus"></i> Novo Endereço
                </button>
            </div>

            <div class="row g-3">
              <div class="col-md-6" v-for="addr in addresses" :key="addr.id">
                <div
                  class="card address-card"
                  :class="{ selected: selectedId === addr.id }"
                  @click="selectAddress(addr.id)"
                >
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                      <h6 class="card-title">{{ addr.nickname }}</h6>
                      <span class="badge text-black px-3" :class="addr.primary ? 'bg-primary text-white' : 'bg-secondary'">
                        {{ addr.primary ? 'Principal' : 'Secundário' }}
                      </span>
                    </div>

                    <p class="card-text small mb-1">{{ addr.street }}, {{ addr.number }}</p>
                    <p class="card-text small mb-1">{{ addr.neighborhood }}</p>
                    <p class="card-text small mb-1">
                      {{ addr.city }} - {{ addr.state }}, {{ addr.cep }}
                    </p>

                    <div class="mt-2">
                      <button class="btn btn-sm btn-outline-secondary me-1" @click.stop="editAddress(addr.id)">
                        <i class="bi bi-pencil"></i>
                      </button>
                      <button class="btn btn-sm btn-outline-danger" @click.stop="deleteAddress(addr.id)">
                        <i class="bi bi-trash"></i>
                      </button>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- FORM -->
          <div v-else>

            <div class="progress">
              <div
                class="progress-bar"
                :style="{ width: step === 1 ? '50%' : '100%' }"
              ></div>
            </div>

            <!-- STEP 1 -->
            <div class="form-step mt-3 active" v-if="step === 1">
              <h6 class="mb-3">Informações do Endereço</h6>

              <div class="row g-3">

                <div class="col-md-6">
                  <label class="form-label">Apelido do Endereço</label>
                  <input v-model="form.nickname" class="form-control" placeholder="Ex: Casa, Trabalho...">
                </div>

                <div class="col-md-6">
                  <label class="form-label">CEP</label>
                  <div class="input-group">
                    <input
                      v-model="form.cep"
                      class="form-control"
                      maxlength="9"
                      @input="handleCepInput"
                    >
                  </div>

                  <div class="cep-loading mt-1" v-show="loadingCEP">
                    <small class="text-muted">Buscando CEP...</small>
                  </div>
                </div>

                <div class="col-md-8">
                  <label class="form-label">Rua</label>
                  <input v-model="form.street" class="form-control">
                </div>

                <div class="col-md-4">
                  <label class="form-label">Número</label>
                  <input v-model="form.number" class="form-control">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Complemento</label>
                  <input v-model="form.complement" class="form-control" placeholder="Apto, Bloco, etc.">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Bairro</label>
                  <input v-model="form.neighborhood" class="form-control">
                </div>

                <div class="col-md-8">
                  <label class="form-label">Cidade</label>
                  <input v-model="form.city" class="form-control">
                </div>

                <div class="col-md-4">
                  <label class="form-label">Estado</label>
                  <select v-model="form.state" class="form-select">
                    <option value="">Selecione</option>
                    <option value="BA">Bahia</option>
                    <option value="SP">São Paulo</option>
                  </select>
                </div>

                <div class="col-12">
                  <label class="form-label">Ponto de Referência</label>
                  <input v-model="form.reference" class="form-control">
                </div>

              </div>

              <div class="d-flex justify-content-between mt-4">
                <button class="btn bg-light" @click="showList">
                  Voltar
                </button>
                <button class="btn bg-yellow fw-medium" @click="nextStep">
                  Continuar
                </button>
              </div>
            </div>

            <!-- STEP 2 -->
            <div class="form-step mt-3" v-if="step === 2">
              <h6 class="mb-3">Instruções de Entrega</h6>

              <textarea v-model="form.instructions" class="form-control"></textarea>

              <div class="form-check mt-3">
                <input type="checkbox" v-model="form.primary" class="form-check-input">
                <label class="form-check-label">
                  Definir como endereço principal
                </label>
              </div>

              <div class="d-flex justify-content-between mt-4">
                <button class="btn bg-light" @click="prevStep">Voltar</button>
                <button class="btn bg-yellow fw-medium" @click="saveAddress">
                  Salvar Endereço
                </button>
              </div>
            </div>

          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue'
    import { useToast } from 'vue-toastification'

    const toast = useToast()

    const props = defineProps({
        modelValue: Boolean
    })

    const emit = defineEmits(['update:modelValue', 'address-selected'])

    const view = ref('list')         // 'list' ou 'form'
    const step = ref(1)              // passo do formulário
    const selectedId = ref(null)     // id do endereço selecionado
    const loadingCEP = ref(false)    // flag de loading CEP

    let debounceTimer = null

    // Função para disparar evento de atualização
    const triggerAddressUpdate = () => {
        // Dispara evento customizado para notificar o Cart
        window.dispatchEvent(new CustomEvent('addresses-updated'))
        // Também salva no storage para outras abas
        localStorage.setItem('addressesUpdated', Date.now().toString())
    }

    // lista de endereços (carrega do LocalStorage se existir)
    const addresses = ref([])
    
    const loadAddresses = () => {
        if (localStorage.getItem('addresses')) {
            addresses.value = JSON.parse(localStorage.getItem('addresses'))
        } else {
            addresses.value = []
        }
    }
    
    loadAddresses()

    // formulário
    const form = ref({
        cep: '',
        nickname: '',
        street: '',
        number: '',
        complement: '',
        neighborhood: '',
        city: '',
        state: '',
        reference: '',
        instructions: '',
        primary: false,
        id: null
    })

    /* 🔥 MÁSCARA + TRIGGER AUTOMÁTICO */
    const handleCepInput = () => {
        let v = form.value.cep.replace(/\D/g, '')

        if (v.length > 5) {
            v = v.slice(0,5) + '-' + v.slice(5,8)
        }

        form.value.cep = v

        const cepLimpo = v.replace(/\D/g, '')

        if (cepLimpo.length === 8) {
            clearTimeout(debounceTimer)
            debounceTimer = setTimeout(() => {
                searchCEP(cepLimpo)
            }, 400)
        }
    }

    /* 🔥 AJAX VIA CEP */
    const searchCEP = async (cep) => {
        loadingCEP.value = true

        try {
            const res = await fetch(`https://viacep.com.br/ws/${cep}/json/`)
            const data = await res.json()

            if (!data.erro) {
                form.value.street = data.logradouro
                form.value.neighborhood = data.bairro
                form.value.city = data.localidade
                form.value.state = data.uf
            }
        } catch (e) {
            console.error(e)
        }

        loadingCEP.value = false
    }

    /* ---- NAVIGATION ---- */
    const showForm = () => {
        view.value = 'form'
        step.value = 1
        // limpa o form apenas para novo endereço
        form.value = {
            cep: '',
            nickname: '',
            street: '',
            number: '',
            complement: '',
            neighborhood: '',
            city: '',
            state: '',
            reference: '',
            instructions: '',
            primary: false,
            id: null
        }
    }

    const showList = () => {
        view.value = 'list'
        loadAddresses() // Recarrega a lista ao voltar
        triggerAddressUpdate() // Notifica mudança
    }
    
    const nextStep = () => step.value = 2
    const prevStep = () => step.value = 1

    /* ---- ENDEREÇOS ---- */
    const selectAddress = (id) => {
        selectedId.value = id
        const selectedAddr = addresses.value.find(a => a.id === id)
        if (selectedAddr) {
            emit('address-selected', selectedAddr)
            close()
        }
    }

    const editAddress = (id) => {
        const addr = addresses.value.find(a => a.id === id)
        if (addr) {
            form.value = { ...addr } // mantém id
            view.value = 'form'
            step.value = 1
        }
    }

    const deleteAddress = (id) => {
        if (confirm('Deseja excluir este endereço?')) {
            const deletedAddress = addresses.value.find(a => a.id === id)
            const wasSelected = localStorage.getItem('selectedAddressId') === id.toString()
            
            addresses.value = addresses.value.filter(a => a.id !== id)
            localStorage.setItem('addresses', JSON.stringify(addresses.value))
            
            // Se o endereço excluído era o selecionado
            if (wasSelected) {
                // Tenta encontrar um endereço principal
                const primaryAddress = addresses.value.find(addr => addr.primary === true)
                if (primaryAddress) {
                    emit('address-selected', primaryAddress)
                } else if (addresses.value.length > 0) {
                    // Se não tem principal, seleciona o primeiro da lista
                    emit('address-selected', addresses.value[0])
                } else {
                    // Não tem mais endereços
                    emit('address-selected', null)
                }
            }
            
            triggerAddressUpdate() // Notifica mudança
            loadAddresses() // Recarrega a lista
            
            toast.success('Endereço removido com sucesso!', {
                timeout: 3000
            })
        }
    }

    /* ---- SALVAR ---- */
    const saveAddress = () => {
        // Se for definir como principal, remove primary dos outros
        if (form.value.primary) {
            addresses.value.forEach(addr => {
                addr.primary = false
            })
        }
        
        let isNewAddress = false
        
        if (form.value.id) {
            // edição: atualiza endereço existente
            const index = addresses.value.findIndex(a => a.id === form.value.id)
            if (index !== -1) {
                // Verifica se o endereço editado era o selecionado
                const wasSelected = localStorage.getItem('selectedAddressId') === form.value.id.toString()
                addresses.value[index] = { ...form.value }
                
                // Se era o selecionado, atualiza a seleção
                if (wasSelected) {
                    emit('address-selected', form.value)
                }
            }
        } else {
            // novo endereço: adiciona
            isNewAddress = true
            form.value.id = Date.now()
            addresses.value.push({ ...form.value })
        }

        // persistir no LocalStorage
        localStorage.setItem('addresses', JSON.stringify(addresses.value))
        
        // Se o endereço salvo for o principal, emite evento
        if (form.value.primary) {
            emit('address-selected', form.value)
        } else if (isNewAddress && addresses.value.length === 1) {
            // Se é o primeiro endereço, seleciona automaticamente
            emit('address-selected', form.value)
        }
        
        triggerAddressUpdate() // Notifica mudança
        
        // volta para lista
        showList()
        
        toast.success(isNewAddress ? 'Endereço cadastrado com sucesso!' : 'Endereço atualizado com sucesso!', {
            timeout: 3000
        })
    }

    // Após salvar o endereço, dispare este evento:
     window.dispatchEvent(new CustomEvent('addresses-updated'))

    /* ---- FECHAR MODAL ---- */
    const close = () => {
        emit('update:modelValue', false)
        loadAddresses() // Recarrega endereços ao fechar
        triggerAddressUpdate() // Notifica mudança
    }
    
    const handleNoAddresses = () => {
      // Se não há endereços e o modal abriu, já mostra o formulário
      if (addresses.value.length === 0 && props.modelValue) {
        showForm()
      }
    }


    // Observa quando o modal abre para recarregar os endereços
    watch(() => props.modelValue, (newValue) => {
      if (newValue) {
        loadAddresses()
        // Se não tem endereços, já abre o formulário
        if (addresses.value.length === 0) {
          showForm()
        }
      }
    })
</script>


<style scoped>
    .badge.text-black{
        font-weight: 600;
        font-size: 0.75rem;
    }
    .bg-primary{
      background: var(--primary)!important;
    }
     .bg-secondary, .progress-bar{
        background: var(--bg-yellow) !important;
    }
    .modal-title{
        font-size: clamp(1rem, 1.125vw, 1.125rem);
    }
    h6, .form-label, .form-check-label{
        font-size: clamp(0.938rem, 1vw, 1rem);
    }
    .modal-header{
        background: var(--bg-ouro);
    }
    .address-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .address-card:hover {
        transform: translateY(-2px);
    }
    .address-card.selected {
        border-color: var(--primary);
        background-color: rgba(13,110,253,.05);
    }
    @media (max-width: 476px) {
      .address-title, .btn.btn-outline-primary.btn-sm{
        font-size: 0.844rem;  
      }
    }
</style>