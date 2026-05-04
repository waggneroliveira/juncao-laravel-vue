import { defineStore } from 'pinia'
import { useToast } from 'vue-toastification'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: []
  }),

  persist: {
    key: 'cart',
    storage: localStorage,
    paths: ['items']
  },

  getters: {
    totalItems: (state) =>
      state.items.reduce((total, item) => total + item.quantity, 0),

    subTotal: (state) =>
      state.items.reduce((total, item) => {
        let itemTotal = getItemBaseTotal(item)
        return total + itemTotal
      }, 0),

    discount: (state) =>
      state.items.reduce((total, item) => {
        if (item.oldPrice && item.oldPrice > getItemBasePrice(item)) {
          return total + (item.oldPrice - getItemBasePrice(item)) * item.quantity
        }
        return total
      }, 0),

    cashbackTotal: (state) =>
      state.items.reduce(
        (total, item) =>
          total + ((getItemBasePrice(item) * (item.cashback || 0)) / 100) * item.quantity,
        0
      ),

    total: (state) => {
      let total = 0
      
      state.items.forEach(item => {
        if (item.isCombo) {
          total += (item.finalPrice || item.price) * item.quantity
        } else {
          total += getItemBaseTotal(item)
        }
      })
      
      const discount = state.items.reduce((total, item) => {
        if (item.oldPrice && item.oldPrice > getItemBasePrice(item)) {
          return total + (item.oldPrice - getItemBasePrice(item)) * item.quantity
        }
        return total
      }, 0)
      
      return total - discount
    },
    
    getItemDetails: (state) => (itemId) => {
      const item = state.items.find(i => i.cartUniqueId === itemId || i.id === itemId)
      if (!item) return null
      
      if (item.isCombo) {
        return {
          ...item,
          itemTotal: (item.finalPrice || item.price) * item.quantity
        }
      }
      
      let basePrice = getItemBasePrice(item)
      let aditionalsTotal = getItemAditionalsTotal(item)
      
      return {
        ...item,
        basePrice,
        aditionalsTotal,
        aditionalsList: getItemAditionalsList(item),
        itemTotal: (basePrice + aditionalsTotal) * item.quantity
      }
    }
  },

  actions: {

    // ⭐ FUNÇÃO PARA GERAR ID ÚNICO
    generateUniqueId() {
      return `${Date.now()}_${Math.random().toString(36).substr(2, 9)}`
    },

    add(product) {
      const toast = useToast()

      // ⭐ FUNÇÃO PARA GERAR ASSINATURA DOS ADICIONAIS
      const getItemAditionalsSignature = (item) => {
        if (!item.aditionals || item.aditionals.length === 0) return {}
        
        const signature = {}
        item.aditionals.forEach(group => {
          if (group.items && group.items.length) {
            group.items.forEach(ad => {
              if (ad.quantity && ad.quantity > 0) {
                signature[ad.id] = ad.quantity
              }
            })
          }
        })
        return signature
      }

      // ⭐ FUNÇÃO CORRIGIDA PARA GERAR CHAVE ÚNICA
      const generateItemKey = (item) => {
        if (item.isCombo) {
          // Para combos: usa productId + seleções dos itens + addons
          return `${item.productId || item.id}_combo_${JSON.stringify(item.itemSelections || {})}_${JSON.stringify(item.selectedAddons || [])}`
        }
        
        // Para produtos normais: considera TODAS as personalizações
        const parts = [
          item.productId || item.id,
          'normal',
          item.selectedOption || 'no_option',
          item.selectedSize?.id || 'no_size',
          JSON.stringify((item.selectedFlavors || []).map(f => f.id).sort()),
          JSON.stringify(getItemAditionalsSignature(item))
        ]
        
        return parts.join('_')
      }

      const newItemKey = generateItemKey(product)
      
      console.log('🔑 Gerando chave para:', product.name)
      console.log('Chave gerada:', newItemKey)
      
      // Verifica se já existe item com a MESMA chave
      const existingIndex = this.items.findIndex(item => generateItemKey(item) === newItemKey)

      if (existingIndex !== -1) {
        // Item idêntico encontrado - apenas aumenta quantidade
        this.items[existingIndex].quantity++
        
        // ⭐ PRESERVAR FLAG DE REORDER SE EXISTIR
        if (product.isReorder && !this.items[existingIndex].isReorder) {
          this.items[existingIndex].isReorder = product.isReorder
          this.items[existingIndex].reorderDate = product.reorderDate
          this.items[existingIndex].originalOrderId = product.originalOrderId
        }
        
        toast.info(`Mais 1 unidade de "${product.name}" adicionada!`, { timeout: 3000 })
        return
      }

      // Se chegou aqui, é um item novo (personalizações diferentes)
      console.log('🆕 Novo item detectado, adicionando ao carrinho')
      
      // ⭐ GERA UM ID ÚNICO PARA O ITEM
      const uniqueId = this.generateUniqueId()

      // Para combos
      if (product.isCombo) {
        const comboItem = {
          id: uniqueId, // ⭐ USA ID ÚNICO
          originalProductId: product.productId || product.id,
          productId: product.productId || product.id,
          name: product.name,
          description: product.description,
          price: product.price,
          finalPrice: product.finalPrice || product.price,
          basePrice: product.basePrice || product.price,
          oldPrice: product.oldPrice,
          image: product.image,
          cashback: product.cashback || 0,
          quantity: 1,
          isCombo: true,
          comboItems: product.comboItems || [],
          comboAddons: product.comboAddons || [],
          selectedAddons: product.selectedAddons || [],
          itemSelections: product.itemSelections || {},
          savings: product.savings,
          customization: product.customization,
          isReorder: product.isReorder || false,
          reorderDate: product.reorderDate || null,
          originalOrderId: product.originalOrderId || null
        }
        
        console.log('📦 Item combo adicionado à store - ID:', comboItem.id)
        
        this.items.push(comboItem)
        toast.success(`"${product.name}" adicionado ao carrinho!`, { timeout: 3000 })
        return
      }

      // CRIA UMA CÓPIA PROFUNDA DOS ADICIONAIS COM AS QUANTIDADES
      let aditionalsCopy = []
      if (product.aditionals && product.aditionals.length > 0) {
        aditionalsCopy = JSON.parse(JSON.stringify(product.aditionals))
      }

      // ⭐ ADICIONA O NOVO ITEM (PRODUTO NORMAL) COM ID ÚNICO
      const normalItem = {
        id: uniqueId, // ⭐ USA ID ÚNICO
        originalProductId: product.productId || product.id,
        productId: product.productId || product.id,
        name: product.name,
        description: product.description,
        price: product.price,
        originalPrice: product.originalPrice || product.price,
        oldPrice: product.oldPrice,
        image: product.image,
        cashback: product.cashback || 0,
        quantity: 1,
        options: product.options || [],
        selectedOption: product.selectedOption || null,
        selectedSize: product.selectedSize || null,
        selectedFlavors: product.selectedFlavors || [],
        aditionals: aditionalsCopy,
        cuisineType: product.cuisineType,
        customization: product.customization,
        isCombo: false,
        isReorder: product.isReorder || false,
        reorderDate: product.reorderDate || null,
        originalOrderId: product.originalOrderId || null
      }

      console.log('📦 Item normal adicionado à store - ID:', normalItem.id)

      this.items.push(normalItem)
      toast.success(`"${product.name}" adicionado ao carrinho!`, { timeout: 3000 })
    },

    updateItem(product) {
      const toast = useToast()
      
      console.log('updateItem chamado com:', product)

      // ⭐ FUNÇÃO PARA GERAR ASSINATURA DOS ADICIONAIS
      const getItemAditionalsSignature = (item) => {
        if (!item.aditionals || item.aditionals.length === 0) return {}
        
        const signature = {}
        item.aditionals.forEach(group => {
          if (group.items && group.items.length) {
            group.items.forEach(ad => {
              if (ad.quantity && ad.quantity > 0) {
                signature[ad.id] = ad.quantity
              }
            })
          }
        })
        return signature
      }

      // ⭐ FUNÇÃO PARA GERAR CHAVE ÚNICA
      const generateItemKey = (item) => {
        if (item.isCombo) {
          return `${item.productId || item.id}_combo_${JSON.stringify(item.itemSelections || {})}_${JSON.stringify(item.selectedAddons || [])}`
        }
        
        const parts = [
          item.productId || item.id,
          'normal',
          item.selectedOption || 'no_option',
          item.selectedSize?.id || 'no_size',
          JSON.stringify((item.selectedFlavors || []).map(f => f.id).sort()),
          JSON.stringify(getItemAditionalsSignature(item))
        ]
        
        return parts.join('_')
      }

      // ⭐ ENCONTRA O ITEM PELO ID ÚNICO
      const originalIndex = this.items.findIndex(item => item.id === product.cartItemId)
      
      if (originalIndex === -1) {
        console.error('Item não encontrado para atualização')
        return
      }

      const originalItem = this.items[originalIndex]
      const originalQuantity = originalItem.quantity
      
      // Remove o original
      this.items.splice(originalIndex, 1)

      // ⭐ GERA UM NOVO ID ÚNICO PARA O ITEM ATUALIZADO
      const newUniqueId = this.generateUniqueId()

      // Para combos
      if (product.isCombo || originalItem.isCombo) {
        const newComboItem = {
          id: newUniqueId, // ⭐ NOVO ID ÚNICO
          originalProductId: product.productId || originalItem.productId,
          productId: product.productId || originalItem.productId,
          name: product.name || originalItem.name,
          description: product.description || originalItem.description,
          price: product.price || originalItem.price,
          finalPrice: product.finalPrice || originalItem.finalPrice || product.price,
          basePrice: product.basePrice || originalItem.basePrice || product.price,
          oldPrice: product.oldPrice || originalItem.oldPrice,
          image: product.image || originalItem.image,
          cashback: product.cashback || originalItem.cashback || 0,
          quantity: originalQuantity,
          isCombo: true,
          comboItems: product.comboItems || originalItem.comboItems || [],
          comboAddons: product.comboAddons || originalItem.comboAddons || [],
          selectedAddons: product.selectedAddons || originalItem.selectedAddons || [],
          itemSelections: product.itemSelections || originalItem.itemSelections || {},
          savings: product.savings || originalItem.savings,
          customization: product.customization || originalItem.customization,
          isReorder: originalItem.isReorder || product.isReorder || false,
          reorderDate: originalItem.reorderDate || product.reorderDate || null,
          originalOrderId: originalItem.originalOrderId || product.originalOrderId || null
        }
        
        // Verifica se já existe item igual (mesmas seleções)
        const existingIndex = this.items.findIndex(item => 
          item.isCombo && 
          generateItemKey(item) === generateItemKey(newComboItem)
        )
        
        if (existingIndex !== -1) {
          this.items[existingIndex].quantity += originalQuantity
          toast.success(`"${newComboItem.name}" atualizado e combinado com item existente!`, { timeout: 3000 })
        } else {
          this.items.push(newComboItem)
          toast.success(`"${newComboItem.name}" atualizado com sucesso!`, { timeout: 3000 })
        }
        return
      }

      // Para produtos normais
      let updatedAditionals = []
      if (product.aditionals && product.aditionals.length > 0) {
        updatedAditionals = JSON.parse(JSON.stringify(product.aditionals))
      }

      const newNormalItem = {
        id: newUniqueId, // ⭐ NOVO ID ÚNICO
        originalProductId: product.productId || originalItem.productId,
        productId: product.productId || originalItem.productId,
        name: product.name || originalItem.name,
        description: product.description || originalItem.description,
        price: product.price || originalItem.price,
        originalPrice: product.originalPrice || originalItem.originalPrice || product.price,
        oldPrice: product.oldPrice || originalItem.oldPrice,
        image: product.image || originalItem.image,
        cashback: product.cashback || originalItem.cashback || 0,
        quantity: originalQuantity,
        options: product.options || originalItem.options || [],
        selectedOption: product.selectedOption !== undefined ? product.selectedOption : originalItem.selectedOption,
        selectedSize: product.selectedSize || originalItem.selectedSize,
        selectedFlavors: product.selectedFlavors || originalItem.selectedFlavors || [],
        aditionals: updatedAditionals.length > 0 ? updatedAditionals : originalItem.aditionals || [],
        cuisineType: product.cuisineType || originalItem.cuisineType,
        customization: product.customization || originalItem.customization,
        isCombo: false,
        isReorder: originalItem.isReorder || product.isReorder || false,
        reorderDate: originalItem.reorderDate || product.reorderDate || null,
        originalOrderId: originalItem.originalOrderId || product.originalOrderId || null
      }

      // Verifica se já existe item igual
      const existingIndex = this.items.findIndex(item => 
        !item.isCombo && 
        generateItemKey(item) === generateItemKey(newNormalItem)
      )
      
      if (existingIndex !== -1) {
        this.items[existingIndex].quantity += originalQuantity
        toast.success(`"${newNormalItem.name}" atualizado e combinado com item existente!`, { timeout: 3000 })
      } else {
        this.items.push(newNormalItem)
        toast.success(`"${newNormalItem.name}" atualizado com sucesso!`, { timeout: 3000 })
      }
    },

    increase(itemId) {
      const toast = useToast()
      
      // ⭐ BUSCA PELO ID ÚNICO
      const item = this.items.find(i => i.id === itemId)
      
      if (item) {
        item.quantity++
        toast.info(`Quantidade de "${item.name}" agora é ${item.quantity}`, { timeout: 3000 })
      }
    },

    decrease(itemId) {
      const toast = useToast()
      
      // ⭐ BUSCA PELO ID ÚNICO
      const item = this.items.find(i => i.id === itemId)
      
      if (item) {
        if (item.quantity > 1) {
          item.quantity--
          toast.info(`Quantidade de "${item.name}" agora é ${item.quantity}`, { timeout: 3000 })
        } else {
          this.remove(itemId)
        }
      }
    },

    remove(itemId) {
      const toast = useToast()
      
      // ⭐ BUSCA PELO ID ÚNICO
      const item = this.items.find(i => i.id === itemId)
      
      if (item) {
        this.items = this.items.filter(i => i.id !== itemId)
        toast.error(`"${item.name}" removido do carrinho!`, { timeout: 3000 })
      }
    },

    clear() {
      const toast = useToast()
      this.items = []
      toast.info('Carrinho esvaziado!', { timeout: 3000 })
    },
    
    calculateItemTotal(item) {
      if (item.isCombo) {
        return (item.finalPrice || item.price) * item.quantity
      }
      
      let total = getItemBasePrice(item) * item.quantity
      total += getItemAditionalsTotal(item) * item.quantity
      return total
    }
  }
})

// ========== FUNÇÕES AUXILIARES ==========

// Obtém o preço base do item (considerando tamanho selecionado)
function getItemBasePrice(item) {
  if (item.selectedSize && item.selectedSize.price) {
    return item.selectedSize.price
  }
  return item.price || 0
}

// Calcula o total base do item (preço * quantidade)
function getItemBaseTotal(item) {
  const basePrice = getItemBasePrice(item)
  let total = basePrice * item.quantity
  
  total += getItemAditionalsTotal(item) * item.quantity
  
  return total
}

// Calcula o total dos adicionais para uma unidade do item
function getItemAditionalsTotal(item) {
  if (!item.aditionals || item.aditionals.length === 0) return 0
  
  let aditionalsTotal = 0
  item.aditionals.forEach(group => {
    if (group.items && group.items.length) {
      group.items.forEach(aditional => {
        if (aditional.quantity > 0 && aditional.price) {
          aditionalsTotal += aditional.price * aditional.quantity
        }
      })
    }
  })
  
  return aditionalsTotal
}

// Retorna a lista de adicionais para exibição
function getItemAditionalsList(item) {
  if (!item.aditionals || item.aditionals.length === 0) return []
  
  const list = []
  item.aditionals.forEach(group => {
    if (group.items && group.items.length) {
      group.items.forEach(aditional => {
        if (aditional.quantity > 0) {
          list.push({
            name: aditional.name,
            quantity: aditional.quantity,
            price: aditional.price || 0,
            total: (aditional.price || 0) * aditional.quantity
          })
        }
      })
    }
  })
  
  return list
}