// stores/useUserStore.js
import { defineStore } from 'pinia'
import axios from 'axios'

export const useUserStore = defineStore('user', {
  state: () => ({
    id: null,                    // ID único do usuário
    fullName: '',                // Nome completo
    whatsapp: '',                // WhatsApp para contato
    email: '',                   // Email (opcional, para futuro)
    isLogged: false,             // Status de login
    selectedAddress: null,       // Endereço selecionado para entrega
    deliveryMethod: null,        // Método de entrega selecionado
    paymentMethod: null          // Método de pagamento selecionado
  }),

  getters: {
    // Getter para o ID (consistência)
    userId: (state) => state.id,
    
    // Informações completas do usuário
    userInfo: (state) => ({
      id: state.id,
      fullName: state.fullName,
      whatsapp: state.whatsapp,
      email: state.email,
      selectedAddress: state.selectedAddress,
      deliveryMethod: state.deliveryMethod,
      paymentMethod: state.paymentMethod
    }),
    
    // Nome de exibição (primeiro nome)
    displayName: (state) => {
      if (!state.fullName) return 'Usuário'
      return state.fullName.split(' ')[0]
    },
    
    // Verifica se tem endereço para entrega
    hasDeliveryAddress: (state) => {
      return state.selectedAddress !== null && state.selectedAddress !== undefined
    },
    
    // Verifica se tem método de entrega
    hasDeliveryMethod: (state) => {
      return state.deliveryMethod !== null && state.deliveryMethod !== undefined
    },
    
    // Verifica se tem método de pagamento
    hasPaymentMethod: (state) => {
      return state.paymentMethod !== null && state.paymentMethod !== undefined && state.paymentMethod !== ''
    },
    
    // Dados completos para finalizar pedido
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
    // Login do usuário
    login(userData) {
      console.log('🟡 login chamado com:', userData)
      
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
      
      console.log('✅ Usuário logado:', {
        id: this.id,
        fullName: this.fullName,
        whatsapp: this.whatsapp,
        selectedAddress: this.selectedAddress,
        deliveryMethod: this.deliveryMethod,
        paymentMethod: this.paymentMethod
      })
      
      // Busca dados completos do backend
      this.fetchUserFromBackend()
    },

    // Buscar dados completos do usuário no backend
    async fetchUserFromBackend() {
      if (!this.id) return null
      
      try {
        console.log('🔄 Buscando dados do usuário no backend...')
        
        // Busca dados do cliente
        const response = await axios.get('/client/data')
        if (response.data.success) {
          this.fullName = response.data.client.name
          this.whatsapp = response.data.client.phone
          this.email = response.data.client.email || ''
          this.id = response.data.client.id
          this.isLogged = true
          
          console.log('✅ Dados do cliente carregados:', response.data.client)
        }
        
        // Busca endereços do backend
        const addressesResponse = await axios.get('/client/addresses')
        if (addressesResponse.data.success && addressesResponse.data.addresses.length > 0) {
          const primaryAddress = addressesResponse.data.addresses.find(a => a.primary === true) || addressesResponse.data.addresses[0]
          this.selectedAddress = primaryAddress
          localStorage.setItem('selectedAddressId', primaryAddress.id.toString())
          localStorage.setItem('selectedAddress', JSON.stringify(primaryAddress))
          console.log('✅ Endereço carregado do backend:', primaryAddress)
        }
        
        this.saveToStorage()
        
        // Dispara evento para atualizar o Cart
        window.dispatchEvent(new CustomEvent('user-data-updated', { 
          detail: { 
            selectedAddress: this.selectedAddress,
            deliveryMethod: this.deliveryMethod,
            paymentMethod: this.paymentMethod
          } 
        }))
        
        return response.data.client
      } catch (error) {
        console.error('Erro ao buscar usuário do backend:', error)
        return null
      }
    },

    // Atualizar dados do usuário
    updateUser(userData) {
      if (userData.id) this.id = userData.id
      if (userData.fullName) this.fullName = userData.fullName
      if (userData.whatsapp) this.whatsapp = userData.whatsapp
      if (userData.email) this.email = userData.email
      if (userData.selectedAddress !== undefined) this.setSelectedAddress(userData.selectedAddress)
      if (userData.deliveryMethod !== undefined) this.setDeliveryMethod(userData.deliveryMethod)
      if (userData.paymentMethod !== undefined) this.setPaymentMethod(userData.paymentMethod)
      
      this.saveToStorage()
      console.log('📝 Dados do usuário atualizados:', userData)
    },

    // Setar endereço selecionado
    setSelectedAddress(address) {
      console.log('🟡 setSelectedAddress chamado:', address)
      this.selectedAddress = address
      if (address) {
        localStorage.setItem('selectedAddressId', address.id.toString())
        localStorage.setItem('selectedAddress', JSON.stringify(address))
      } else {
        localStorage.removeItem('selectedAddressId')
        localStorage.removeItem('selectedAddress')
      }
      this.saveToStorage()
      
      // Dispara evento para atualizar o Cart
      window.dispatchEvent(new CustomEvent('user-data-updated', { 
        detail: { selectedAddress: address } 
      }))
    },

    // Setar método de entrega
    setDeliveryMethod(method) {
      console.log('🟡 setDeliveryMethod chamado:', method)
      this.deliveryMethod = method
      if (method) {
        localStorage.setItem('selectedDeliveryMethod', JSON.stringify(method))
      } else {
        localStorage.removeItem('selectedDeliveryMethod')
      }
      this.saveToStorage()
      
      // Dispara evento para atualizar o Cart
      window.dispatchEvent(new CustomEvent('user-data-updated', { 
        detail: { deliveryMethod: method } 
      }))
    },

    // Setar método de pagamento
    setPaymentMethod(method) {
      console.log('🟡 setPaymentMethod chamado:', method)
      this.paymentMethod = method
      if (method) {
        localStorage.setItem('selectedPaymentMethod', method)
      } else {
        localStorage.removeItem('selectedPaymentMethod')
      }
      this.saveToStorage()
      
      // Dispara evento para atualizar o Cart
      window.dispatchEvent(new CustomEvent('user-data-updated', { 
        detail: { paymentMethod: method } 
      }))
    },

    // Logout
    logout() {
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
      
      console.log('👋 Usuário deslogado')
      
      // Dispara evento para atualizar o Cart
      window.dispatchEvent(new CustomEvent('user-data-updated', { detail: { isLogged: false } }))
    },

    // Salvar no localStorage
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

    // Carregar do localStorage ao iniciar
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
          
          console.log('📦 Usuário carregado do storage:', {
            id: this.id,
            fullName: this.fullName,
            selectedAddress: this.selectedAddress
          })
          
          // Busca dados atualizados do backend
          this.fetchUserFromBackend()
        } catch (error) {
          console.error('Erro ao carregar usuário:', error)
          this.logout()
        }
      }
    }
  },

  persist: {
    key: 'user',
    storage: localStorage,
    paths: ['id', 'fullName', 'whatsapp', 'email', 'isLogged', 'selectedAddress', 'deliveryMethod', 'paymentMethod']
  }
})