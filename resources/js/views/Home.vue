<script setup>
    import { RouterView } from 'vue-router'
    import { ref, watch, computed, onMounted } from 'vue'
    import { useCartStore } from '@/stores/useCartStore'
    import { useToast } from 'vue-toastification'
    import { useUserStore } from '@/stores/useUserStore'
    
    // Componentes
    import Aside from '@/components/Aside.vue'
    import Header from '@/components/Header.vue'
    import TopBar from '@/components/TopBar.vue'
    import ProductCarousel from '@/components/ProductCarousel.vue'
    import ProductList from '@/components/ProductList.vue'
    import Announcement from '@/components/Announcement.vue'
    import Cart from '@/components/Cart.vue'
    import ProductModal from '@/components/ProductModal.vue'
    import MobileBottomMenu from '@/components/MobileBottomMenu.vue'
    import OrderHistoryModal from '@/components/OrderHistoryModal.vue'
    import IdentifyModal from '@/components/IdentifyModal.vue'
    import Footer from '@/components/Footer.vue'

    // Store e Toast
    const cart = useCartStore()
    const toast = useToast()
    const userStore = useUserStore()

    // Estados do modal
    const showProductModal = ref(false)
    const selectedProduct = ref(null)
    const showOrderHistoryModal = ref(false)
    const showLoginModal = ref(false)

    // Abrir modal do produto
    const openProductModal = (product) => {
      selectedProduct.value = product
      showProductModal.value = true
    }

    // Função para lidar com o re-pedido - VERSÃO COMPLETA CORRIGIDA
    const handleReorder = (order) => {
      console.log('🎯 Reordenando pedido completo:', order.id)
      
      if (!order || !order.items || order.items.length === 0) {
        toast.error('Erro ao reordenar: pedido inválido')
        return
      }
      
      // Processar cada item do pedido
      order.items.forEach((originalItem, index) => {
        console.log(`📦 Processando item ${index + 1}:`, originalItem.name, originalItem.isCombo ? '(COMBO)' : '(NORMAL)')
        
        if (originalItem.isCombo) {
          // 🔥 PARA COMBO: Adicionar com todas as configurações preservadas
          const comboItem = JSON.parse(JSON.stringify(originalItem))
          
          comboItem.productId = comboItem.productId || comboItem.id
          comboItem.finalPrice = comboItem.finalPrice || comboItem.price
          comboItem.basePrice = comboItem.basePrice || comboItem.price
          comboItem.hasComboSelection = true
          comboItem.isComboItem = true
          
          comboItem.isReorder = true
          comboItem.reorderDate = new Date().toISOString()
          comboItem.originalOrderId = order.id
          
          if (!comboItem.itemSelections && comboItem.comboDetails) {
            comboItem.itemSelections = rebuildSelectionsFromDetails(comboItem)
          }
          
          if (comboItem.selectedAddons && comboItem.selectedAddons.length) {
            comboItem.addonsTotalPrice = comboItem.selectedAddons.reduce(
              (sum, addon) => sum + (addon.price * (addon.quantity || 1)), 0
            )
          } else {
            comboItem.selectedAddons = []
            comboItem.addonsTotalPrice = 0
          }
          
          comboItem.uniqueId = `${comboItem.productId}_${Date.now()}_${index}_${Math.random().toString(36).substr(2, 6)}`
          
          delete comboItem.comboDetails
          delete comboItem.customization
          delete comboItem.createdAt
          delete comboItem.updatedAt
          
          console.log('✅ Adicionando combo com isReorder:', comboItem.isReorder, comboItem.name)
          
          for (let i = 0; i < (comboItem.quantity || 1); i++) {
            const comboCopy = JSON.parse(JSON.stringify(comboItem))
            cart.add(comboCopy)  // ← CORRIGIDO: cart ao invés de cartStore
          }
          
        } else {
          // 🍔 PARA ITEM NORMAL
          const simpleItem = {
            id: originalItem.id,
            productId: originalItem.productId || originalItem.id,
            name: originalItem.name,
            description: originalItem.description,
            price: originalItem.finalPrice || originalItem.price,
            finalPrice: originalItem.finalPrice || originalItem.price,
            oldPrice: originalItem.oldPrice,
            originalPrice: originalItem.originalPrice || originalItem.price,
            image: originalItem.image,
            quantity: originalItem.quantity || 1,
            isCombo: false,
            cashback: originalItem.cashback || 0,
            customization: originalItem.customization,
            selectedSize: originalItem.selectedSize,
            selectedFlavors: originalItem.selectedFlavors,
            aditionals: originalItem.aditionals ? JSON.parse(JSON.stringify(originalItem.aditionals)) : [],
            aditionalsState: originalItem.aditionalsState ? { ...originalItem.aditionalsState } : {},
            isReorder: true,
            reorderDate: new Date().toISOString(),
            originalOrderId: order.id
          }
          
          console.log('✅ Adicionando item normal com isReorder:', simpleItem.isReorder, simpleItem.name)
          
          for (let i = 0; i < simpleItem.quantity; i++) {
            const itemCopy = JSON.parse(JSON.stringify(simpleItem))
            cart.add(itemCopy)  // ← CORRIGIDO: cart ao invés de cartStore
          }
        }
      })
      
      const totalItens = order.items.reduce((sum, item) => sum + (item.quantity || 1), 0)
      toast.success(`${totalItens} item(ns) adicionado(s) ao carrinho!`, {
        timeout: 3000
      })
      
      showOrderHistoryModal.value = false
    }
    const handleIdentify = ({ whatsapp: wpp, fullName: name }) => {
      const userData = {
        id: Date.now(),
        fullName: name,
        whatsapp: wpp,
        email: ''
      }
      
      console.log('📝 Realizando login com dados:', userData)
      userStore.login(userData)
      showLoginModal.value = false
      
      toast.success(`Bem-vindo(a), ${name}! Login realizado com sucesso!`, {
        timeout: 4000
      })
    }
    const openOrderHistory = () => {
      console.log('🔍 Verificando login para abrir histórico:', {
        isLogged: userStore.isLogged,
        userId: userStore.userId,
      })
      
      // Verificar se está logado
      if (!userStore.isLogged) {
        toast.warning('Faça login para ver seus pedidos!', {
          timeout: 3000
        })
        showLoginModal.value = true
        return
      }
      
      // Verificar se tem ID
      if (!userStore.userId) {
        console.error('❌ Usuário logado mas sem ID:', userStore)
        toast.error('Erro ao carregar dados do usuário. Por favor, faça login novamente.', {
          timeout: 4000
        })
        userStore.logout()
        showLoginModal.value = true
        return
      }
      
      console.log('✅ Abrindo histórico para userId:', userStore.userId)
      showOrderHistoryModal.value = true
    }

    const handleMenuChange = (key) => {
      // Lógica para outros itens do menu (home, search, delivery, profile)
      console.log('Menu alterado:', key)
    }

    // Dados da aplicação - Estrutura unificada e extensível com suporte a COMBOS
    const products = ref([
      {
        id: 1,
        name: 'Hambúrguer Clássico',
        description: '4 smash burgers com 400g de fritas, creme de cheddar com bacon + Refrigerante',
        price: 109.90,
        oldPrice: 119.90,
        cashback: 5,
        image: ['../src/assets/images/prod.png'],
        category: 'hamburguers',
        tags: ['promoção', 'bacon', 'mais pedido'],
        productType: 'food',
        cuisineType: 'burger',
        isCombo: false,
        customization: {
          hasSize: false,
          hasFlavors: false,
          hasToppings: true,
          toppings: [
            { id: 1, name: 'Queijo Extra', price: 3.50, category: 'cheese', maxQuantity: 2 },
            { id: 2, name: 'Bacon Extra', price: 4.00, category: 'meat', maxQuantity: 2 },
            { id: 3, name: 'Cheddar', price: 3.00, category: 'cheese' },
            { id: 4, name: 'Molho Especial', price: 2.00, category: 'sauce' }
          ],
          hasSpiciness: true,
          spicinessLevels: [
            { id: 1, level: 0, name: 'Sem Pimenta' },
            { id: 2, level: 1, name: 'Leve' },
            { id: 3, level: 2, name: 'Médio' },
            { id: 4, level: 3, name: 'Picante' }
          ]
        },
        specifications: {
          preparationTime: 20,
          calories: 850,
          serves: 1,
          isVegetarian: false,
          isVegan: false,
          isGlutenFree: false,
          allergens: ['glúten', 'lactose', 'ovos']
        },
        stock: { available: true, quantity: 50, maxPerOrder: 5 },
        featured: true,
        order: 1
      },
      {
        id: 2,
        name: 'Pizza Portuguesa',
        description: 'Molho especial, presunto, ovos, cebola, azeitona e queijo mussarela',
        price: 45.90,
        oldPrice: 0,
        cashback: 8,
        image: ['../src/assets/images/prod.png'],
        category: 'pizzas',
        tags: ['tradicional', 'salgada', 'promoção'],
        productType: 'food',
        cuisineType: 'pizza',
        isCombo: false,
        customization: {
          hasSize: true,
          sizes: [
            { id: 1, name: 'P', price: 45.90, description: '4 fatias - 2 pessoas', oldPrice: 55.90 },
            { id: 2, name: 'M', price: 59.90, description: '6 fatias - 3 pessoas', oldPrice: 69.90 },
            { id: 3, name: 'G', price: 79.90, description: '8 fatias - 4 pessoas', oldPrice: 89.90 }
          ],
          hasFlavors: true,
          flavors: [
            { id: 1, name: 'Portuguesa', price: 0, description: 'Presunto, ovo, cebola, azeitona', isRecommended: true },
            { id: 2, name: 'Calabresa', price: 5.00, description: 'Calabresa, cebola, mussarela' },
            { id: 3, name: 'Frango com Catupiry', price: 8.00, description: 'Frango desfiado, catupiry, milho' },
            { id: 4, name: 'Margherita', price: 3.00, description: 'Molho, mussarela, manjericão' }
          ],
          maxFlavors: 2,
          hasToppings: true,
          toppings: [
            { id: 5, name: 'Queijo Extra', price: 4.00, category: 'cheese' },
            { id: 6, name: 'Orégano', price: 0, category: 'sauce' },
            { id: 7, name: 'Azeitona Extra', price: 2.00, category: 'vegetable' }
          ],
          hasSpiciness: false
        },
        specifications: {
          preparationTime: 25,
          serves: 4,
          isVegetarian: false,
          isVegan: false,
          isGlutenFree: false,
          allergens: ['glúten', 'lactose']
        },
        stock: { available: true, quantity: 30 },
        featured: true,
        order: 2
      },
      
      // EXEMPLO: COMBO DE YAKISOBA COM OPÇÕES
      {
        id: 6,
        name: 'Combo Yakisoba Completo',
        description: 'Yakisoba + Refrigerante + 4 Rolinhos',
        price: 59.90,
        oldPrice: 89.90,
        cashback: 10,
        image: ['../src/assets/images/prod.png'],
        category: 'combos',
        tags: ['promoção', 'combo', 'yakisoba'],
        productType: 'combo',
        cuisineType: 'japanese',
        isCombo: true,
        
        // Itens do combo
        comboItems: [
          {
            id: 'yakisoba',
            name: 'Yakisoba',
            quantity: 1,
            required: true,
            // Sem opções de escolha, fixo
            options: null
          },
          {
            id: 'refrigerante',
            name: 'Refrigerante',
            quantity: 1,
            required: true,
            // ✅ COM OPÇÃO DE ESCOLHA (select)
            options: {
              type: 'select',  // select, radio, checkbox
              title: 'Escolha o sabor do refrigerante',
              required: true,
              maxSelections: 1,
              choices: [
                { id: 1, name: 'Kuat', price: 0, default: true },
                { id: 2, name: 'Fanta Laranja', price: 0 },
                { id: 3, name: 'Fanta Uva', price: 0 },
                { id: 4, name: 'Pepsi', price: 0 },
                { id: 5, name: 'Guaraná', price: 0 }
              ]
            }
          },
          {
            id: 'rolinhos',
            name: 'Rolinhos Primavera',
            quantity: 4,
            required: true,
            // ✅ COM OPÇÃO DE ESCOLHA MÚLTIPLA (checkbox) com limite
            options: {
              type: 'checkbox',
              title: 'Escolha até 4 rolinhos (pode repetir sabores)',
              required: true,
              maxSelections: 4,
              choices: [
                { id: 1, name: 'Queijo Misto', price: 0, maxPerOption: 4 },
                { id: 2, name: 'Romeu e Julieta', price: 0, maxPerOption: 4 },
                { id: 3, name: 'Carne', price: 0, maxPerOption: 4 },
                { id: 4, name: 'Frango', price: 0, maxPerOption: 4 },
                { id: 5, name: 'Legumes', price: 0, maxPerOption: 4 }
              ]
            }
          }
        ],
        
        // Addons extras (opcionais pagos)
        comboAddons: [
          {
            id: 1,
            name: 'Hashi (par de palitinhos)',
            price: 1.00,
            maxQuantity: 2
          },
          {
            id: 2,
            name: 'Molho Especial',
            price: 2.00,
            maxQuantity: 3
          }
        ],
        
        savings: 30.00,
        savingsPercent: 33,
        stock: { available: true, quantity: 20, maxPerOrder: 2 },
        featured: true,
        order: 0
      },
      
      // EXEMPLO: COMBO DE PIZZA COM ESCOLHA DE BORDA E SABORES
      {
        id: 7,
        name: 'Combo Pizza Especial',
        description: 'Pizza Média + Refrigerante 1L + Sobremesa',
        price: 69.90,
        oldPrice: 99.90,
        cashback: 8,
        image: ['../src/assets/images/prod.png'],
        category: 'combos',
        tags: ['promoção', 'combo', 'pizza'],
        productType: 'combo',
        cuisineType: 'pizza',
        isCombo: true,
        
        comboItems: [
          {
            id: 'pizza',
            name: 'Pizza Média',
            quantity: 1,
            required: true,
            // ✅ Opção de escolha de sabores (máximo 2 sabores)
            options: {
              type: 'multicheckbox',
              title: 'Escolha os sabores da pizza (máx. 2)',
              required: true,
              maxSelections: 2,
              choices: [
                { id: 1, name: 'Portuguesa', price: 0, description: 'Presunto, ovo, cebola, azeitona' },
                { id: 2, name: 'Calabresa', price: 0, description: 'Calabresa, cebola, mussarela' },
                { id: 3, name: 'Frango com Catupiry', price: 5.00, description: 'Frango desfiado, catupiry' },
                { id: 4, name: 'Margherita', price: 0, description: 'Molho, mussarela, manjericão' },
                { id: 5, name: 'Quatro Queijos', price: 5.00, description: 'Mussarela, provolone, parmesão, catupiry' }
              ]
            }
          },
          {
            id: 'borda',
            name: 'Borda da Pizza',
            quantity: 1,
            required: false,  // Opcional
            // ✅ Opção de escolha de borda (select)
            options: {
              type: 'select',
              title: 'Escolha a borda (opcional)',
              required: false,
              maxSelections: 1,
              choices: [
                { id: 1, name: 'Sem borda', price: 0, default: true },
                { id: 2, name: 'Borda de Catupiry', price: 5.00 },
                { id: 3, name: 'Borda de Cheddar', price: 5.00 },
                { id: 4, name: 'Borda de Chocolate', price: 6.00 }
              ]
            }
          },
          {
            id: 'refrigerante',
            name: 'Refrigerante 1L',
            quantity: 1,
            required: true,
            options: {
              type: 'radio',
              title: 'Escolha o refrigerante',
              required: true,
              maxSelections: 1,
              choices: [
                { id: 1, name: 'Coca-Cola', price: 0 },
                { id: 2, name: 'Coca-Cola Zero', price: 0 },
                { id: 3, name: 'Guaraná', price: 0 },
                { id: 4, name: 'Pepsi', price: 0 }
              ]
            }
          },
          {
            id: 'sobremesa',
            name: 'Sobremesa',
            quantity: 1,
            required: true,
            options: {
              type: 'radio',
              title: 'Escolha sua sobremesa',
              required: true,
              maxSelections: 1,
              choices: [
                { id: 1, name: 'Pudim', price: 0 },
                { id: 2, name: 'Torta de Limão', price: 0 },
                { id: 3, name: 'Brownie', price: 3.00 },
                { id: 4, name: 'Sorvete', price: 2.00 }
              ]
            }
          }
        ],
        
        comboAddons: [
          {
            id: 1,
            name: 'Molho Especial',
            price: 2.00,
            maxQuantity: 3
          }
        ],
        
        savings: 30.00,
        savingsPercent: 30,
        stock: { available: true, quantity: 15, maxPerOrder: 3 },
        featured: true,
        order: 0
      },
      
      // EXEMPLO: COMBO DE HAMBÚRGUER COM ESCOLHA DE ACOMPANHAMENTO E BEBIDA
      {
        id: 8,
        name: 'Combo Burger',
        description: 'Hambúrguer + Acompanhamento + Bebida',
        price: 39.90,
        oldPrice: 59.90,
        cashback: 5,
        image: ['../src/assets/images/prod.png'],
        category: 'combos',
        tags: ['promoção', 'combo', 'hamburguer'],
        productType: 'combo',
        cuisineType: 'burger',
        isCombo: true,
        
        comboItems: [
          {
            id: 'hamburguer',
            name: 'Hambúrguer Artesanal',
            quantity: 1,
            required: true,
            // Sem opções, fixo
            options: null
          },
          {
            id: 'acompanhamento',
            name: 'Acompanhamento',
            quantity: 1,
            required: true,
            options: {
              type: 'select',
              title: 'Escolha o acompanhamento',
              required: true,
              maxSelections: 1,
              choices: [
                { id: 1, name: 'Batata Frita', price: 0, default: true },
                { id: 2, name: 'Batata Rústica', price: 0 },
                { id: 3, name: 'Onion Rings', price: 3.00 },
                { id: 4, name: 'Salada', price: 0 }
              ]
            }
          },
          {
            id: 'bebida',
            name: 'Bebida',
            quantity: 1,
            required: true,
            options: {
              type: 'select',
              title: 'Escolha a bebida',
              required: true,
              maxSelections: 1,
              choices: [
                { id: 1, name: 'Coca-Cola 350ml', price: 0 },
                { id: 2, name: 'Guaraná 350ml', price: 0 },
                { id: 3, name: 'Suco Natural', price: 3.00 },
                { id: 4, name: 'Água', price: 0 }
              ]
            }
          }
        ],
        
        comboAddons: [
          {
            id: 1,
            name: 'Queijo Extra',
            price: 3.00,
            maxQuantity: 2
          },
          {
            id: 2,
            name: 'Bacon Extra',
            price: 4.00,
            maxQuantity: 2
          }
        ],
        
        savings: 20.00,
        savingsPercent: 33,
        stock: { available: true, quantity: 30, maxPerOrder: 5 },
        featured: true,
        order: 0
      },
      
      {
        id: 3,
        name: 'Açaí Tradicional',
        description: 'Açaí puro da Amazônia, sem xarope, acompanha granola',
        price: 19.90,
        cashback: 3,
        image: ['../src/assets/images/prod.png'],
        category: 'acai',
        tags: ['natural', 'saudável', 'vegano'],
        productType: 'dessert',
        cuisineType: 'acai',
        isCombo: false,
        customization: {
          hasSize: true,
          sizes: [
            { id: 1, name: '300ml', price: 19.90 },
            { id: 2, name: '500ml', price: 27.90 },
            { id: 3, name: '700ml', price: 34.90 }
          ],
          hasFlavors: false,
          hasToppings: true,
          toppings: [
            { id: 8, name: 'Granola', price: 2.00, category: 'sweet' },
            { id: 9, name: 'Banana', price: 1.50, category: 'sweet' },
            { id: 10, name: 'Leite Condensado', price: 2.50, category: 'sweet' },
            { id: 11, name: 'Morango', price: 2.00, category: 'sweet' },
            { id: 12, name: 'Paçoca', price: 2.00, category: 'sweet' }
          ],
          hasSpiciness: false
        },
        specifications: {
          calories: 320,
          isVegetarian: true,
          isVegan: true,
          isGlutenFree: true,
          allergens: []
        },
        stock: { available: true, quantity: 100 },
        featured: true,
        order: 3
      },
      {
        id: 4,
        name: 'Coca-Cola 2L',
        description: 'Refrigerante gelado',
        price: 12.90,
        image: ['../src/assets/images/prod.png'],
        category: 'bebidas',
        tags: ['refrigerante', 'gelada'],
        productType: 'beverage',
        cuisineType: 'drink',
        isCombo: false,
        customization: {
          hasSize: true,
          sizes: [
            { id: 1, name: 'Lata 350ml', price: 5.90 },
            { id: 2, name: '600ml', price: 8.90 },
            { id: 3, name: '2 Litros', price: 12.90 }
          ],
          hasFlavors: false,
          hasToppings: false,
          hasSpiciness: false
        },
        specifications: {
          isVegetarian: true,
          isVegan: true,
          isGlutenFree: true,
          allergens: []
        },
        stock: { available: true, quantity: 200 },
        featured: false,
        order: 4
      },
      {
        id: 5,
        name: 'Coca-Cola 1L',
        description: 'Refrigerante gelado',
        price: 9.90,
        image: ['../src/assets/images/prod.png'],
        category: 'bebidas',
        tags: ['refrigerante', 'gelada'],
        productType: 'beverage',
        cuisineType: 'drink',
        isCombo: false,
        customization: {
          hasSize: true,
          sizes: [],
          hasFlavors: false,
          hasToppings: false,
          hasSpiciness: false
        },
        specifications: {
          isVegetarian: true,
          isVegan: true,
          isGlutenFree: true,
          allergens: []
        },
        stock: { available: true, quantity: 200 },
        featured: false,
        order: 5
      }
    ])

    // Produtos em destaque para o carrossel
    const highlights = computed(() => {
      return products.value.filter(p => p.featured === true)
    })

    // Categorias agrupadas para exibição
    const categories = computed(() => {
      const categoryMap = new Map()
      
      products.value.forEach(product => {
        if (!categoryMap.has(product.category)) {
          categoryMap.set(product.category, {
            id: product.category,
            name: getCategoryName(product.category),
            products: []
          })
        }
        categoryMap.get(product.category).products.push(product)
      })
      
      return Array.from(categoryMap.values())
    })

    // Helper para nomes das categorias
    const getCategoryName = (categoryKey) => {
      const names = {
        'hamburguers': 'Hambúrgueres',
        'pizzas': 'Pizzas',
        'acai': 'Açaí',
        'bebidas': 'Bebidas',
        'entradas': 'Entradas',
        'sobremesas': 'Sobremesas',
        'combos': 'Combos'
      }
      return names[categoryKey] || categoryKey
    }

    // ========== FUNÇÕES PARA COMBOS ==========
    
    // Verifica se um produto é combo
    const isCombo = (product) => {
      return product?.isCombo === true
    }

    // Calcula o preço total do combo baseado nas escolhas do usuário
    const calculateComboPrice = (combo, selections = {}) => {
      let totalPrice = combo.price // Preço base do combo
      
      // Adiciona preço dos addons selecionados
      if (selections.selectedAddons) {
        selections.selectedAddons.forEach(addon => {
          totalPrice += addon.price * addon.quantity
        })
      }
      
      // Adiciona customizações extras dos itens
      if (selections.itemCustomizations) {
        Object.values(selections.itemCustomizations).forEach(custom => {
          if (custom.selectedToppings) {
            custom.selectedToppings.forEach(topping => {
              totalPrice += topping.price
            })
          }
          if (custom.selectedSize && custom.selectedSize.price) {
            const originalItemPrice = combo.comboItems.find(i => i.name === custom.itemName)?.price || 0
            totalPrice += (custom.selectedSize.price - originalItemPrice)
          }
        })
      }
      
      return totalPrice
    }

    // Prepara o combo para adicionar ao carrinho
    const prepareComboForCart = (combo, selections = {}) => {
      return {
        id: `${combo.id}_${Date.now()}`,
        productId: combo.id,
        name: combo.name,
        description: combo.description,
        basePrice: combo.price,
        finalPrice: calculateComboPrice(combo, selections),
        quantity: 1,
        isCombo: true,
        comboItems: combo.comboItems,
        comboAddons: combo.comboAddons,
        selections: selections,
        image: combo.image?.[0] || combo.image,
        savings: combo.savings
      }
    }

    // Função para adicionar combo ao carrinho
    const addComboToCart = (combo, selections = {}) => {
      const comboItem = prepareComboForCart(combo, selections)
      cart.add(comboItem)
      toast.success(`${combo.name} adicionado ao carrinho! Economia de R$ ${combo.savings?.toFixed(2) || '0,00'}`, {
        timeout: 3000
      })
    }

    // Helper para obter produtos disponíveis para combo
    const getAvailableProductsForCombo = () => {
      return products.value.filter(p => !p.isCombo)
    }

    // ========== FUNÇÕES PARA PRODUTOS NORMAIS ==========

    // Função para adicionar produto normal ao carrinho
    function addToCart(product, customizations = {}) {
      const cartItem = {
        id: `${product.id}_${Date.now()}`,
        productId: product.id,
        name: product.name,
        basePrice: product.price,
        finalPrice: calculateFinalPrice(product, customizations),
        quantity: 1,
        customizations,
        image: product.image?.[0] || product.image,
        isCombo: false
      }
      
      cart.add(cartItem)
      toast.success(`${product.name} adicionado ao carrinho!`, {
        timeout: 2000
      })
    }

    // Calcula preço final baseado nas personalizações
    const calculateFinalPrice = (product, customizations) => {
      let finalPrice = product.price
      
      if (customizations.selectedSize) {
        finalPrice = customizations.selectedSize.price
      }
      
      if (customizations.selectedFlavors) {
        customizations.selectedFlavors.forEach(flavor => {
          finalPrice += flavor.price
        })
      }
      
      if (customizations.selectedToppings) {
        customizations.selectedToppings.forEach(topping => {
          finalPrice += topping.price
        })
      }
      
      return finalPrice
    }

    // Função unificada para adicionar ao carrinho (produto ou combo)
    const addItemToCart = (item, selections = {}) => {
      if (isCombo(item)) {
        addComboToCart(item, selections)
      } else {
        addToCart(item, selections)
      }
    }

    // Monitora mudanças no carrinho
    watch(() => cart.items, (newItems) => {
      console.log('Carrinho atualizado:', JSON.parse(JSON.stringify(newItems)))
    }, { deep: true })

    // Inicialização
    onMounted(() => {
      console.log('App inicializado com estrutura de produtos e combos')
      console.log('Produtos disponíveis:', products.value.length)
      console.log('Combos disponíveis:', products.value.filter(p => p.isCombo).length)
    })

    // Expor produtos globalmente para acesso do Cart
    if (typeof window !== 'undefined') {
      window.products = products.value
    }
</script>

<template>
  <div class="container-fluid p-0">
    <Header class="header" />
    
    <MobileBottomMenu 
    @change="handleMenuChange" 
    @open-orders="openOrderHistory"/>
    
    <Cart />

    <div class="layout">
      <main class="main">

        <!-- Topo CONTIDO -->
        <div class="container">
          <TopBar />          
        </div>

        <div class="container py-0">
  
          <div class="d-flex flex-wrap">
            <!-- PASSANDO AS CATEGORIAS PARA O ASIDE -->
            <Aside :categories="categories" class="aside" />

            <div class="content w-mobile-100">
              
              <Announcement/>
              
              <div class="carousel-breakout">
                <ProductCarousel
                  :products="highlights"
                  @open="openProductModal"
                />
              </div>

              <div class="list-product">
                <ProductList
                  :categories="categories"
                  @open="openProductModal"
                />
              </div>

            </div>
          </div>

        </div>

        <RouterView />

      </main>

      <ProductModal
        v-model:show="showProductModal"
        :product="selectedProduct"
        @add-to-cart="addItemToCart"
      />
    </div>

    <OrderHistoryModal 
      v-if="showOrderHistoryModal"
      v-model="showOrderHistoryModal"
      :user-id="userStore.userId"
      @reorder="handleReorder"
    />
    
    <!-- IdentifyModal para login -->
    <IdentifyModal
      v-model="showLoginModal"
      @submit="handleIdentify"
    />

    <Footer/>
  </div>
</template>

<style scoped>
@media (max-width: 768px) {
  .aside{
    width: 100%;
  }
  .w-mobile-100{
    width: 100%;
  }
  .carousel-breakout{
    margin-left: 0 !important;
    width: 100vw !important;
  }
}
  .list-product{
      width: 69vw;
      margin-left: calc(-30vw + 50%);
  }
  .carousel-breakout {
    width: 90vw;
    margin-left: calc(-30vw + 50%);
    margin-top: -40px;
  }

  /* opcional: controlar o lado direito */
  .carousel-breakout .swiper {
    padding-right: 2rem;
  }
  @media (max-width: 680px) {
    .carousel-breakout{
      margin-top: 0;
    }
    .carousel-breakout{
      padding-bottom: 10px;
    }
    .swiper-button-prev, .swiper-button-next{
      top: 25px !important;
    }
  }
</style>