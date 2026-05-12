<template>
    <div class="image my-4 mt-0 my-md-0">
        <img 
              src="@/assets/images/delifast-hotizontal.png" 
              alt="Oxente Açaiteria"
              class="logo"
          />
    </div>
</template>

<style scoped>
    .image{
        text-align: end;
        width: 90vw;
        margin-left: calc(-49.5vw + 50%);
    }
    .image img{
        width: 100%;
        max-width: 530px;
        height: 225px;
        object-fit: cover;
        aspect-ratio: 1 / 1.92;
    }
    @media (max-width: 768px) {
        .image{
            margin-left: 0 !important;
            width: 100% !important
        }
    }
    @media (max-width: 680px) {
        .image img{
            height: 160px;
        }
    }
</style>