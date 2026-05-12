<template>
  <div>
    <!-- Modal -->
    <div class="modal fade show" tabindex="-1" style="display: block;" v-if="show" @click.self="close">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content p-0">
            
            <div class="modal-header py-2 px-2 px-md-3 mb-4 d-flex gap-2 justify-content-between">
                <svg class="icon-location" width="34" height="42" viewBox="0 0 34 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17 0C7.62838 0 0 7.27149 0 16.2047C0 20.62 1.6795 24.8739 4.71769 28.1973L17 41.5667L29.2823 28.1973C32.3205 24.8758 34 20.6219 34 16.2047C34 7.27149 26.3716 0 17 0ZM17 22.2836C13.4717 22.2836 10.6247 19.5699 10.6247 16.2066C10.6247 12.8434 13.4717 10.1296 17 10.1296C20.5283 10.1296 23.3753 12.8434 23.3753 16.2066C23.3753 19.5699 20.5283 22.2836 17 22.2836Z" fill="#595959"/>
                </svg>

                <div class="header col-11">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h5 class="mb-0 location-title">Locais de entrega</h5>
                        <button type="button" class="btn-close" @click="close"></button>
                    </div>
                    
                    <p class="small text-muted mb-0">
                        Confira os bairros atendidos e as taxas de entrega
                    </p>
                </div>
            </div>

            <div class="px-2 px-md-3">

                <!-- Endereço -->
                <div class="border p-3 rounded mb-3 d-flex gap-2 align-items-start">
                    <svg width="39" height="30" viewBox="0 0 39 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M36.4453 6.97266C38.3789 10.1367 36.6211 14.4727 32.9883 14.9414C32.6953 15 32.4609 15 32.168 15C30.4102 15 28.8867 14.2383 27.832 13.0664C26.7773 14.2383 25.2539 15 23.4961 15C21.7969 15 20.2148 14.2383 19.1602 13.0664C18.1055 14.2383 16.582 15 14.8828 15C13.125 15 11.6016 14.2383 10.5469 13.0664C9.49219 14.2383 7.96875 15 6.21094 15C5.91797 15 5.68359 15 5.39062 14.9414C1.75781 14.4727 0 10.1367 1.99219 6.97266L5.74219 0.878906C6.09375 0.351562 6.73828 0 7.38281 0H31.0547C31.6992 0 32.2852 0.351562 32.6367 0.878906L36.4453 6.97266ZM32.168 16.875C32.5195 16.875 32.8711 16.875 33.2227 16.8164C33.5742 16.8164 33.8672 16.6992 34.2188 16.6406V28.125C34.2188 29.1797 33.3398 30 32.3438 30H6.09375C5.03906 30 4.21875 29.1797 4.21875 28.125V16.6406C4.51172 16.6992 4.80469 16.7578 5.15625 16.8164C5.50781 16.875 5.85938 16.875 6.21094 16.875C6.79688 16.875 7.38281 16.8164 7.96875 16.6992V22.5H30.4688V16.6992C30.9961 16.8164 31.582 16.875 32.168 16.875Z" fill="var(--bg-orange)"/>
                    </svg>

                    <div>
                        <strong>Nosso Endereço</strong>
                        <p class="mb-0 small text-muted">
                            Rua Heróis de França 886, 4450-156 Porto, Portugal. Próximo à estátua da liberdade, entre a padaria e o posto de combustível.
                        </p>
                    </div>
                </div>

                <!-- Loading State -->
                <div v-if="loading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <p class="mt-2 text-muted">Carregando regiões de entrega...</p>
                </div>

                <!-- Error State -->
                <div v-else-if="error" class="alert alert-danger text-center">
                    {{ error }}
                </div>

                <!-- Info Bairros e Tempo -->
                <div v-if="!loading && !error && filteredAreas.length > 0" class="d-flex gap-2 flex-wrap mb-3">
                    <div class="border rounded p-3 flex-fill d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>  
                        <div class="d-flex flex-column">
                            <p class="mb-0">Bairros atendidos</p>
                            <small class="text-muted mb-0">{{ filteredTotalNeighborhoods }} bairros</small>
                        </div>
                    </div>
                    <div class="border rounded p-3 flex-fill d-flex align-items-center gap-2">
                        <i class="bi bi-clock-fill me-2"></i> 
                        <div class="d-flex flex-column">
                            <p class="mb-0">Entrega: {{ filteredMinEstimatedTime }}-{{ filteredMaxEstimatedTime }} minutos</p>                            
                            <small class="text-muted mb-0">Taxa conforme bairro</small>
                        </div>
                    </div>
                </div>

                <!-- Campo de busca -->
                <div class="mb-4">
                    <div class="search-container">
                        <i class="bi bi-search search-icon"></i>
                        <input 
                            type="text" 
                            class="form-control search-input" 
                            v-model="searchTerm" 
                            placeholder="Buscar bairro ou cidade..."
                            @input="handleSearch"
                        >
                        <button 
                            v-if="searchTerm" 
                            class="btn-clear-search" 
                            @click="clearSearch"
                        >
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <!-- Resultado da busca específica -->
                    <div v-if="searchTerm && searchResult" class="mt-4 search-result-alert">
                        <div :class="searchResult.found ? 'alert alert-success' : 'alert alert-warning'">
                            <div class="d-flex align-items-center gap-2">
                                <i :class="searchResult.found ? 'bi bi-check-circle-fill' : 'bi bi-exclamation-triangle-fill'"></i>
                                <span>
                                    <strong>{{ searchResult.found ? 'Atendemos' : 'Não atendemos' }}</strong> 
                                    "{{ searchTerm }}"
                                    <span v-if="searchResult.found">
                                        - Taxa: {{ searchResult.price }} - Entrega: {{ searchResult.estimated_time }}
                                    </span>
                                    <span v-else>
                                        - Verifique os bairros atendidos abaixo
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lista de bairros filtrada -->
                <div class="col-12 height">
                    <div v-for="(area, index) in filteredAreas" :key="index" class="mb-4">
                        <h6 class="mb-3 fw-bold"><i class="bi bi-geo-alt-fill me-2"></i>{{ area.name }}</h6>
                        <div class="row g-2">
                            <div 
                                v-for="(neighborhood, i) in area.neighborhoods" 
                                :key="i" 
                                class="col-6 col-md-3"
                            >
                                <div class="border rounded text-center p-2 min-height d-flex justify-content-center align-items-center flex-column">
                                    <h6 class="name text-uppercase">{{ neighborhood.name }}</h6>
                                    <p class="value text-primary mb-0">{{ neighborhood.price }}</p>
                                    <small class="f-12 text-muted">{{ neighborhood.estimated_time }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="!loading && !error && filteredAreas.length === 0" class="text-center py-5">
                    <i class="bi bi-geo-alt-slash fs-1 text-muted"></i>
                    <p class="mt-2 text-muted">
                        {{ searchTerm ? 'Nenhum bairro encontrado com este termo.' : 'Nenhuma região de entrega cadastrada no momento.' }}
                    </p>
                    <button v-if="searchTerm" class="btn btn-link" @click="clearSearch">
                        Limpar busca
                    </button>
                </div>          
            </div>
        </div>
      </div>
    </div>

    <!-- Fundo do modal -->
    <div v-if="show" class="modal-backdrop fade show"></div>
  </div>
</template>

<script setup>
    import { defineProps, defineEmits, computed, ref, watch } from 'vue'
    import axios from 'axios'

    const props = defineProps({
        modelValue: Boolean
    })
    const emit = defineEmits(['update:modelValue'])

    const show = computed({
        get: () => props.modelValue,
        set: (val) => emit('update:modelValue', val)
    })

    function close() {
        show.value = false
        // Resetar busca ao fechar
        searchTerm.value = ''
        searchResult.value = null
    }

    // Estados
    const loading = ref(false)
    const error = ref(null)
    const deliveryRegions = ref([])
    const areas = ref([])
    const searchTerm = ref('')
    const searchResult = ref(null)

    // Função de busca
    function handleSearch() {
        if (!searchTerm.value.trim()) {
            searchResult.value = null
            return
        }

        const term = searchTerm.value.toLowerCase().trim()
        
        // Buscar em todas as regiões
        const found = deliveryRegions.value.find(region => 
            region.name.toLowerCase().includes(term)
        )

        if (found) {
            searchResult.value = {
                found: true,
                name: found.name,
                price: formatCurrency(found.delivery_fee),
                estimated_time: `${found.estimated_time_min}-${found.estimated_time_max} min`,
                delivery_fee: found.delivery_fee
            }
        } else {
            searchResult.value = {
                found: false,
                term: term
            }
        }
    }

    function clearSearch() {
        searchTerm.value = ''
        searchResult.value = null
    }

    // Computed para filtrar áreas
    const filteredAreas = computed(() => {
        if (!searchTerm.value.trim()) {
            return areas.value
        }

        const term = searchTerm.value.toLowerCase().trim()
        
        return areas.value
            .map(area => ({
                ...area,
                neighborhoods: area.neighborhoods.filter(neighborhood => 
                    neighborhood.name.toLowerCase().includes(term) ||
                    area.name.toLowerCase().includes(term)
                )
            }))
            .filter(area => area.neighborhoods.length > 0)
    })

    // Totais filtrados
    const filteredTotalNeighborhoods = computed(() => {
        let total = 0
        filteredAreas.value.forEach(area => {
            total += area.neighborhoods.length
        })
        return total
    })

    const filteredMinEstimatedTime = computed(() => {
        if (filteredAreas.value.length === 0) return 0
        let minTime = Infinity
        filteredAreas.value.forEach(area => {
            area.neighborhoods.forEach(neighborhood => {
                if (neighborhood.min_time < minTime) {
                    minTime = neighborhood.min_time
                }
            })
        })
        return minTime === Infinity ? 0 : minTime
    })

    const filteredMaxEstimatedTime = computed(() => {
        if (filteredAreas.value.length === 0) return 0
        let maxTime = 0
        filteredAreas.value.forEach(area => {
            area.neighborhoods.forEach(neighborhood => {
                if (neighborhood.max_time > maxTime) {
                    maxTime = neighborhood.max_time
                }
            })
        })
        return maxTime
    })

    // Totais gerais (sem filtro)
    const totalNeighborhoods = computed(() => {
        return deliveryRegions.value.length
    })

    const minEstimatedTime = computed(() => {
        if (deliveryRegions.value.length === 0) return 0
        return Math.min(...deliveryRegions.value.map(r => r.estimated_time_min))
    })

    const maxEstimatedTime = computed(() => {
        if (deliveryRegions.value.length === 0) return 0
        return Math.max(...deliveryRegions.value.map(r => r.estimated_time_max))
    })

    // Formatar moeda
    function formatCurrency(value) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(value)
    }

    // Buscar regiões de entrega
    async function fetchDeliveryRegions() {
        loading.value = true
        error.value = null
        
        try {
            const response = await axios.get('/api/delivery-regions')
            deliveryRegions.value = response.data.data || response.data
            
            // Agrupar por cidade
            const grouped = {}
            
            deliveryRegions.value.forEach(region => {
                let city = 'Geral'
                
                if (region.name.includes(' - ')) {
                    city = region.name.split(' - ')[0]
                } else if (region.name.includes('-')) {
                    city = region.name.split('-')[0].trim()
                } else {
                    const cities = ['Salvador', 'Lauro de Freitas', 'Simões Filho', 'Camaçari']
                    for (const c of cities) {
                        if (region.name.includes(c)) {
                            city = c
                            break
                        }
                    }
                }
                
                if (!grouped[city]) {
                    grouped[city] = {
                        name: city,
                        neighborhoods: []
                    }
                }
                
                grouped[city].neighborhoods.push({
                    name: region.name,
                    price: formatCurrency(region.delivery_fee),
                    estimated_time: `${region.estimated_time_min}-${region.estimated_time_max} min`,
                    delivery_fee: region.delivery_fee,
                    min_time: region.estimated_time_min,
                    max_time: region.estimated_time_max
                })
            })
            
            Object.keys(grouped).forEach(city => {
                grouped[city].neighborhoods.sort((a, b) => a.name.localeCompare(b.name))
            })
            
            areas.value = Object.values(grouped)
            
        } catch (err) {
            console.error('Erro ao buscar regiões de entrega:', err)
            error.value = 'Não foi possível carregar as regiões de entrega. Tente novamente mais tarde.'
        } finally {
            loading.value = false
        }
    }

    // Buscar quando o modal abrir
    watch(show, (newValue) => {
        if (newValue && deliveryRegions.value.length === 0) {
            fetchDeliveryRegions()
        } else if (!newValue) {
            // Resetar busca ao fechar
            searchTerm.value = ''
            searchResult.value = null
        }
    })
</script>

<style scoped>
.col-12.height{
    max-height: 300px;
    overflow-y: auto;
    overflow-x: hidden;
}
/* Estilos apenas para o campo de busca adicionado */
.search-container {
    position: relative;
    width: 100%;
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    font-size: 1rem;
    z-index: 1;
}

.search-input {
    padding-left: 38px;
    padding-right: 38px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    transition: all 0.2s ease;
}

.search-input:focus {
    border-color: #ff8c00;
    box-shadow: 0 0 0 0.2rem rgba(255, 140, 0, 0.25);
    outline: none;
}

.btn-clear-search {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    transition: color 0.2s ease;
}

.btn-clear-search:hover {
    color: #dc3545;
}

.search-result-alert {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.f-12{
    font-size: 0.75rem;
}
.alert {
    border-radius: 8px;
    font-size: 0.875rem;
    padding: 0.75rem 1rem;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-warning {
    background-color: #fff3cd;
    border-color: #ffeeba;
    color: #856404;
}
.location-title{
   font-weight: 600; 
   color: #595959;
}
.bi{
    color: #FF9800;
}
.min-height{
    min-height: 75px;
}
.name{
    font-size: 0.75rem;
    font-weight: 600;
}
.value{
    font-size: 0.875rem;
    font-weight: 600; 
}
.modal-backdrop {
  z-index: 1040;
}
.modal-content {
  z-index: 1050;
}
@media (max-width: 476px) {
    .min-height{
        min-height: 70px;
    }
    .name{
        font-size: 0.625rem;
        margin-bottom: 3px;
    }
    .border.rounded.p-2.flex-fill.d-flex.align-items-center.gap-2{
        font-size: 0.844rem;
    }
    .location-title{
        font-size: 1rem;
    }
    .small.text-muted.mb-0{
        font-size: 0.75rem !important;
    }
    .icon-location{
        width: 15px;
        height: 25px;
    }
}
</style>