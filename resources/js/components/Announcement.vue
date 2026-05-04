<template>
    <div class="image my-4 mt-0 my-md-0">
        <img 
              src="@/assets/images/anuncio-horizontal.png" 
              alt="Oxente Açaiteria"
              class="logo"
          />
    </div>
</template>

<style scoped>
@media (max-width: 768px) {
    .image{
        margin-left: 0 !important;
        width: 100% !important
    }
}
    .image{
        text-align: end;
        width: 90vw;
        margin-left: calc(-49.5vw + 50%);
    }
    .image img{
        width: 100%;
        max-width: 500px;
        height: 120px;
        object-fit: cover;
        aspect-ratio: 1 / 1.92;
    }
</style>