import { defineStore } from 'pinia'
import axios from 'axios'

export const useUserStore = defineStore('user', {
  state: () => ({
    id: null,
    fullName: '',
    whatsapp: '',
    email: '',
    pathImage: null,
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
    async loginExistingUser(whatsapp, email = null) {
      try {
        console.log('🔍 Buscando cliente existente pelo WhatsApp:', whatsapp)
        console.log('📧 Email fornecido:', email)
        
        // 1. Primeiro, busca os dados do cliente (rota pública)
        const response = await axios.post('/client/find-by-whatsapp', { whatsapp })
        
        console.log('📦 Resposta do find-by-whatsapp:', response.data)
        
        if (response.data.success && response.data.exists && response.data.client) {
          const client = response.data.client
          
          console.log('✅ Cliente encontrado:', client)
          
          // 🔥 USAR O EMAIL FORNECIDO OU O DO BANCO
          const emailToUse = email || client.email
          
          console.log('📧 Email que será usado na autenticação:', emailToUse)
          
          // 2. Autenticar no backend com email também
          const authResponse = await axios.post('/identify/validate-user', {
            whatsapp: whatsapp,
            fullName: client.name,
            email: emailToUse  // 🔥 ESSE CAMPO É OBRIGATÓRIO AGORA
          })
          
          console.log('📦 Resposta do validate-user:', authResponse.data)
          
          if (!authResponse.data.success) {
            console.error('❌ Falha na autenticação')
            return false
          }
          
          // 3. Atualizar store com os dados
          this.id = client.id
          this.fullName = client.name
          this.whatsapp = client.phone
          this.email = emailToUse  // 🔥 SALVAR O EMAIL
          this.pathImage = client.path_image || null
          this.isLogged = true
          
          // 4. Carregar métodos salvos
          if (client.delivery_method) {
            this.deliveryMethod = client.delivery_method
            localStorage.setItem('selectedDeliveryMethod', JSON.stringify(client.delivery_method))
            console.log('📦 Método de entrega carregado:', this.deliveryMethod)
          }
          
          if (client.payment_method) {
            this.paymentMethod = client.payment_method
            localStorage.setItem('selectedPaymentMethod', client.payment_method)
            console.log('💰 Método de pagamento carregado:', this.paymentMethod)
          }
          
          this.saveToStorage()
          
          // 5. Carregar endereços
          await this.loadAddresses()
          
          // 6. Disparar eventos
          this.dispatchEvents()
          
          console.log('✅ Login existente finalizado! Estado final:', {
            deliveryMethod: this.deliveryMethod,
            paymentMethod: this.paymentMethod,
            selectedAddress: this.selectedAddress,
            email: this.email,
            hasAvatar: !!this.pathImage
          })
          
          return true
        }
        
        console.log('❌ Cliente não encontrado')
        return false
      } catch (error) {
        console.error('❌ Erro ao logar cliente existente:', error)
        console.error('Detalhes:', error.response?.data)
        
        // Mostrar mensagem de erro mais clara
        const errorMessage = error.response?.data?.errors || error.response?.data?.message
        if (errorMessage) {
          console.error('Erro detalhado da validação:', errorMessage)
        }
        
        return false
      }
    },
    
    syncWithProfile(profileData) {
      if (profileData) {
        this.fullName = profileData.nome || this.fullName
        this.whatsapp = profileData.telefone || this.whatsapp
        this.email = profileData.email || this.email
        this.pathImage = profileData.avatar || this.pathImage // 👈 ADICIONADO: sincronizar avatar
        this.saveToStorage()
        this.dispatchEvents()
      }
    },
    
    /**
     * 🔥 ATUALIZAR AVATAR (NOVO MÉTODO)
     */
    updateAvatar(avatarUrl) {
      console.log('🖼️ Atualizando avatar no store:', avatarUrl)
      this.pathImage = avatarUrl
      this.saveToStorage()
      
      // Disparar evento específico para o avatar
      window.dispatchEvent(new CustomEvent('user-data-updated', { 
        detail: { 
          avatar: avatarUrl,
          timestamp: Date.now()
        }
      }))
      
      // Forçar atualização do cart
      window.dispatchEvent(new CustomEvent('force-cart-update', { 
        detail: { source: 'avatar-update', timestamp: Date.now() }
      }))
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
        fullName: this.fullName,
        avatar: this.pathImage // 👈 ADICIONADO: incluir avatar nos eventos
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
      this.pathImage = userData.pathImage || null
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
     * Logout - Limpa completamente todos os dados
     */
    async logout() {
      try {
        await axios.get('/logout')
      } catch (error) {
        console.error('Erro no logout:', error)
      }
      
      // Limpar estado do store
      this.id = null
      this.fullName = ''
      this.whatsapp = ''
      this.email = ''
      this.pathImage = null
      this.isLogged = false
      this.selectedAddress = null
      this.deliveryMethod = null
      this.paymentMethod = null
      
      // 🔥 LIMPAR TODAS AS CHAVES DO LOCALSTORAGE
      const keysToRemove = [
        'userData',           // Dados do usuário
        'selectedAddressId',  // ID do endereço selecionado
        'selectedAddress',    // Endereço selecionado
        'selectedDeliveryMethod', // Método de entrega
        'selectedPaymentMethod',  // Método de pagamento
        'addresses',          // Lista de endereços
        'addressesUpdated',   // Flag de atualização
        'user'                // Dados do persist (Pinia)
      ]
      
      keysToRemove.forEach(key => {
        localStorage.removeItem(key)
        console.log(`🗑️ Removido do localStorage: ${key}`)
      })
      
      // 🔥 LIMBAR TAMBÉM O SESSION STORAGE SE HOUVER
      sessionStorage.clear()
      
      // Disparar eventos para atualizar o Cart
      this.dispatchEvents()
      
      // 🔥 DISPARAR EVENTO ESPECÍFICO DE LOGOUT
      window.dispatchEvent(new CustomEvent('user-logout', { 
        detail: { isLogged: false, timestamp: Date.now() } 
      }))
      
      // 🔥 FORÇAR ATUALIZAÇÃO DO CART
      window.dispatchEvent(new CustomEvent('force-cart-update', { 
        detail: { source: 'logout', timestamp: Date.now() } 
      }))
      
      console.log('👋 Usuário deslogado - localStorage limpo')
    },
    
    saveToStorage() {
      const userData = {
        id: this.id,
        fullName: this.fullName,
        whatsapp: this.whatsapp,
        email: this.email,
        pathImage: this.pathImage, // 👈 GARANTIR QUE O AVATAR É SALVO
        selectedAddress: this.selectedAddress,
        deliveryMethod: this.deliveryMethod,
        paymentMethod: this.paymentMethod,
        isLogged: this.isLogged
      }
      
      localStorage.setItem('userData', JSON.stringify(userData))
      console.log('💾 Dados salvos no storage:', {
        hasAvatar: !!this.pathImage,
        avatarUrl: this.pathImage
      })
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
          this.pathImage = data.pathImage || null // 👈 CARREGAR AVATAR
          this.selectedAddress = data.selectedAddress || null
          this.deliveryMethod = data.deliveryMethod || null
          this.paymentMethod = data.paymentMethod || null
          this.isLogged = true
          
          console.log('📦 Usuário carregado do storage', {
            hasAvatar: !!this.pathImage,
            avatarUrl: this.pathImage
          })
          
          // Disparar evento com o avatar carregado
          if (this.pathImage) {
            window.dispatchEvent(new CustomEvent('user-data-loaded', { 
              detail: { 
                avatar: this.pathImage,
                fullName: this.fullName,
                whatsapp: this.whatsapp,
                email: this.email
              }
            }))
          }
        } catch (error) {
          console.error('Erro ao carregar:', error)
        }
      } else {
        console.log('📦 Nenhum dado encontrado no storage')
      }
    }
  }
})