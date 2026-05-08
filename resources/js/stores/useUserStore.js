import { defineStore } from 'pinia'
import axios from 'axios'

export const useUserStore = defineStore('user', {
  state: () => ({
    id: null,
    fullName: '',
    whatsapp: '',
    email: '',
    isLogged: false,
    selectedAddress: null,
    deliveryMethod: null,
    paymentMethod: null
  }),

  getters: {
    userId: (state) => state.id,
    userInfo: (state) => ({ ...state }),
    displayName: (state) => state.fullName?.split(' ')[0] || 'Usuário',
    hasDeliveryAddress: (state) => !!state.selectedAddress,
    hasDeliveryMethod: (state) => !!state.deliveryMethod,
    hasPaymentMethod: (state) => !!state.paymentMethod,
    checkoutData: (state) => ({
      user: {
        id: state.id,
        fullName: state.fullName,
        whatsapp: state.whatsapp,
        email: state.email
      },
      deliveryMethod: state.deliveryMethod,
      paymentMethod: state.paymentMethod,
      selectedAddress: state.selectedAddress
    })
  },

  actions: {
    /**
     * 🔥 LOGIN DE CLIENTE EXISTENTE (FLUXO COMPLETO)
     */
    async loginExistingUser(whatsapp) {
      try {
        console.log('🔍 1. Buscando cliente pelo WhatsApp:', whatsapp)
        
        // Passo 1: Buscar dados do cliente (rota pública)
        const findResponse = await axios.post('/client/find-by-whatsapp', { whatsapp })
        
        if (!findResponse.data.success || !findResponse.data.exists) {
          console.log('❌ Cliente não encontrado')
          return false
        }
        
        const clientData = findResponse.data.client
        console.log('✅ Cliente encontrado:', clientData)
        
        // Passo 2: Autenticar no backend (criar sessão)
        console.log('🔐 2. Autenticando no backend...')
        
        const authResponse = await axios.post('/identify/validate-user', {
          whatsapp: whatsapp,
          fullName: clientData.name
        })
        
        if (!authResponse.data.success) {
          console.error('❌ Falha na autenticação:', authResponse.data)
          return false
        }
        
        console.log('✅ Autenticado com sucesso!')
        
        // Passo 3: Atualizar o store com os dados básicos
        this.id = clientData.id
        this.fullName = clientData.name
        this.whatsapp = clientData.phone
        this.email = clientData.email || ''
        this.isLogged = true
        
        // Passo 4: Carregar métodos salvos
        if (clientData.delivery_method) {
          this.deliveryMethod = clientData.delivery_method
          localStorage.setItem('selectedDeliveryMethod', JSON.stringify(clientData.delivery_method))
          console.log('📦 Método de entrega:', this.deliveryMethod)
        }
        
        if (clientData.payment_method) {
          this.paymentMethod = clientData.payment_method
          localStorage.setItem('selectedPaymentMethod', clientData.payment_method)
          console.log('💰 Método de pagamento:', this.paymentMethod)
        }
        
        this.saveToStorage()
        
        // Passo 5: Carregar endereços (agora com sessão autenticada)
        await this.loadAddresses()
        
        // Passo 6: Disparar eventos
        this.dispatchEvents()
        
        console.log('✅ Login existente finalizado! Estado final:', {
          deliveryMethod: this.deliveryMethod,
          paymentMethod: this.paymentMethod,
          selectedAddress: this.selectedAddress
        })
        
        return true
        
      } catch (error) {
        console.error('❌ Erro no loginExistingUser:', error)
        console.error('Detalhes:', error.response?.data)
        return false
      }
    },
    
    /**
     * 🔥 CARREGAR ENDEREÇOS DO USUÁRIO (após autenticação)
     */
    async loadAddresses() {
      if (!this.id) {
        console.log('⚠️ Sem ID do usuário')
        return
      }
      
      try {
        console.log('🏠 Carregando endereços...')
        const response = await axios.get('/client/addresses')
        
        if (response.data.success && response.data.addresses?.length > 0) {
          const addresses = response.data.addresses
          console.log(`📦 ${addresses.length} endereço(s) encontrado(s)`)
          
          // Tentar carregar o endereço selecionado
          const savedAddressId = localStorage.getItem('selectedAddressId')
          
          if (savedAddressId) {
            const selected = addresses.find(a => a.id == savedAddressId)
            if (selected) {
              this.selectedAddress = selected
              console.log('🏠 Endereço selecionado:', selected.street)
            }
          }
          
          // Se não tem selecionado, pegar o principal
          if (!this.selectedAddress) {
            const primary = addresses.find(a => a.primary === true) || addresses[0]
            if (primary) {
              this.selectedAddress = primary
              localStorage.setItem('selectedAddressId', primary.id.toString())
              localStorage.setItem('selectedAddress', JSON.stringify(primary))
              console.log('🏠 Endereço principal:', primary.street)
            }
          }
          
          this.saveToStorage()
        } else {
          console.log('⚠️ Nenhum endereço encontrado')
        }
      } catch (error) {
        console.error('❌ Erro ao carregar endereços:', error)
      }
    },
    
    /**
     * Disparar eventos para sincronizar o Cart
     */
    dispatchEvents() {
      const eventData = {
        deliveryMethod: this.deliveryMethod,
        paymentMethod: this.paymentMethod,
        selectedAddress: this.selectedAddress,
        isLogged: true,
        fullName: this.fullName
      }
      
      window.dispatchEvent(new CustomEvent('user-data-updated', { detail: eventData }))
      window.dispatchEvent(new CustomEvent('user-login', { detail: eventData }))
      window.dispatchEvent(new CustomEvent('force-cart-update', { detail: { timestamp: Date.now() } }))
      
      console.log('📡 Eventos disparados:', eventData)
    },
    
    /**
     * Login de NOVO cliente (cadastro)
     */
    login(userData) {
      console.log('🆕 Login de novo cliente:', userData)
      
      this.id = userData.id || Date.now()
      this.fullName = userData.fullName || ''
      this.whatsapp = userData.whatsapp || ''
      this.email = userData.email || ''
      this.isLogged = true
      
      if (userData.selectedAddress) {
        this.selectedAddress = userData.selectedAddress
        localStorage.setItem('selectedAddressId', userData.selectedAddress.id.toString())
        localStorage.setItem('selectedAddress', JSON.stringify(userData.selectedAddress))
      }
      
      if (userData.deliveryMethod) {
        this.deliveryMethod = userData.deliveryMethod
        localStorage.setItem('selectedDeliveryMethod', JSON.stringify(userData.deliveryMethod))
      }
      
      if (userData.paymentMethod) {
        this.paymentMethod = userData.paymentMethod
        localStorage.setItem('selectedPaymentMethod', userData.paymentMethod)
      }
      
      this.saveToStorage()
      this.dispatchEvents()
      
      console.log('✅ Novo cliente logado')
    },
    
    /**
     * Setar método de entrega
     */
    async setDeliveryMethod(method) {
      console.log('📦 setDeliveryMethod:', method)
      this.deliveryMethod = method
      
      if (method) {
        localStorage.setItem('selectedDeliveryMethod', JSON.stringify(method))
        
        if (this.id) {
          try {
            await axios.put('/client/delivery-method', method)
            console.log('✅ Método de entrega salvo no backend')
          } catch (error) {
            console.error('Erro ao salvar:', error)
          }
        }
      } else {
        localStorage.removeItem('selectedDeliveryMethod')
      }
      
      this.saveToStorage()
      this.dispatchEvents()
    },
    
    /**
     * Setar método de pagamento
     */
    async setPaymentMethod(method) {
      console.log('💰 setPaymentMethod:', method)
      this.paymentMethod = method
      
      if (method) {
        localStorage.setItem('selectedPaymentMethod', method)
        
        if (this.id) {
          try {
            await axios.put('/client/payment-method', { method })
            console.log('✅ Método de pagamento salvo no backend')
          } catch (error) {
            console.error('Erro ao salvar:', error)
          }
        }
      } else {
        localStorage.removeItem('selectedPaymentMethod')
      }
      
      this.saveToStorage()
      this.dispatchEvents()
    },
    
    /**
     * Setar endereço selecionado
     */
    async setSelectedAddress(address) {
      console.log('🏠 setSelectedAddress:', address)
      this.selectedAddress = address
      
      if (address) {
        localStorage.setItem('selectedAddressId', address.id.toString())
        localStorage.setItem('selectedAddress', JSON.stringify(address))
        
        if (this.id) {
          try {
            await axios.put(`/client/addresses/${address.id}/primary`)
            await axios.put('/client/selected-address', { address_id: address.id })
            console.log('✅ Endereço salvo no backend')
          } catch (error) {
            console.error('Erro ao salvar endereço:', error)
          }
        }
      } else {
        localStorage.removeItem('selectedAddressId')
        localStorage.removeItem('selectedAddress')
      }
      
      this.saveToStorage()
      this.dispatchEvents()
    },
    
    /**
     * Logout
     */
    async logout() {
      try {
        await axios.get('/logout')
      } catch (error) {
        console.error('Erro no logout:', error)
      }
      
      this.id = null
      this.fullName = ''
      this.whatsapp = ''
      this.email = ''
      this.isLogged = false
      this.selectedAddress = null
      this.deliveryMethod = null
      this.paymentMethod = null
      
      localStorage.removeItem('userData')
      localStorage.removeItem('selectedAddressId')
      localStorage.removeItem('selectedAddress')
      localStorage.removeItem('selectedDeliveryMethod')
      localStorage.removeItem('selectedPaymentMethod')
      
      this.dispatchEvents()
      console.log('👋 Usuário deslogado')
    },
    
    saveToStorage() {
      localStorage.setItem('userData', JSON.stringify({
        id: this.id,
        fullName: this.fullName,
        whatsapp: this.whatsapp,
        email: this.email,
        selectedAddress: this.selectedAddress,
        deliveryMethod: this.deliveryMethod,
        paymentMethod: this.paymentMethod
      }))
    },
    
    loadUserFromStorage() {
      const saved = localStorage.getItem('userData')
      if (saved) {
        try {
          const data = JSON.parse(saved)
          this.id = data.id
          this.fullName = data.fullName || ''
          this.whatsapp = data.whatsapp || ''
          this.email = data.email || ''
          this.selectedAddress = data.selectedAddress || null
          this.deliveryMethod = data.deliveryMethod || null
          this.paymentMethod = data.paymentMethod || null
          this.isLogged = true
          
          console.log('📦 Usuário carregado do storage')
        } catch (error) {
          console.error('Erro ao carregar:', error)
        }
      }
    }
  }
})