<template>
  <div class="top-bar flex-column flex-md-row d-flex justify-content-center justify-content-md-between align-items-center align-items-md-start gap-2 gap-md-3 flex-wrap position-relative">

    <div class="content-logo">
      <!-- Logo -->
      <div class="image text-center mb-2 mb-md-3 position-relative rounded-circle">
          <img 
              src="@/assets/images/logo.png" 
              alt="Oxente Açaiteria"
              class="logo"
          />
      </div>

      <!-- Nome + status -->
      <div class="text-center text-md-start mb-0 mb-md-4 w-100">
        <h5 class="fw-medium text-primary mb-1">Oxente Açaiteria</h5>
        <small class="d-block font-12 text-grey">Praticidade que alimenta.</small>
        
        <div class="d-flex justify-content-center justify-content-md-start align-items-center mt-0 mt-md-2">
          <span class="status-dot me-1"></span>
          <small class="text-grey">Aberto <i class="fst-normal"> • 14h às 18h</i></small>
        </div>
      </div>
    </div>
    
    <!-- Horários e contato -->
    <div class="d-none d-md-flex flex-row flex-wrap align-items-start gap-2 text-secondary small mt-0 mt-md-5">
      
      <div class="horous">
        <div class="d-flex align-items-start gap-2 mb-1 pe-2 border-end">
          <svg class="mt-1" width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M8.49928 0C3.81162 0 0 3.81382 0 8.50135C0 13.1891 3.81162 17 8.49928 17C13.1869 17 17 13.1891 17 8.50135C17 3.81364 13.1869 0 8.49928 0ZM8.49928 1.11725C12.5836 1.11725 15.8847 4.41628 15.8847 8.50135C15.8847 12.5857 12.5836 15.8849 8.49928 15.8849C4.41497 15.8849 1.1158 12.5866 1.1158 8.50135C1.1158 4.417 4.41407 1.11725 8.49928 1.11725ZM8.49928 2.34841C8.19062 2.34841 7.941 2.59802 7.941 2.90599V8.50144C7.94241 8.6491 8.00147 8.79043 8.10623 8.89449L10.7416 11.5299C10.9595 11.7471 11.3118 11.7471 11.5298 11.5299C11.747 11.3119 11.747 10.9589 11.5298 10.7417L9.05696 8.27081V2.90594C9.05696 2.59798 8.80725 2.34841 8.49928 2.34841Z" fill="var(--primary)"/>
          </svg>

          <span class="horous-day d-flex flex-column text-grey">
            Segunda à sexta-feira <i>14:00 às 18:00</i>
          </span>
        </div>

        <div class="d-flex align-items-start gap-2">
          <svg class="mt-1" width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M8.49928 0C3.81162 0 0 3.81382 0 8.50135C0 13.1891 3.81162 17 8.49928 17C13.1869 17 17 13.1891 17 8.50135C17 3.81364 13.1869 0 8.49928 0ZM8.49928 1.11725C12.5836 1.11725 15.8847 4.41628 15.8847 8.50135C15.8847 12.5857 12.5836 15.8849 8.49928 15.8849C4.41497 15.8849 1.1158 12.5866 1.1158 8.50135C1.1158 4.417 4.41407 1.11725 8.49928 1.11725ZM8.49928 2.34841C8.19062 2.34841 7.941 2.59802 7.941 2.90599V8.50144C7.94241 8.6491 8.00147 8.79043 8.10623 8.89449L10.7416 11.5299C10.9595 11.7471 11.3118 11.7471 11.5298 11.5299C11.747 11.3119 11.747 10.9589 11.5298 10.7417L9.05696 8.27081V2.90594C9.05696 2.59798 8.80725 2.34841 8.49928 2.34841Z" fill="var(--primary)"/>
          </svg>

          <span class="horous-day d-flex flex-column text-grey">
            Sábado e Domingo <i>14:00 às 18:00</i>
          </span>
        </div>
      </div>

      <div class="d-flex align-items-center gap-2 px-2">
        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12.276 15L11.6145 14.991L11.424 14.955C11.274 14.9265 11.115 14.898 10.9635 14.8605C10.095 14.6446 9.22797 14.2457 8.23348 13.6084C7.01398 12.8256 5.81248 11.8253 4.45199 10.4592C3.38228 9.41612 2.41719 8.27096 1.5705 7.04006C0.851998 5.97084 0.421499 5.09057 0.1725 4.16681C0.1215 3.98085 0.0869998 3.7964 0.0539998 3.61795C0.0389999 3.54147 0.0254999 3.46799 0.0105 3.39001L0 3.29553V2.76167L0.0359999 2.67469C0.299999 2.03586 0.749998 1.58447 1.191 1.17808C1.512 0.878156 1.85099 0.605227 2.16899 0.351793C2.35931 0.195921 2.58393 0.087452 2.82436 0.0353103C3.0648 -0.0168314 3.31418 -0.0111557 3.55199 0.0518703C3.86158 0.125386 4.13049 0.316266 4.30199 0.584232L4.71299 1.20357C5.14199 1.8574 5.58448 2.53223 6.03448 3.18456C6.20578 3.42104 6.30835 3.70027 6.33085 3.99138C6.35335 4.28249 6.29491 4.57415 6.16198 4.83413C6.08248 5.00959 5.99098 5.17154 5.90248 5.329L5.78998 5.53145C5.70598 5.68141 5.62348 5.83137 5.53648 5.98134L5.81848 6.31875C6.07198 6.61867 6.33598 6.94109 6.60598 7.23201C7.34686 8.04619 8.15304 8.7985 9.01647 9.48143C9.33447 9.29698 9.66747 9.11102 10.032 8.91308C10.3967 8.69139 10.8318 8.61585 11.25 8.70163C11.3983 8.73553 11.5395 8.79544 11.667 8.87858C12.596 9.48943 13.5225 10.1028 14.4465 10.7186C14.9295 11.041 15.123 11.6979 14.9205 12.3187C14.8748 12.467 14.8059 12.6072 14.7165 12.7341C14.2352 13.4158 13.6612 14.027 13.011 14.5501C12.8692 14.6566 12.7187 14.751 12.561 14.832C12.504 14.8635 12.4485 14.8935 12.393 14.925L12.276 15ZM11.7525 14.0387H12.0195L12.1005 13.9953C12.2115 13.9389 12.3179 13.8737 12.4185 13.8003C12.9984 13.3286 13.5098 12.7784 13.938 12.1657C13.9671 12.1192 13.9894 12.0687 14.004 12.0158C14.0396 11.9327 14.0497 11.8409 14.0332 11.752C14.0166 11.6631 13.9741 11.5811 13.911 11.5164C12.988 10.9016 12.0635 10.2897 11.1375 9.68088C11.1057 9.66066 11.0707 9.64595 11.034 9.63739C10.8436 9.60479 10.648 9.64551 10.4865 9.75136C10.083 9.9688 9.72147 10.1728 9.38097 10.3737C9.25545 10.4554 9.1071 10.4948 8.95759 10.4861C8.80808 10.4775 8.66526 10.4213 8.54998 10.3257C7.59896 9.58893 6.71364 8.77107 5.90398 7.88134C5.61448 7.57092 5.34298 7.24401 5.08049 6.92759C4.94099 6.76413 4.79999 6.60217 4.66799 6.44022C4.5714 6.33391 4.51318 6.19836 4.50258 6.05514C4.49197 5.91192 4.52961 5.76928 4.60949 5.64992C4.72799 5.45497 4.83749 5.25702 4.94699 5.06057L5.06399 4.85213C5.14649 4.70217 5.22299 4.5702 5.28448 4.43374C5.34799 4.32546 5.37776 4.20072 5.37002 4.07545C5.36227 3.95017 5.31734 3.83005 5.24098 3.73042C4.79099 3.06909 4.34099 2.38827 3.90749 1.73144L3.50699 1.11659C3.48719 1.08411 3.46081 1.05613 3.42956 1.03443C3.3983 1.01274 3.36285 0.99782 3.32549 0.990627C3.23099 0.962045 3.13089 0.957219 3.03408 0.976577C2.93727 0.995935 2.84673 1.03888 2.77049 1.1016C2.47049 1.34604 2.14199 1.60847 1.84349 1.8844C1.4895 2.20981 1.1565 2.53823 0.961497 2.95512V3.24604C0.974997 3.31053 0.986997 3.37501 0.998997 3.43949C1.0305 3.60745 1.0605 3.76491 1.101 3.91487C1.32 4.72616 1.7115 5.52395 2.36849 6.5017C3.181 7.68182 4.1069 8.77975 5.13299 9.77985C6.44998 11.101 7.59448 12.0533 8.75397 12.7986C9.65397 13.3789 10.434 13.7373 11.196 13.9278C11.328 13.9608 11.463 13.9848 11.598 14.0088L11.7525 14.0387Z" fill="var(--primary)"/>
        </svg>
        <span class="horous-day text-grey">Fale Conosco</span>
      </div>

    </div>

    <!-- Busca e ações -->
    <div class="border-start col-12 col-md-auto ps-2 d-none d-md-flex align-items-center gap-2 gap-md-3 justify-content-between justify-content-md-end flex-wrap flex-grow-1 right-container mt-3 mt-md-5 mb-3 mb-md-0">

      <!-- Botão -->
      <button class="rounded-2 bg-primary d-none d-md-block height-35 px-3 btn-reset location-delivery text-white flex-shrink-0" @click="showModalLocation = true">
        Locais de entrega
      </button>

      <!-- Campo de busca -->
      <div class=" d-none d-md-block position-relative flex-grow-1 search-wrapper mt-0">
        <input
          type="text"
          placeholder="Buscar produto..."
          class="form-control p-0 ps-3 height-35 input-search"
        />
        <i class="bi bi-search bg-primary position-absolute d-flex justify-content-center align-items-center top-50 end-0 translate-middle-y m-0 text-white height-35 search-icon"></i>
      </div>

      <!-- Ícones -->
      <div class="d-none d-md-flex align-items-center gap-3 gap-md-4 fs-5 text-primary flex-shrink-0">

        <div class="position-relative">
          <button class="reset-button" data-bs-toggle="offcanvas" data-bs-target="#cartCanvas">
            <svg class="svg-car" width="32" height="34" viewBox="0 0 32 34" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M22.6039 3.84615H13.9929V6.64335H22.6039V3.84615ZM28.3446 3.4965V13.1119L10.3332 16.993L6.81706 0H1.43517C1.05454 0 0.689498 0.18419 0.420351 0.512051C0.151205 0.839912 0 1.28459 0 1.74825C0 2.21192 0.151205 2.65659 0.420351 2.98445C0.689498 3.31231 1.05454 3.4965 1.43517 3.4965H4.62125L9.25684 26.2238H28.3446V22.7273H11.4957L11.0221 20.4021L31.2149 16.0489V3.4965H28.3446ZM9.68739 27.972C9.26162 27.972 8.84541 28.1258 8.49139 28.414C8.13737 28.7021 7.86144 29.1117 7.69851 29.5909C7.53557 30.07 7.49294 30.5973 7.576 31.106C7.65907 31.6147 7.8641 32.0819 8.16517 32.4487C8.46624 32.8154 8.84982 33.0652 9.26741 33.1664C9.68501 33.2676 10.1179 33.2156 10.5112 33.0172C10.9046 32.8187 11.2408 32.4826 11.4773 32.0513C11.7139 31.6201 11.8401 31.1131 11.8401 30.5944C11.8401 29.8989 11.6133 29.2319 11.2096 28.7401C10.8059 28.2483 10.2583 27.972 9.68739 27.972ZM26.9094 27.972C26.4837 27.972 26.0674 28.1258 25.7134 28.414C25.3594 28.7021 25.0835 29.1117 24.9205 29.5909C24.7576 30.07 24.715 30.5973 24.798 31.106C24.8811 31.6147 25.0861 32.0819 25.3872 32.4487C25.6883 32.8154 26.0719 33.0652 26.4894 33.1664C26.907 33.2676 27.3399 33.2156 27.7332 33.0172C28.1266 32.8187 28.4628 32.4826 28.6994 32.0513C28.9359 31.6201 29.0622 31.1131 29.0622 30.5944C29.0622 29.8989 28.8354 29.2319 28.4317 28.7401C28.0279 28.2483 27.4804 27.972 26.9094 27.972Z" fill="var(--primary)"/>
            </svg>

            <span class="position-absolute number-car bg-primary start-100 translate-middle badge rounded-pill d-flex justify-content-center align-items-center">
              {{ cartStore.totalItems > 99 ? '99+' : cartStore.totalItems }}
            </span>
          </button>
        </div>

        <div class="d-flex justify-content-center align-items-center gap-2">
          <svg width="24" height="27" viewBox="0 0 24 27" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M12.3556 14.8065C16.3556 14.6323 19.5556 11.3661 19.5556 7.40323C19.5556 3.30968 16.1778 0 12 0C7.82222 0 4.44444 3.30968 4.44444 7.40323C4.44444 11.3661 7.64444 14.5887 11.6444 14.8065C5.06667 15.0242 0 20.1629 0 27H1.77778C1.77778 20.9032 6.17778 16.5484 12 16.5484C17.8222 16.5484 22.2222 20.9032 22.2222 27H24C24 20.1629 18.9333 15.0242 12.3556 14.8065ZM6.22222 7.44677C6.22222 4.31129 8.8 1.78548 12 1.78548C15.2 1.78548 17.7778 4.31129 17.7778 7.44677C17.7778 10.5823 15.2 13.1081 12 13.1081C8.8 13.1081 6.22222 10.5387 6.22222 7.44677Z" fill="var(--primary)"/>
          </svg>

          <div class="dropdown">
            <!-- Botão dos 3 pontinhos -->
            <div
              class="d-flex flex-column gap-1"
              role="button"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              <span class="points rounded-pill"></span>
              <span class="points rounded-pill"></span>
              <span class="points rounded-pill"></span>
            </div>

            <!-- Menu -->
            <ul class="dropdown-menu custom-dropdown">
              <a class="dropdown-item" href="#" @click="showAddressModal = true">
                Meus Endereços
              </a>
              <a class="dropdown-item" href="#" @click="showProfileModal = true">
                Meu Perfil
              </a>
              <a class="dropdown-item" href="#" @click="openOrderHistory">
                Meus Pedidos
              </a>

              <li><hr class="dropdown-divider"></li>

              <li v-if="userStore.isLogged">
                <a class="dropdown-item text-dark" href="#" @click.prevent="logout">
                  Sair
                </a>
              </li>

              <li v-else>
                <a class="dropdown-item text-dark" href="#" @click="showModal = true">
                  Entrar
                </a>
              </li>
            </ul>            
          </div>
        </div>

      </div>
    </div>
  </div>

 <!-- ================= MENU MOBILE MELHORADO ================= -->
<div 
  class="offcanvas offcanvas-start d-md-none" 
  tabindex="-1" 
  id="mobileMenu"
>
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">
      <i class="bi bi-grid-3x3-gap-fill me-2"></i>Menu
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>

  <div class="offcanvas-body d-flex flex-column gap-3">

    <!-- INFORMAÇÕES DO USUÁRIO (se logado) -->
    <div v-if="userStore.isLogged" class="user-info-mobile p-3 d-flex justify-content-center align-items-center text-start mb-2">
      <div class="avatar-mobile me-2">
        <i class="bi bi-person-circle display-1"></i>
      </div>
      <div class="col-11">
        <h6 class="mb-0">{{ userStore.fullName || 'Usuário' }}</h6>
        <small class="text-muted">{{ userStore.whatsapp || 'Telefone não informado' }}</small>
      </div>
    </div>

    <!-- HORÁRIOS -->
    <div class="menu-section">
      <div class="section-title mb-2">
        <i class="bi bi-clock-history me-2"></i>
        <span class="text-grey">Horário de Funcionamento</span>
      </div>
      <div class="d-flex flex-column gap-2 text-secondary small ps-3">
        <div class="d-flex align-items-start gap-2">
          <i class="bi bi-calendar-day mt-1 text-primary"></i>
          <span class="d-flex flex-column text-grey">
            Segunda à sexta-feira <i class="fst-normal">14:00 às 18:00</i>
          </span>
        </div>
        <div class="d-flex align-items-start gap-2">
          <i class="bi bi-calendar-day mt-1 text-primary"></i>
          <span class="d-flex flex-column text-grey">
            Sábado e Domingo <i class="fst-normal">14:00 às 18:00</i>
          </span>
        </div>
      </div>
    </div>

    <!-- LINKS PRINCIPAIS -->
    <div class="menu-section">
      <div class="section-title mb-2">
        <i class="bi bi-compass me-2"></i>
        <span>Navegação</span>
      </div>
      <div class="menu-links d-flex flex-column gap-2">
        <button 
          class="menu-link-btn"
          @click="closeOffcanvasAndNavigate('home')"
        >
          <i class="bi bi-house-door me-3"></i>
          <span>Início</span>
        </button>

        <button 
          class="menu-link-btn"
          @click="closeOffcanvasAndNavigate('search')"
        >
          <i class="bi bi-search me-3 bg-transparent"></i>
          <span>Buscar produtos</span>
        </button>

        <button 
          class="menu-link-btn"
          data-bs-toggle="offcanvas"
          data-bs-target="#cartCanvas"
          @click="closeOffcanvas"
        >
          <i class="bi bi-cart3 me-3"></i>
          <span>Carrinho</span>
          <span v-if="cartStore.totalItems > 0" class="cart-badge-mobile ms-auto">
            {{ cartStore.totalItems }}
          </span>
        </button>
      </div>
    </div>

    <!-- ÁREA DO CLIENTE -->
    <div class="menu-section">
      <div class="section-title mb-2">
        <i class="bi bi-person-badge me-2"></i>
        <span>Área do Cliente</span>
      </div>
      <div class="menu-links d-flex flex-column gap-2">
        <button 
          class="menu-link-btn"
          @click.stop="openAddressModal"
        >
          <i class="bi bi-geo-alt me-3"></i>
          <span>Meus Endereços</span>
        </button>

        <button 
          class="menu-link-btn"
          @click.stop="openProfileModal"
        >
          <i class="bi bi-person-circle me-3"></i>
          <span>Meu Perfil</span>
        </button>

        <button 
          class="menu-link-btn"
          @click.stop="openOrderHistoryMobile"
        >
          <i class="bi bi-bag-check me-3"></i>
          <span>Meus Pedidos</span>
        </button>

        <button 
          class="menu-link-btn"
          @click.stop="openLocationModal"
        >
          <i class="bi bi-truck me-3"></i>
          <span>Locais de entrega</span>
        </button>
      </div>
    </div>

    <!-- CONTATO -->
    <div class="menu-section">
      <div class="section-title mb-2">
        <i class="bi bi-headset me-2"></i>
        <span>Atendimento</span>
      </div>
      <div class="menu-links d-flex flex-column gap-2">
        <a href="tel:+5585999999999" class="menu-link-btn">
          <i class="bi bi-telephone me-3"></i>
          <span>(85) 99999-9999</span>
        </a>
        <a href="https://wa.me/5585999999999" target="_blank" class="menu-link-btn">
          <i class="bi bi-whatsapp me-3"></i>
          <span>WhatsApp</span>
        </a>
        <a href="mailto:contato@oxenteacai.com.br" class="menu-link-btn">
          <i class="bi bi-envelope me-3"></i>
          <span>contato@oxenteacai.com.br</span>
        </a>
      </div>
    </div>

    <!-- REDES SOCIAIS -->
    <div class="menu-section">
      <div class="section-title mb-2">
        <i class="bi bi-share me-2"></i>
        <span>Redes Sociais</span>
      </div>
      <div class="social-icons d-flex gap-3 justify-content-center">
        <a href="#" target="_blank" class="social-icon">
          <i class="bi bi-instagram fs-4"></i>
        </a>
        <a href="#" target="_blank" class="social-icon">
          <i class="bi bi-facebook fs-4"></i>
        </a>
        <a href="#" target="_blank" class="social-icon">
          <i class="bi bi-tiktok fs-4"></i>
        </a>
        <a href="#" target="_blank" class="social-icon">
          <i class="bi bi-youtube fs-4"></i>
        </a>
      </div>
    </div>

    <!-- LOGIN/LOGOUT -->
    <div class="mt-2">
      <button 
        v-if="userStore.isLogged"
        class="btn-logout-mobile w-100"
        @click.stop="logoutMobile"
      >
        <i class="bi bi-box-arrow-right me-2"></i>
        Sair da conta
      </button>

      <button 
        v-else
        class="btn-login-mobile w-100"
        @click.stop="openLoginModal"
      >
        <i class="bi bi-box-arrow-in-right me-2"></i>
        Entrar na minha conta
      </button>
    </div>

  </div>
</div>

  <!-- Modal -->
  <IdentifyModal
      v-model="showModal"
      @submit="handleIdentify"
  />
  <!-- Modal -->
  <DeliveryLocations v-model:modelValue="showModalLocation"/>

  <!-- Componente do perfil -->
  <ProfileModal v-model="showProfileModal" @profile-updated="handleProfileUpdate" />

  <!-- OrderHistoryModal com verificação de userId -->
  <OrderHistoryModal
    v-model="showOrderHistoryModal" 
    :user-id="userStore.userId"
    @reorder="handleReorder"
  />

  <AddressModal v-model="showAddressModal" />
</template>

<script setup>
  import { ref, onMounted, computed, nextTick } from 'vue'
  import { useCartStore } from '@/stores/useCartStore'
  import { useUserStore } from '@/stores/useUserStore'
  import { useToast } from 'vue-toastification'
  import IdentifyModal from './IdentifyModal.vue'
  import AddressModal from '@/components/AddressModal.vue'
  import DeliveryLocations from '@/components/DeliveryLocations.vue'
  import OrderHistoryModal from '@/components/OrderHistoryModal.vue'
  import ProfileModal from '@/components/Profile.vue'

  const toast = useToast()
  const cartStore = useCartStore()
  const userStore = useUserStore()

  // estado dos modais
  const showProfileModal = ref(false)
  const showModalLocation = ref(false)
  const showModal = ref(false)
  const showAddressModal = ref(false)
  const showOrderHistoryModal = ref(false)

  // Computed para verificar se o usuário está logado e tem ID
  const isUserReady = computed(() => {
    return userStore.isLogged && userStore.userId
  })

  // ========== FUNÇÃO HANDLE REORDER ==========
  const handleReorder = (order) => {
    console.log('🎯 Reordenando pedido completo:', order.id)
    
    if (!order || !order.items || order.items.length === 0) {
      toast.error('Erro ao reordenar: pedido inválido')
      return
    }
    
    order.items.forEach((originalItem, index) => {
      if (originalItem.isCombo) {
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
        
        for (let i = 0; i < (comboItem.quantity || 1); i++) {
          const comboCopy = JSON.parse(JSON.stringify(comboItem))
          cartStore.add(comboCopy)
        }
      } else {
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
        
        for (let i = 0; i < simpleItem.quantity; i++) {
          const itemCopy = JSON.parse(JSON.stringify(simpleItem))
          cartStore.add(itemCopy)
        }
      }
    })
    
    const totalItens = order.items.reduce((sum, item) => sum + (item.quantity || 1), 0)
    toast.success(`${totalItens} item(ns) adicionado(s) ao carrinho!`, {
      timeout: 3000
    })
    
    showOrderHistoryModal.value = false
  }

  const rebuildSelectionsFromDetails = (item) => {
    if (!item.comboDetails) return null
    const selections = {}
    
    if (item.comboDetails.acompanhamento && item.comboItems) {
      const acompanhamentoItem = item.comboItems.find(ci => ci.id === 'acompanhamento')
      if (acompanhamentoItem && acompanhamentoItem.options) {
        const choice = acompanhamentoItem.options.choices.find(c => c.name === item.comboDetails.acompanhamento)
        if (choice) {
          selections.acompanhamento = { choice, quantity: 1, price: choice.price || 0 }
        }
      }
    }
    
    if (item.comboDetails.bebida && item.comboItems) {
      const bebidaItem = item.comboItems.find(ci => ci.id === 'bebida')
      if (bebidaItem && bebidaItem.options) {
        const choice = bebidaItem.options.choices.find(c => c.name === item.comboDetails.bebida)
        if (choice) {
          selections.bebida = { choice, quantity: 1, price: choice.price || 0 }
        }
      }
    }
    
    return Object.keys(selections).length > 0 ? selections : null
  }

  // Função para fechar o offcanvas CORRETAMENTE
  const closeOffcanvas = () => {
    const offcanvasElement = document.getElementById('mobileMenu')
    if (!offcanvasElement) return
    
    // Verifica se tem uma instância do Bootstrap
    if (window.bootstrap && window.bootstrap.Offcanvas) {
      const offcanvas = window.bootstrap.Offcanvas.getInstance(offcanvasElement)
      if (offcanvas) {
        offcanvas.hide()
      }
    }
    
    // Remove qualquer backdrop remanescente
    const backdrop = document.querySelector('.offcanvas-backdrop')
    if (backdrop) {
      backdrop.remove()
    }
    
    // Restaura o body
    document.body.classList.remove('offcanvas-open')
    document.body.style.overflow = ''
    document.body.style.paddingRight = ''
  }

  // Abrir modal de endereços - VERSÃO CORRIGIDA
  const openAddressModal = () => {
    // Fecha o offcanvas primeiro
    closeOffcanvas()
    // Pequeno delay para garantir que fechou
    setTimeout(() => {
      showAddressModal.value = true
    }, 150)
  }

  // Abrir modal de perfil
  const openProfileModal = () => {
    closeOffcanvas()
    setTimeout(() => {
      showProfileModal.value = true
    }, 150)
  }

  // Abrir modal de locais de entrega
  const openLocationModal = () => {
    closeOffcanvas()
    setTimeout(() => {
      showModalLocation.value = true
    }, 150)
  }

  // Abrir modal de login
  const openLoginModal = () => {
    closeOffcanvas()
    setTimeout(() => {
      showModal.value = true
    }, 150)
  }

  // Abrir histórico de pedidos
  const openOrderHistoryMobile = () => {
    closeOffcanvas()
    
    setTimeout(() => {
      if (!userStore.isLogged) {
        toast.warning('Faça login para ver seus pedidos!', { timeout: 3000 })
        showModal.value = true
        return
      }
      
      if (!userStore.userId) {
        toast.error('Erro ao carregar dados do usuário. Por favor, faça login novamente.', { timeout: 4000 })
        userStore.logout()
        showModal.value = true
        return
      }
      
      showOrderHistoryModal.value = true
    }, 150)
  }

  // Navegação
  const closeOffcanvasAndNavigate = (page) => {
    closeOffcanvas()
    setTimeout(() => {
      window.dispatchEvent(new CustomEvent('mobile-navigation', { detail: { page } }))
    }, 200)
  }

  // Logout mobile
  const logoutMobile = () => {
    closeOffcanvas()
    setTimeout(() => {
      logout()
    }, 150)
  }

  const handleProfileUpdate = (updatedProfile) => {
    console.log('Perfil atualizado:', updatedProfile)
    toast.success('Perfil atualizado com sucesso!', { timeout: 3000 })
  }

  const openOrderHistory = () => {
    if (!userStore.isLogged) {
      toast.warning('Faça login para ver seus pedidos!', { timeout: 3000 })
      showModal.value = true
      return
    }
    
    if (!userStore.userId) {
      toast.error('Erro ao carregar dados do usuário. Por favor, faça login novamente.', { timeout: 4000 })
      userStore.logout()
      showModal.value = true
      return
    }
    
    showOrderHistoryModal.value = true
  }

  const handleIdentify = ({ whatsapp: wpp, fullName: name }) => {
    const userData = {
      id: Date.now(),
      fullName: name,
      whatsapp: wpp,
      email: ''
    }
    
    userStore.login(userData)
    showModal.value = false
    
    toast.success(`Bem-vindo(a), ${name}! Login realizado com sucesso!`, { timeout: 4000 })
  }

  onMounted(() => {
    userStore.loadUserFromStorage()
  })

  const logout = () => {
    const userName = userStore.fullName || 'Usuário'
    userStore.logout()
    
    if (showOrderHistoryModal.value) {
      showOrderHistoryModal.value = false
    }
    
    toast.info(`Até mais, ${userName}! Você saiu da sua conta.`, { timeout: 4000 })
  }
</script>

<style scoped>
  .reset-button {
    all: unset;
    cursor: pointer;
  }
  .image{
      width: 210px;
      height: 210px;
      margin-top: -170px;
      background: #FFF;
      border: 7px solid rgba(0, 0, 0, 0.10);
  }
  .logo {
      width: 195px;
      height: 100%;
      object-fit: contain;
  }

  .status-dot {
      width: 8px;
      height: 8px;
      background: #28a745;
      border-radius: 50%;
  }
  .btn-reset {
    all: unset;
    cursor: pointer;
  }

  .input-search {
    border-color: var(--primary);
    width: 100%;
  }

  .height-35 {
    height: 35px;
  }

  .search-wrapper {
    min-width: 180px;
    max-width: 360px;
    width: 100%;
  }

  .search-icon {
    border-radius: 0 5px 5px 0;
    width: 45px;
  }

  .horous-day {
    font-size: clamp(0.75rem, 0.85vw, 0.9rem);
  }

  .horous-day i {
    font-style: normal;
    font-size: 0.75rem;
  }

  .number-car {
    height: 16px;
    width: 16px;
    font-size: clamp(0.5rem, 0.7vw, 0.7rem);
    top: 6px;
  }

  .svg-car {
    width: 22px;
  }

  .points {
    height: 6px;
    width: 6px;
    background: var(--primary);
  }
  .top-bar{
    z-index: 12;
  }

  /* ================= ESTILOS DO MENU MOBILE ================= */
  .offcanvas-header {
    background: var(--bg-ouro);
  }

  .offcanvas-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
  }

  .user-info-mobile i {
    color: var(--bg-ccc);
  }

  .section-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--primary);
    border-left: 3px solid #FFC400;
    padding-left: 8px;
  }

  .menu-link-btn {
    all: unset;
    cursor: pointer;
    display: flex;
    align-items: center;
    padding: 12px 16px;
    background: #f8f9fa;
    border-radius: 12px;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    color: #1f2937;
  }

  .menu-link-btn:hover {
    background: #FFF1C3;
    transform: translateX(5px);
  }

  .menu-link-btn i {
    color: var(--primary);
    font-size: 1.1rem;
    width: 24px;
  }

  .cart-badge-mobile {
    background: #FFC400;
    color: #000;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
  }

  .social-icons {
    margin-top: 8px;
  }

  .social-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border-radius: 50%;
    transition: all 0.3s ease;
    color: var(--primary);
    text-decoration: none;
  }

  .social-icon:hover {
    background: #FFC400;
    color: #000;
    transform: translateY(-3px);
  }

  .btn-login-mobile {
    background: var(--primary);
    color: white;
    border: none;
    padding: 12px 16px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .btn-login-mobile:hover {
    background: #8a1f74;
    transform: translateY(-2px);
  }

  .btn-logout-mobile {
    background: #dc2626;
    color: white;
    border: none;
    padding: 12px 16px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .btn-logout-mobile:hover {
    background: #b91c1c;
    transform: translateY(-2px);
  }

  .menu-section {
    margin-bottom: 8px;
  }

  @media (min-width: 1400px) {
    .search-wrapper {
      max-width: 420px;
    }
    .top-bar {
      gap: 20px;
    }
  }

  @media (max-width: 992px) {
    .search-wrapper {
      max-width: 100%;
    }
  }

  @media (max-width: 768px) {
    .top-bar {
      gap: 10px;
    }
    .right-container {
      border-left: none !important;
      padding-left: 0 !important;
      justify-content: space-between;
    }
    .search-wrapper {
      min-width: 100%;
      order: 3;
    }
  }
  @media (max-width: 680px) {
    .d-flex.justify-content-center.justify-content-md-start.align-items-center.mt-0.mt-md-2 small{
      font-size: 0.75rem !important;
    }
    .font-12, .form-control.p-0.ps-3.height-35.input-search{
      font-size: 0.75rem;
    }
    .image {
        width: 140px;
        height: 140px;
        margin-top: -100px;
        border: 5px solid rgba(0, 0, 0, 0.10);
    }
    .logo {
        width: 120px;
    }
  }

</style>