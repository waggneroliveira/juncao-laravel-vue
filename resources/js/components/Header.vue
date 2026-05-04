<template>
    <div class="banner-inner container-fluid p-0 position-relative text-end">
        <img src="@/assets/images/banner-inner.png" 
        alt="Banner interno" 
        class="w-100 h-100 position-relative z-10 cover">

        <div class="overlay">
            <!-- Botão menu mobile -->
            <button 
            class="btn d-md-none"
            data-bs-toggle="offcanvas" 
            data-bs-target="#mobileMenu"
            >
            <i class="bi bi-list display-2 text-white"></i>
            </button>
        </div>
    </div>

</template>

<style scoped>
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