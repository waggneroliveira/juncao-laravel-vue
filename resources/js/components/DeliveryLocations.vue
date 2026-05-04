<template>
  <div>
    <!-- Modal -->
    <div class="modal fade show" tabindex="-1" style="display: block;" v-if="show" @click.self="close">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content p-0">
            
            <div class="modal-header py-2 px-2 mb-4 d-flex gap-2 justify-content-between">
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

            <div class="px-2">

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

                <!-- Info Bairros e Tempo -->
                <div class="d-flex gap-2 flex-wrap mb-5">
                    <div class="border rounded p-2 flex-fill d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success"></i> Bairros atendidos
                    </div>
                    <div class="border rounded p-2 flex-fill d-flex align-items-center gap-2">
                        <i class="bi bi-clock-fill"></i> Entrega: 30-45 minutos
                    </div>
                </div>

                <!-- Lista de bairros -->
                <div v-for="(area, index) in areas" :key="index" class="mb-4">
                    <h6 class="mb-3"><i class="bi bi-geo-alt-fill me-2"></i>{{ area.name }}</h6>
                    <div class="row g-2">
                        <div 
                            v-for="(neighborhood, i) in area.neighborhoods" 
                            :key="i" 
                            class="col-6 col-md-3"
                        >
                            <div class="border rounded text-center p-2 min-height d-flex justify-content-center align-items-center flex-column">
                                <h6 class="name">{{ neighborhood.name }}</h6>
                                <p class="value text-primary mb-0">{{ neighborhood.price }}</p>
                            </div>
                        </div>
                    </div>
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
    import { defineProps, defineEmits, computed } from 'vue'

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
    }

    const areas = [
    {
        name: 'Salvador',
        neighborhoods: [
        { name: 'ALPHAVILLE II', price: 'R$ 20,00' },
        { name: 'VILLAS DO ATLANTICO', price: 'R$ 20,00' },
        { name: 'PRAIA DO FLAMENTO', price: 'R$ 20,00' },
        { name: 'FAZENDA GRANDE DO RETIRO', price: 'R$ 20,00' },
        ]
    },
    {
        name: 'Lauro de Freitas',
        neighborhoods: [
        { name: 'ALPHAVILLE II', price: 'R$ 20,00' },
        { name: 'VILLAS DO ATLANTICO', price: 'R$ 20,00' },
        { name: 'PRAIA DO FLAMENTO', price: 'R$ 20,00' },
        { name: 'FAZENDA GRANDE DO RETIRO', price: 'R$ 20,00' },
        ]
    }
    ]
</script>

<style scoped>
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