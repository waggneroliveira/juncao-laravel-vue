<template>
    <div class="banner-inner container-fluid p-0 position-relative text-end">
        <img src="@/assets/images/banner-inner.png" 
        alt="Banner interno" 
        class="w-100 h-100 position-relative z-10 cover">

        <div class="overlay">
            <div class="d-flex justify-content-between px-2">
                <div class="d-flex justify-content-center align-items-center gap-3">
                    <!-- Botão menu mobile -->
                    <button 
                    class="btn d-md-none p-0 border-0 bg-transparent"
                    data-bs-toggle="offcanvas" 
                    data-bs-target="#mobileMenu"
                    >
                        <svg width="42" height="42" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">                    
                            <line x1="9" y1="7" x2="21" y2="7" stroke="white" stroke-width="2" stroke-linecap="round"/>
                            <line x1="5" y1="12" x2="20" y2="12" stroke="white" stroke-width="2" stroke-linecap="round"/>
                            <line x1="5" y1="17" x2="15" y2="17" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <i class="bi bi-search text-white fs-5"></i>
                </div>
                <div class="d-md-none py-2 px-1 d-flex justify-content-end align-items-end gap-3">
                    <span 
                        class="position-relative"
                        style="cursor: pointer;"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#cartCanvas"
                        aria-controls="cartCanvas"
                    >
                        <i class="bi bi-bag text-main fs-5"></i>

                        <span v-if="cartStore.totalItems" class="cart position-absolute d-flex justify-content-center align-items-center p-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ cartStore.totalItems }}
                        </span>
                    </span>
                    <div class="icon-user rounded-5 d-flex justify-content-center align-items-center p-0 overflow-hidden">
                        <svg 
                            width="24" 
                            height="28" 
                            viewBox="0 0 20 23" 
                            fill="none" 
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path d="M10.2963 12.6129C13.6296 12.4645 16.2963 9.68226 16.2963 6.30645C16.2963 2.81936 13.4815 0 10 0C6.51852 0 3.7037 2.81936 3.7037 6.30645C3.7037 9.68226 6.37037 12.4274 9.7037 12.6129C4.22222 12.7984 0 17.1758 0 23H1.48148C1.48148 17.8065 5.14815 14.0968 10 14.0968C14.8519 14.0968 18.5185 17.8065 18.5185 23H20C20 17.1758 15.7778 12.7984 10.2963 12.6129ZM5.18518 6.34355C5.18518 3.67258 7.33333 1.52097 10 1.52097C12.6667 1.52097 14.8148 3.67258 14.8148 6.34355C14.8148 9.01452 12.6667 11.1661 10 11.1661C7.33333 11.1661 5.18518 8.97742 5.18518 6.34355Z" fill="white"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useCartStore } from '@/stores/useCartStore'

const cartStore = useCartStore()

</script>

<style scoped>
    .cart{
        width: 18px;
        height: 18px;
        font-size: 0.75rem;
        top: 2px
    }
    .icon-user{
        background: #E9ECEF;
        width: 38px; 
        height: 38px;
    }
    .icon-user svg{
        width: 18px;
        height: 18px;
    }
    .banner-inner{
        height: 209px;
    }
    .cover{
        object-fit: cover;
    }
    .overlay{
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 11;
    }
    @media (max-width: 680px) {
        .banner-inner {
            height: 130px;
        }
    
    }
</style>