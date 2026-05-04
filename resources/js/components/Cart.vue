<template>
    <div class="offcanvas offcanvas-end cart-canvas" tabindex="-1" id="cartCanvas">
  
        <!-- Header -->
        <div class="cart-header d-flex justify-content-between align-items-center">
            <span class="my-cart">
                <svg class="me-2" width="26" height="23" viewBox="0 0 26 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14.184 4.46937H8.78054V6.22461H14.184V4.46937ZM17.7862 4.24996V10.2836L6.48409 12.719L4.2777 2.05591H0.900568C0.661723 2.05591 0.43266 2.17149 0.26377 2.37722C0.0948811 2.58295 0 2.86199 0 3.15294C0 3.44388 0.0948811 3.72292 0.26377 3.92865C0.43266 4.13438 0.661723 4.24996 0.900568 4.24996H2.89983L5.80867 18.5113H17.7862V16.3173H7.21355L6.91637 14.8582L19.5874 12.1266V4.24996H17.7862ZM6.07884 19.6083C5.81166 19.6083 5.55049 19.7049 5.32834 19.8857C5.1062 20.0665 4.93305 20.3235 4.83081 20.6242C4.72857 20.9248 4.70182 21.2557 4.75394 21.5749C4.80606 21.8941 4.93472 22.1873 5.12364 22.4175C5.31256 22.6476 5.55326 22.8043 5.8153 22.8678C6.07734 22.9313 6.34895 22.8987 6.59579 22.7742C6.84262 22.6496 7.0536 22.4387 7.20203 22.1681C7.35046 21.8975 7.42969 21.5793 7.42969 21.2539C7.42969 20.8175 7.28737 20.3989 7.03403 20.0903C6.7807 19.7817 6.43711 19.6083 6.07884 19.6083ZM16.8857 19.6083C16.6185 19.6083 16.3573 19.7049 16.1352 19.8857C15.913 20.0665 15.7399 20.3235 15.6376 20.6242C15.5354 20.9248 15.5086 21.2557 15.5608 21.5749C15.6129 21.8941 15.7415 22.1873 15.9305 22.4175C16.1194 22.6476 16.3601 22.8043 16.6221 22.8678C16.8842 22.9313 17.1558 22.8987 17.4026 22.7742C17.6494 22.6496 17.8604 22.4387 18.0089 22.1681C18.1573 21.8975 18.2365 21.5793 18.2365 21.2539C18.2365 20.8175 18.0942 20.3989 17.8409 20.0903C17.5875 19.7817 17.2439 19.6083 16.8857 19.6083Z" fill="#595959"/>
                    <circle cx="21.5" cy="4.5" r="4.5" fill="#595959"/>
                    <path d="M20.5423 5.23H19.0123V4.31H20.5423V2.57H21.4623V4.31H22.9923V5.23H21.4623V6.97H20.5423V5.23Z" fill="white"/>
                    </svg>
                    Meu Carrinho
                </span>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>

        <!-- Body -->
        <div class="offcanvas-body p-3">

            <!-- Identifique-se -->
            <div
                class="identify-box d-flex align-items-center justify-content-between mb-3 border p-2 rounded"
                @click="showModal = true"
            >
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <div class="icon-user rounded-3 d-flex justify-content-center align-items-center p-3">
                        <svg width="20" height="23" viewBox="0 0 20 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.2963 12.6129C13.6296 12.4645 16.2963 9.68226 16.2963 6.30645C16.2963 2.81936 13.4815 0 10 0C6.51852 0 3.7037 2.81936 3.7037 6.30645C3.7037 9.68226 6.37037 12.4274 9.7037 12.6129C4.22222 12.7984 0 17.1758 0 23H1.48148C1.48148 17.8065 5.14815 14.0968 10 14.0968C14.8519 14.0968 18.5185 17.8065 18.5185 23H20C20 17.1758 15.7778 12.7984 10.2963 12.6129ZM5.18518 6.34355C5.18518 3.67258 7.33333 1.52097 10 1.52097C12.6667 1.52097 14.8148 3.67258 14.8148 6.34355C14.8148 9.01452 12.6667 11.1661 10 11.1661C7.33333 11.1661 5.18518 8.97742 5.18518 6.34355Z" fill="#595959"/>
                        </svg>
                    </div>
                    <div class="text-start">
                        <strong>{{ userStore.isLogged ? `Olá, ${userStore.fullName}!` : 'Identifique-se aqui' }}</strong>
                        <div class="small text-muted">
                            {{ userStore.isLogged ? 'Você já está identificado' : 'Antes de finalizar o pedido, simples e rápido!' }}
                        </div>
                    </div>
                </div>
                <span class="border rounded-pill d-flex justify-content-center align-items-center arrow">›</span>
            </div>

            <!-- Address info - Só mostra se estiver logado -->
            <div v-if="userStore.isLogged" class="d-flex align-items-center justify-content-between mb-3 rounded my-2">
                <div
                    class="contorno p-2 d-flex align-items-start justify-content-between rounded-3 w-100"
                    :class="{ 'not-address': !selectedDeliveryMethod || (selectedDeliveryMethod?.value === 'delivery' && !selectedAddress) }"
                >
                    <!-- LEFT ICON -->
                    <div class="d-flex align-items-start w-100">
                        <div class="icon-address rounded-3 d-flex justify-content-center align-items-center p-3 me-2"
                            :class="{ 'not-address': !selectedDeliveryMethod || (selectedDeliveryMethod?.value === 'delivery' && !selectedAddress) }">
                            <svg width="23" height="21" viewBox="0 0 23 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.3242 1.35555H20.6327C20.2199 1.35555 19.8346 1.47081 19.4892 1.66038C19.3675 1.47748 19.1715 1.35555 18.9413 1.35555H14.2057C13.8338 1.35555 13.5294 1.66038 13.5294 2.03283C13.5294 2.40529 13.8338 2.71012 14.2057 2.71012H14.9021C14.4759 3.27881 14.2057 3.97707 14.2057 4.742V11.5158C14.2057 11.8883 13.9013 12.1931 13.5294 12.1931H11.5002C11.1283 12.1931 10.8238 11.8883 10.8238 11.5158V0.678238C10.8238 0.305784 10.5194 0.000952597 10.1475 0.000952597L3.38278 0C3.01083 0 2.70641 0.304831 2.70641 0.677286V7.45112C2.70641 7.55971 2.74065 7.66069 2.78727 7.75595C1.11676 8.84668 0 10.7299 0 12.8705V16.9353C0 17.3077 0.30442 17.6125 0.676372 17.6125H2.02914C2.02914 19.0414 2.93575 20.356 4.23429 20.8028C4.62623 20.9381 5.01913 20.999 5.41105 20.999C6.11502 20.999 6.79141 20.7894 7.37266 20.3694C8.25928 19.733 8.79296 18.7033 8.79296 17.6125H15.5645C15.5645 19.1566 16.5586 20.4845 18.0198 20.8781C18.3242 20.959 18.6353 21 18.9397 21C19.8121 21 20.6511 20.6685 21.2875 20.0512C22.1532 19.211 22.5052 17.9984 22.2274 16.8C21.9364 15.5873 20.9689 14.6186 19.7655 14.327C19.718 14.3136 19.6638 14.3136 19.6171 14.3003V5.86039C19.9282 6.009 20.2669 6.09759 20.6322 6.09759H22.3236C22.6956 6.09759 23 5.79276 23 5.4203V2.03278C23 1.66032 22.6956 1.35549 22.3236 1.35549L22.3242 1.35555ZM4.05896 6.77495V1.35555H6.0881V2.71014C6.0881 3.0826 6.39252 3.38743 6.76447 3.38743C7.13642 3.38743 7.44084 3.0826 7.44084 2.71014V1.35555H9.46998V6.77495H4.05896ZM6.58856 19.2655C6.04061 19.6579 5.35091 19.7465 4.67452 19.516C3.90967 19.2521 3.38263 18.4729 3.38263 17.6127H7.44189C7.44189 18.27 7.12415 18.8863 6.58951 19.2654L6.58856 19.2655ZM8.02981 16.2581H1.35254V12.8706C1.35254 10.2556 3.47683 8.12865 6.0881 8.12865H9.47106V11.5162C9.47106 12.6336 10.3843 13.5481 11.5002 13.5481H13.5293C14.6452 13.5481 15.5585 12.6336 15.5585 11.5162V4.74234C15.5585 3.62495 16.4717 2.71046 17.5876 2.71046H18.264V14.294C18.264 14.294 18.2231 14.3074 18.2031 14.3074C18.0947 14.334 17.9862 14.3617 17.8854 14.395C17.8511 14.4084 17.8178 14.415 17.7836 14.4293C17.6751 14.4703 17.5667 14.5169 17.4658 14.5646C17.4392 14.5779 17.405 14.5913 17.3783 14.6055C17.2765 14.6598 17.1757 14.7208 17.0805 14.7884C17.0539 14.8084 17.0263 14.8227 16.9997 14.8361C16.8779 14.917 16.7695 15.0123 16.661 15.1142C16.5868 15.1819 16.505 15.2562 16.4517 15.3238C16.3366 15.4524 16.2348 15.5876 16.1407 15.7239C16.114 15.7648 16.0931 15.8048 16.0665 15.8391C15.9989 15.9544 15.9314 16.0697 15.8771 16.1916C15.8638 16.2116 15.8505 16.2326 15.8429 16.2592H8.02978L8.02981 16.2581ZM20.9172 17.1117C21.0866 17.8366 20.8763 18.5682 20.356 19.0759C19.828 19.5836 19.1116 19.7665 18.381 19.5703C17.4744 19.3331 16.8856 18.5072 16.9198 17.5241C16.9198 17.3822 16.9465 17.2536 16.9874 17.1107C17.0749 16.7792 17.2309 16.4877 17.4678 16.2305C17.4944 16.1962 17.5287 16.1695 17.5762 16.1219C17.9482 15.7761 18.4353 15.5865 18.9499 15.5865C19.1126 15.5865 19.2886 15.6065 19.4636 15.6475C20.1809 15.8171 20.7489 16.3924 20.9249 17.1107L20.9172 17.1117ZM21.6479 4.7421H20.6328C20.0715 4.7421 19.6177 4.28866 19.6177 3.72567C19.6177 3.16363 20.0706 2.70924 20.6328 2.70924H21.6479V4.7421Z" fill="white"/>
                            </svg>
                        </div>

                        <!-- TEXT CONTENT -->
                        <div class="text-content flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="info">
                                    <h5 class="mb-0 fw-medium address-title d-flex justify-content-start align-items-end">
                                        {{ selectedDeliveryMethod ? selectedDeliveryMethod.label : 'Definir forma de entrega' }} - 
                                        <span 
                                            class="ms-1 text-primary d-flex pointer"
                                            :class="{ 'd-none': !selectedDeliveryMethod }"
                                            @click="openDeliveryMethodModal"
                                        >Alterar</span>
                                    </h5>

                                    <small class="finish text-muted fw-medium">
                                        {{ getDeliveryMethodText() }} 
                                    </small>
                                </div>
                                <!-- RIGHT CHECK / EDIT BUTTON -->
                                <div class="d-flex align-items-center justify-content-center gap-0">
                                    <button 
                                        @click="selectedDeliveryMethod?.value === 'delivery' && !selectedAddress ? openAddressModal() : openDeliveryMethodModal()" 
                                        class="btn btn-sm p-0 border-0 bg-transparent"
                                        style="line-height: 0;"
                                    >
                                        <svg v-if="!selectedDeliveryMethod || (selectedDeliveryMethod?.value === 'delivery' && !selectedAddress)" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="0.5" y="0.5" width="27" height="27" rx="13.5" fill="#E9ECEF"/>
                                            <rect x="0.5" y="0.5" width="27" height="27" rx="13.5" stroke="#DEE2E6"/>
                                            <path d="M14.4365 12.9808H17.0573V14.8672H14.4365V17.4016H12.5501V14.8672H9.92925V12.9808H12.5501V10.4032H14.4365V12.9808Z" fill="#6C757D"/>
                                        </svg>
                                    </button>
                                    <svg v-if="selectedDeliveryMethod && !(selectedDeliveryMethod?.value === 'delivery' && !selectedAddress)" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="28" height="28" rx="14" fill="var(--bg-orange)"/>
                                        <path d="M11.8656 18.775L7.19687 14.1063C6.91563 13.825 6.91563 13.3469 7.19687 13.0656L8.20937 12.0531C8.49062 11.7719 8.94062 11.7719 9.22187 12.0531L12.4 15.2031L19.15 8.45313C19.4312 8.17188 19.8812 8.17188 20.1625 8.45313L21.175 9.46563C21.4562 9.74688 21.4562 10.225 21.175 10.5063L12.9062 18.775C12.625 19.0562 12.1469 19.0562 11.8656 18.775Z" fill="white"/>
                                    </svg>
                                </div>
                            </div>

                            <div v-if="selectedDeliveryMethod?.value === 'delivery' && selectedAddress" class="d-flex text-muted small mt-2">
                                <svg width="11" height="16" viewBox="0 0 11 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.451 12.0711C3.55472 12.0469 3.61938 11.9425 3.59513 11.8387C3.57021 11.7344 3.46447 11.6697 3.36276 11.6946C2.18408 11.9728 1.50781 12.4537 1.50781 13.0141C1.50781 14.0196 3.56415 14.5625 5.4991 14.5625C7.43422 14.5625 9.49038 14.0197 9.49038 13.0141C9.49038 12.4537 8.81415 11.9728 7.63544 11.6946C7.53239 11.6697 7.42732 11.7344 7.40306 11.8387C7.37882 11.9425 7.4428 12.0469 7.5472 12.0711C8.49284 12.2941 9.10373 12.6645 9.10373 13.0141C9.10373 13.5637 7.62331 14.1759 5.49971 14.1759C3.37544 14.1759 1.89569 13.5637 1.89569 13.0141C1.89434 12.6645 2.50537 12.2947 3.451 12.0711Z" fill="#595959"/>
                                    <path d="M8.17916 11.0271C8.07543 11.0035 7.97104 11.0668 7.94611 11.1705C7.92119 11.2743 7.98585 11.3787 8.08957 11.4036C9.62253 11.768 10.6127 12.4435 10.6127 13.1251C10.6127 14.2129 8.27148 15.1316 5.49993 15.1316C2.72837 15.1316 0.387192 14.213 0.387192 13.1251C0.387192 12.4435 1.37729 11.7673 2.91028 11.4036C3.014 11.3787 3.07866 11.2749 3.05374 11.1705C3.02882 11.0668 2.92375 11.0035 2.82069 11.0271C1.05472 11.4467 0 12.2314 0 13.1251C0 14.4668 2.41601 15.5182 5.5 15.5182C8.58416 15.5182 11 14.4668 11 13.1251C10.9993 12.2314 9.9451 11.4467 8.17916 11.0271Z" fill="#595959"/>
                                    <path d="M3.81905 10.2363L5.50019 13.1494L7.18268 10.2363C9.32316 9.51632 10.7565 7.52461 10.7565 5.25619C10.7565 2.35791 8.39845 0 5.50033 0C2.60222 0 0.244141 2.35808 0.244141 5.25619C0.244141 7.52461 1.67795 9.51629 3.81905 10.2363ZM5.50019 0.387266C8.18485 0.387266 10.3691 2.57154 10.3691 5.25619C10.3691 7.38115 9.01262 9.2442 6.9927 9.89217L6.92198 9.91507L5.50015 12.3768L4.08034 9.91576L4.00894 9.89219C1.9883 9.24425 0.630455 7.38134 0.630455 5.25621C0.630455 2.57156 2.81485 0.387266 5.50019 0.387266Z" fill="#595959"/>
                                    <path d="M5.50096 8.56332C7.3647 8.56332 8.88083 7.04719 8.88083 5.18346C8.88083 3.31972 7.3647 1.80359 5.50096 1.80359C3.63722 1.80359 2.12109 3.31972 2.12109 5.18346C2.12109 7.04719 3.63722 8.56332 5.50096 8.56332ZM5.50096 2.19103C7.15112 2.19103 8.49339 3.53339 8.49339 5.18415C8.49339 6.8343 7.15103 8.17658 5.50096 8.17658C3.85089 8.17658 2.50784 6.83422 2.50784 5.18415C2.50784 3.53332 3.8502 2.19103 5.50096 2.19103Z" fill="#595959"/>
                                </svg>

                                <p class="ms-2 mb-0 col-11">
                                    {{ formatAddress(selectedAddress) }} -
                                    <button 
                                        @click="openAddressModal" 
                                        class="btn btn-sm p-0 fw-medium border-0 bg-transparent text-primary"
                                        style="line-height: 0;"
                                    >
                                        Editar
                                    </button>
                                </p>
                            </div>

                            <div v-else-if="selectedDeliveryMethod?.value === 'delivery' && !selectedAddress" class="d-flex text-muted small mt-2">
                                <p class="mb-0">Clique no botão + para adicionar um endereço de entrega</p>
                            </div>

                            <div v-else-if="selectedDeliveryMethod?.value === 'pickup'" class="d-flex text-muted small mt-2">
                                <p class="mb-0">Retirada na loja selecionada</p>
                            </div>

                            <div v-else-if="selectedDeliveryMethod?.value === 'local'" class="d-flex text-muted small mt-2">
                                <p class="mb-0">Consumo no local selecionado</p>
                            </div>

                            <div v-else class="d-flex text-muted small mt-2">
                                <p class="mb-0">Clique no botão + para selecionar a forma de entrega</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment info -->
            <div v-if="userStore.isLogged" class="d-flex align-items-center justify-content-between mb-3 rounded my-2">
                <div
                    class="contorno p-2 d-flex align-items-start justify-content-between rounded-3 w-100"
                    :class="{ 'not-address': !selectedPaymentMethod }"
                >
                    <div class="d-flex align-items-start w-100">
                        <div class="icon-address rounded-3 d-flex justify-content-center align-items-center p-3 me-2"
                            :class="{ 'not-address': !selectedPaymentMethod }">
                            <svg width="23" height="21" viewBox="0 0 23 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1.63966 10.6922H11.1456C11.4757 10.6922 11.7441 10.9571 11.7441 11.285V11.5851C11.7441 12.3104 11.5047 12.9819 11.0876 13.5201C10.6695 14.0592 10.0756 14.4631 9.36764 14.6502L9.1834 14.6993C9.14505 14.7095 9.11419 14.728 9.09174 14.7502C9.0693 14.7743 9.0534 14.8067 9.04685 14.8438C9.04031 14.8808 9.04405 14.9179 9.05714 14.9494C9.06649 14.9781 9.08987 15.0049 9.12354 15.0281L11.1269 16.4388C11.2456 16.5222 11.371 16.5768 11.5 16.6019C11.6282 16.6269 11.7656 16.6222 11.9097 16.5889L16.3812 15.5496C17.0976 15.3829 17.6784 15.6709 18.045 16.1461C18.1956 16.3425 18.3069 16.5676 18.3723 16.8001C18.4387 17.0354 18.4621 17.2855 18.4359 17.5281C18.3723 18.1247 18.0263 18.6712 17.3248 18.9L11.1848 20.8915C10.9295 20.9739 10.6741 21.0091 10.4169 20.998C10.157 20.986 9.90443 20.9276 9.65845 20.822L4.01878 18.4173H1.63949C1.19057 18.4173 0.780937 18.2348 0.483522 17.9402L0.481652 17.9384C0.184238 17.6429 0 17.2372 0 16.7935V12.3158C0 11.8693 0.184245 11.4618 0.481652 11.1681L0.522805 11.1311C0.816484 10.8588 1.21115 10.6911 1.64044 10.6911L1.63966 10.6922ZM3.54197 11.8778H1.63966C1.5265 11.8778 1.42455 11.9195 1.34692 11.9862L1.32634 12.0066C1.24591 12.0862 1.19634 12.1955 1.19634 12.3159V16.7936C1.19634 16.915 1.24591 17.0252 1.32447 17.103L1.32634 17.1049C1.4049 17.1827 1.5162 17.2318 1.63872 17.2318H3.54103V11.8769L3.54197 11.8778ZM4.7391 11.8778V17.4356L10.131 19.7338C10.2497 19.7847 10.362 19.8116 10.4686 19.8171C10.579 19.8218 10.6949 19.8051 10.8156 19.7662L16.9556 17.7747C17.1399 17.7145 17.2306 17.5663 17.2484 17.4032C17.2587 17.3115 17.2484 17.2143 17.2222 17.1198C17.1951 17.0225 17.153 16.9345 17.0987 16.8641C16.9977 16.7372 16.8425 16.6585 16.6526 16.703L12.1811 17.7423C11.8725 17.8145 11.5666 17.821 11.2711 17.7636C10.9765 17.7071 10.6959 17.5848 10.435 17.4014L8.43166 15.9907C8.21281 15.836 8.04913 15.6294 7.95187 15.3932C7.85461 15.1607 7.82655 14.8995 7.87331 14.6346C7.92195 14.3687 8.04072 14.1335 8.21375 13.9482C8.38771 13.7611 8.61405 13.6231 8.87497 13.5545L9.05922 13.5054C9.50627 13.3869 9.88037 13.1359 10.1385 12.8015C10.3414 12.5393 10.4771 12.2225 10.5266 11.8761H4.73924L4.7391 11.8778ZM17.0032 0C18.6577 0 20.1559 0.665076 21.2408 1.74048C22.3285 2.81496 23 4.29982 23 5.94028C23 7.57981 22.3285 9.06469 21.2427 10.1401L21.2071 10.1725C20.125 11.2285 18.6398 11.8806 17.0023 11.8806C15.3469 11.8806 13.8477 11.2155 12.7619 10.1401C11.677 9.0656 11.0045 7.58074 11.0045 5.94028C11.0045 4.30262 11.6761 2.81776 12.7619 1.74143C13.8458 0.666006 15.345 0.00095331 17.0004 0.00095331L17.0032 0ZM20.3982 2.57688C19.5303 1.71729 18.3295 1.1856 17.0032 1.1856C15.6779 1.1856 14.478 1.71729 13.6091 2.57781C12.7402 3.43555 12.2034 4.6249 12.2034 5.93935C12.2034 7.25191 12.7402 8.44035 13.6091 9.3009C14.4779 10.1614 15.6779 10.6931 17.0032 10.6931C18.3144 10.6931 19.504 10.1725 20.37 9.32961L20.3971 9.3009C21.266 8.44038 21.8028 7.25196 21.8028 5.93935C21.8028 4.62675 21.2662 3.43743 20.3982 2.57688ZM19.0374 4.36275C19.0374 4.68973 18.77 4.95557 18.4389 4.95557C18.1087 4.95557 17.8403 4.69065 17.8403 4.36275C17.8403 4.13396 17.7458 3.92553 17.5953 3.7764C17.4447 3.62727 17.2343 3.53372 17.0033 3.53372C16.7722 3.53372 16.5618 3.62728 16.4112 3.7764C16.2607 3.92553 16.1662 4.13395 16.1662 4.36275C16.1662 4.98429 16.6918 5.18623 17.2174 5.38722C18.1265 5.73552 19.0365 6.08471 19.0365 7.51399C19.0365 8.0679 18.8092 8.57087 18.4407 8.93584L18.4389 8.93769C18.2079 9.16648 17.9217 9.34062 17.6018 9.43881V9.73892C17.6018 10.0659 17.3343 10.3317 17.0033 10.3317C16.6731 10.3317 16.4047 10.0668 16.4047 9.73892V9.43974C16.0839 9.34248 15.7968 9.16742 15.5648 8.93861C15.1963 8.57365 14.9691 8.07067 14.9691 7.51399C14.9691 7.18701 15.2366 6.92116 15.5676 6.92116C15.8978 6.92116 16.1662 7.18609 16.1662 7.51399C16.1662 7.74278 16.2607 7.95121 16.4112 8.10034C16.5618 8.24947 16.7722 8.34302 17.0033 8.34302C17.2361 8.34302 17.4447 8.25039 17.5962 8.10219C17.7468 7.95305 17.8394 7.74464 17.8394 7.51491C17.8394 6.89337 17.3128 6.69143 16.7872 6.48952C15.8781 6.14122 14.9681 5.79203 14.9681 4.36275C14.9681 3.80697 15.1954 3.30307 15.5639 2.93813C15.7949 2.70934 16.083 2.5352 16.4038 2.437V2.13782C16.4038 1.81084 16.6712 1.54499 17.0023 1.54499C17.3325 1.54499 17.6009 1.80992 17.6009 2.13782V2.437C17.9217 2.53519 18.2088 2.70932 18.4407 2.93813C18.8083 3.30217 19.0365 3.80699 19.0365 4.36275L19.0374 4.36275Z" fill="#FFF"/>
                            </svg>
                        </div>

                        <div class="text-content flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="info">
                                    <h5 class="mb-0 fw-bold address-title d-inline-flex gap-1 flex-wrap">
                                        {{ selectedPaymentMethod ? getPaymentMethodText() : 'Definir forma de pagamento' }} -
                                        
                                        <span 
                                            class="ms-0 text-primary fw-medium d-flex pointer"
                                            :class="{ 'd-none': !selectedPaymentMethod }"
                                            @click="openPaymentMethodModal"
                                        >Alterar</span>
                                    </h5>

                                    <small class="finish text-muted fw-medium">
                                        {{ getPaymentMethodDescription() }}
                                    </small>
                                </div>

                                <div class="d-flex align-items-center justify-content-center gap-0">
                                    <button 
                                        @click="openPaymentMethodModal" 
                                        class="btn btn-sm p-0 border-0 bg-transparent"
                                        style="line-height: 0;"
                                    >
                                        <svg v-if="!selectedPaymentMethod" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="0.5" y="0.5" width="27" height="27" rx="13.5" fill="#E9ECEF"/>
                                            <rect x="0.5" y="0.5" width="27" height="27" rx="13.5" stroke="#DEE2E6"/>
                                            <path d="M14.4365 12.9808H17.0573V14.8672H14.4365V17.4016H12.5501V14.8672H9.92925V12.9808H12.5501V10.4032H14.4365V12.9808Z" fill="#6C757D"/>
                                        </svg>
                                    </button>
                                    <svg v-if="selectedPaymentMethod" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="28" height="28" rx="14" fill="var(--bg-orange)"/>
                                        <path d="M11.8656 18.775L7.19687 14.1063C6.91563 13.825 6.91563 13.3469 7.19687 13.0656L8.20937 12.0531C8.49062 11.7719 8.94062 11.7719 9.22187 12.0531L12.4 15.2031L19.15 8.45313C19.4312 8.17188 19.8812 8.17188 20.1625 8.45313L21.175 9.46563C21.4562 9.74688 21.4562 10.225 21.175 10.5063L12.9062 18.775C12.625 19.0562 12.1469 19.0562 11.8656 18.775Z" fill="white"/>
                                    </svg>
                                </div>
                            </div>

                            <div v-if="selectedPaymentMethod === 'card'" class="d-flex text-muted small mt-2">
                                <p class="mb-0">✅ Pagamento com cartão (débito ou crédito) presencialmente ou via link de pagamento</p>
                            </div>

                            <div v-else-if="selectedPaymentMethod === 'pix'" class="d-flex text-muted small mt-2">
                                <p class="mb-0">✅ Você receberá um código PIX para pagamento via WhatsApp</p>
                            </div>

                            <div v-else-if="selectedPaymentMethod === 'cash'" class="d-flex text-muted small mt-2">
                                <p class="mb-0">✅ Pagamento em dinheiro, presencialmente em nosso balcão</p>
                            </div>

                            <div v-else class="d-flex text-muted small mt-2">
                                <p class="mb-0">Clique no botão + para selecionar a forma de pagamento</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Modal -->
            <AddressModal
                v-model="showAddressModal"
                @address-selected="handleAddressSelected"
            />
                            
            <!-- Badge de Warning -->
            <div v-if="!canConfirm" class="mb-2 text-center">
                <span class="badge bg-warning p-2 text-dark d-flex align-items-center justify-content-center gap-1">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Você precisa se cadastrar antes de confirmar o pedido!
                </span>
            </div>

            <IdentifyModal
                v-model="showModal"
                @submit="handleIdentify"
            />

            <!-- Item -->
            <div 
                v-for="item in cart.items" 
                :key="item.id + forceUpdate"
                class="cart-item d-flex mb-3"
                @click="openProductModal(item)"
            >   
                <div class="d-flex flex-column">
                    <img :src="item.image" class="item-img">
                    <span class="badge cashback my-2 small d-none">
                        {{ item.cashback }}% cashback
                    </span>
                </div>
                
                <div class="flex-grow-1 ms-2">
                    <div class="d-flex justify-content-between align-items-center mb-0">
                        <div class="fw-bold">{{ item.name }}</div>
                        <span class="me-2 bi bi-pencil-square"></span>
                    </div>
                    <div v-if="item.isReorder" class="mb-2">
                        <span class="badge is-reorder d-inline-flex align-items-center gap-1 px-2 py-1"
                            :title="`Repetido do pedido #${item.originalOrderId || 'anterior'} em ${formatReorderDate(item.reorderDate)}`">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 2L21 6L17 10" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M3 12V10C3 7.2 5 5 8 5H21" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M7 22L3 18L7 14" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M21 12V14C21 16.8 19 19 16 19H3" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Recompra
                        </span>
                    </div>
                    <p class="text-muted small mb-2">
                        {{ item.description }}
                    </p>
                    
                    <!-- Mostrar personalizações do combo -->
                    <div v-if="item.isCombo && item.itemSelections" class="mb-2">
                        <div class="small fw-semibold mb-1 d-flex align-items-center gap-1">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 4V20M20 12H4" stroke="#000" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            Itens do Combo
                        </div>
                        <div v-for="(selection, itemId) in item.itemSelections" :key="itemId" class="small mb-1">
                            <span class="text-muted">{{ getComboItemName(item, itemId) }}:</span>
                            <div v-if="Array.isArray(selection)">
                                <span v-for="(opt, idx) in selection" :key="idx" class="text-success ms-2">
                                    {{ opt.quantity }}x {{ opt.choice.name }}
                                </span>
                            </div>
                            <div v-else>
                                <span class="text-success ms-2">{{ selection.choice?.name || selection.selected }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mostrar addons do combo -->
                    <div v-if="item.isCombo && item.selectedAddons?.length" class="mb-2">
                        <div class="small fw-semibold mb-1 d-flex align-items-center gap-1">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 4V20M20 12H4" stroke="#000" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            Adicionais
                        </div>
                        <div v-for="addon in item.selectedAddons" :key="addon.id" class="small d-flex justify-content-between align-items-center ms-0">
                            <span class="text-muted">{{ addon.quantity }}x {{ addon.name }}</span>
                            <span class="text-success fw-semibold">+{{ formatPrice(addon.price * addon.quantity) }}</span>
                        </div>
                    </div>
                    
                    <!-- Mostrar adicionais selecionados (produtos normais) -->
                    <div v-if="!item.isCombo && getItemAditionalsList(item).length > 0" class="mb-2">
                        <div class="small fw-semibold mb-1 d-flex align-items-center gap-1">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 4V20M20 12H4" stroke="#000" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            Adicionais
                        </div>
                        <div v-for="ad in getItemAditionalsList(item)" :key="ad.name" class="small d-flex justify-content-between align-items-center ms-0">
                            <span class="text-muted">{{ ad.quantity }}x {{ ad.name }}</span>
                            <span class="text-success fw-semibold">+{{ formatPrice(ad.total) }}</span>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-0">
                        <div class="d-flex flex-column gap-0">
                            <strong>{{ formatPrice(getItemTotal(item)) }}</strong>
                            <div class="d-flex gap-1" v-if="item.oldPrice">
                                <div class="text-muted small text-decoration-line-through">
                                    {{ formatPrice(item.oldPrice) }}
                                </div>
                                <div class="percent rounded-1 text-white d-flex justify-content-center align-items-center px-1">
                                    {{ Math.round(((item.oldPrice - item.price) / item.oldPrice) * 100) }}%
                                </div>
                            </div>
                        </div>

                        <div class="qty-control d-flex justify-content-center align-items-center">
                            <button class="btn-minus" @click.stop="cart.decrease(item.id)">-</button>
                            <span class="mx-2">{{ item.quantity }}</span>
                            <button class="btn-plus" @click.stop="cart.increase(item.id)">+</button>
                        </div>
                        
                        <button 
                            @click.stop="cart.remove(item.id)" 
                            type="button" 
                            class="reset-button btn btn-red text-white rounded py-1 px-2"
                        >
                            Excluir
                        </button>    
                    </div>
                </div>
            </div>

            <!-- Totais -->
            <div class="cart-summary mt-3">
                <!-- Subtotal -->
                <div class="d-flex justify-content-between mb-2">
                    <span class="info-value">Sub Total:</span>
                    <strong>{{ formatPrice(cart.subTotal) }}</strong>
                </div>

                <!-- Desconto -->
                <div class="d-flex justify-content-between mb-2" v-if="cart.discount > 0">
                    <span class="info-value">Desconto:</span>
                    <strong>- {{ formatPrice(cart.discount) }}</strong>
                </div>
                
                <!-- Cashback -->
                <div class="d-flex justify-content-between d-none" v-if="cart.cashbackTotal > 0">
                    <span class="info-value">Cashback:</span>
                    <strong>+{{ formatPrice(cart.cashbackTotal) }}</strong>
                </div>

                <!-- Cupom -->
                <div class="mt-2 d-flex gap-2 justify-content-between align-items-center mb-4">
                    <span class="info-value">Cupom:</span>
                    <input type="text" class="form-control w-75" placeholder="Digite o código aqui">
                </div>
                
                <!-- Total -->
                <div class="d-flex justify-content-between">
                    <span class="info-value">Total:</span>
                    <strong>{{ formatPrice(cart.total) }}</strong>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-3 d-flex justify-content-end">
            <button 
                class="btn btn-confirm px-3 d-flex justify-content-center align-items-center"
                :class="{ 'opacity-50': !canConfirm }"
                @click="handleConfirm"
            >
                Confirmar pedido ›
            </button>
        </div>
    </div>

    <!-- Product Modal -->
    <ProductModal
        v-model:show="showProductModal"
        :product="selectedProduct"
    />

    <!-- Delivery Method Modal -->
    <DeliveryMethodModal
        v-model="showDeliveryMethodModal"
        @method-selected="handleDeliveryMethodSelected"
    />

    <!-- Payment Method Modal -->
    <PaymentMethodModal
        v-model="showPaymentMethodModal"
        :selected-value="selectedPaymentMethod"
        @payment-selected="handlePaymentMethodSelected"
    />
</template>

<script setup>
    import { ref, onMounted, computed, onUnmounted, watch } from 'vue'
    import { useToast } from 'vue-toastification'
    import IdentifyModal from './IdentifyModal.vue'
    import ProductModal from './ProductModal.vue'
    import AddressModal from './AddressModal.vue'
    import DeliveryMethodModal from './DeliveryMethodModal.vue'
    import PaymentMethodModal from './PaymentMethodModal.vue'
    import { useCartStore } from '@/stores/useCartStore'
    import { useUserStore } from '@/stores/useUserStore'

    const toast = useToast()
    const cart = useCartStore()
    const userStore = useUserStore()
    const showModal = ref(false)
    const showAddressModal = ref(false)
    const showDeliveryMethodModal = ref(false)
    const showPaymentMethodModal = ref(false)
    const selectedAddress = ref(null)
    const selectedDeliveryMethod = ref(null)
    const selectedPaymentMethod = ref('')

    // Product modal
    const showProductModal = ref(false)
    const selectedProduct = ref(null)

    // Função para obter o nome do item do combo
    const getComboItemName = (item, itemId) => {
        if (!item.comboItems) return itemId
        const comboItem = item.comboItems.find(i => i.id === itemId)
        return comboItem ? comboItem.name : itemId
    }

    // Função para obter o texto do método de entrega
    const getDeliveryMethodText = () => {
        if (!selectedDeliveryMethod.value) return 'Selecione uma forma de entrega'
        
        if (selectedDeliveryMethod.value.value === 'delivery') {
            return `Entrega em domicílio • ${selectedDeliveryMethod.value.timeEstimate}`
        } else if (selectedDeliveryMethod.value.value === 'pickup') {
            return `Retirada na loja • ${selectedDeliveryMethod.value.timeEstimate}`
        }else if (selectedDeliveryMethod.value.value === 'local') {
            return `Consumo no local`
        }
        return selectedDeliveryMethod.value.label
    }

    // Função para obter o texto do método de pagamento
    const getPaymentMethodText = () => {
        const labels = {
            card: 'Cartão de Crédito/Débito',
            pix: 'PIX',
            cash: 'Dinheiro'
        }
        return labels[selectedPaymentMethod.value] || selectedPaymentMethod.value
    }

    // Função para obter a descrição do método de pagamento
    const getPaymentMethodDescription = () => {
        if (!selectedPaymentMethod.value) return 'Selecione uma forma de pagamento'
        
        const descriptions = {
            card: 'Pagamento com cartão (débito ou crédito)',
            pix: 'Pagamento via PIX',
            cash: 'Pagamento em dinheiro'
        }
        return descriptions[selectedPaymentMethod.value] || ''
    }

    // Função para abrir modal de seleção de entrega
    const openDeliveryMethodModal = () => {
        if (!userStore.isLogged) {
            toast.warning('Você precisa se identificar primeiro!', {
                timeout: 3000
            })
            return
        }
        showDeliveryMethodModal.value = true
    }

    // Função para abrir modal de seleção de pagamento
    const openPaymentMethodModal = () => {
        if (!userStore.isLogged) {
            toast.warning('Você precisa se identificar primeiro!', {
                timeout: 3000
            })
            return
        }
        showPaymentMethodModal.value = true
    }

    // Função para lidar com a seleção do método de entrega
    const handleDeliveryMethodSelected = (method) => {
        selectedDeliveryMethod.value = method
        
        if (method.value === 'delivery' && !selectedAddress.value) {
            setTimeout(() => {
                openAddressModal()
            }, 300)
        }
        
        localStorage.setItem('selectedDeliveryMethod', JSON.stringify(method))
        
        toast.info(`Forma de entrega selecionada: ${method.label}`, {
            timeout: 3000
        })
    }

    // Função para lidar com a seleção do método de pagamento
    const handlePaymentMethodSelected = (method) => {
        selectedPaymentMethod.value = method
        localStorage.setItem('selectedPaymentMethod', method)
        
        toast.info(`Forma de pagamento selecionada: ${getPaymentMethodText()}`, {
            timeout: 3000
        })
    }

    // Função para atualizar o endereço selecionado
    const updateSelectedAddress = () => {
        if (!userStore.isLogged) {
            selectedAddress.value = null
            return
        }

        const addressesJson = localStorage.getItem('addresses')
        if (!addressesJson) {
            selectedAddress.value = null
            localStorage.removeItem('selectedAddress')
            return
        }
        
        const addresses = JSON.parse(addressesJson)
        const savedSelectedId = localStorage.getItem('selectedAddressId')
        
        if (savedSelectedId) {
            const foundAddress = addresses.find(addr => addr.id === parseInt(savedSelectedId))
            if (foundAddress) {
                selectedAddress.value = foundAddress
                localStorage.setItem('selectedAddress', JSON.stringify(foundAddress))
            } else {
                const primaryAddress = addresses.find(addr => addr.primary === true)
                if (primaryAddress) {
                    selectedAddress.value = primaryAddress
                    localStorage.setItem('selectedAddressId', primaryAddress.id.toString())
                    localStorage.setItem('selectedAddress', JSON.stringify(primaryAddress))
                } else {
                    selectedAddress.value = null
                    localStorage.removeItem('selectedAddress')
                    localStorage.removeItem('selectedAddressId')
                }
            }
        } else {
            const primaryAddress = addresses.find(addr => addr.primary === true)
            if (primaryAddress) {
                selectedAddress.value = primaryAddress
                localStorage.setItem('selectedAddressId', primaryAddress.id.toString())
                localStorage.setItem('selectedAddress', JSON.stringify(primaryAddress))
            } else {
                selectedAddress.value = null
                localStorage.removeItem('selectedAddress')
            }
        }
    }

    // Carregar método de entrega salvo
    const loadSavedDeliveryMethod = () => {
        const savedMethod = localStorage.getItem('selectedDeliveryMethod')
        if (savedMethod) {
            try {
                selectedDeliveryMethod.value = JSON.parse(savedMethod)
            } catch (e) {
                console.error('Erro ao carregar método de entrega:', e)
            }
        }
    }

    // Carregar método de pagamento salvo
    const loadSavedPaymentMethod = () => {
        const savedPayment = localStorage.getItem('selectedPaymentMethod')
        if (savedPayment) {
            selectedPaymentMethod.value = savedPayment
        }
    }

    const loadSavedAddress = () => {
        updateSelectedAddress()
    }

    const openAddressModal = () => {
        if (!userStore.isLogged) {
            toast.warning('Você precisa se identificar primeiro!', {
                timeout: 3000
            })
            return
        }
        showAddressModal.value = true
    }

    const handleAddressSelected = (address) => {
        if (address) {
            selectedAddress.value = address
            localStorage.setItem('selectedAddressId', address.id.toString())
            localStorage.setItem('selectedAddress', JSON.stringify(address))
        } else {
            selectedAddress.value = null
            localStorage.removeItem('selectedAddressId')
            localStorage.removeItem('selectedAddress')
        }
    }

    const formatAddress = (address) => {
        if (!address) return ''
        const parts = []
        if (address.street) parts.push(address.street)
        if (address.number) parts.push(address.number)
        if (address.complement) parts.push(address.complement)
        if (address.neighborhood) parts.push(address.neighborhood)
        if (address.city && address.state) parts.push(`${address.city} - ${address.state}`)
        if (address.cep) parts.push(address.cep)
        return parts.join(', ')
    }

    // Função corrigida para abrir o modal de produto com todas as informações do combo
    const openProductModal = (item) => {
    console.log('Abrindo modal para edição - item completo:', JSON.parse(JSON.stringify(item)))
    
    // ⭐ IMPORTANTE: O cartItemId DEVE ser o ID do item no carrinho
    const cartItemId = item.id // O id do item no carrinho (ID único gerado pela store)
    
    // Busca o produto original
    let originalProduct = null
    if (typeof window !== 'undefined' && window.products) {
        originalProduct = window.products.find(p => p.id === item.productId || p.id === item.originalProductId)
    }
    
    const baseProduct = originalProduct || item
    const isComboItem = item.isCombo === true || baseProduct?.isCombo === true
    
    let mappedSelections = {}
    let mappedAddons = {}
    
    if (isComboItem && item.itemSelections) {
        // ... mapeamento das seleções (igual ao que você já tem)
    }
    
    if (isComboItem && item.selectedAddons) {
        item.selectedAddons.forEach(addon => {
        mappedAddons[addon.id] = addon.quantity
        })
    }
    
    const productToEdit = {
        // ⭐ ID DO ITEM NO CARRINHO (para edição)
        cartItemId: cartItemId,
        
        // Propriedades básicas
        id: item.productId || item.id,
        productId: item.productId || item.originalProductId || item.id,
        name: item.name,
        description: item.description,
        price: item.basePrice || item.finalPrice || item.price,
        basePrice: item.basePrice,
        finalPrice: item.finalPrice,
        originalPrice: item.originalPrice || item.price,
        oldPrice: item.oldPrice,
        image: item.image,
        cashback: item.cashback || 0,
        cuisineType: item.cuisineType || baseProduct?.cuisineType,
        quantity: item.quantity || 1,
        
        isCombo: isComboItem,
        customization: baseProduct?.customization,
        
        // Propriedades de combo
        comboItems: isComboItem ? (baseProduct?.comboItems || item.comboItems) : null,
        comboAddons: isComboItem ? (baseProduct?.comboAddons || item.comboAddons) : null,
        savings: isComboItem ? (baseProduct?.savings || item.savings) : null,
        savingsPercent: isComboItem ? baseProduct?.savingsPercent : null,
        stock: baseProduct?.stock,
        
        // Dados das seleções
        comboItemSelections: isComboItem ? mappedSelections : {},
        comboAddonsState: isComboItem ? mappedAddons : {},
        selectedAddons: isComboItem ? (item.selectedAddons ? [...item.selectedAddons] : []) : [],
        itemSelections: isComboItem ? (item.itemSelections ? JSON.parse(JSON.stringify(item.itemSelections)) : {}) : {},
        
        // Propriedades para produtos normais
        aditionals: item.aditionals ? JSON.parse(JSON.stringify(item.aditionals)) : [],
        aditionalsState: item.aditionalsState ? { ...item.aditionalsState } : {},
        selectedSize: item.selectedSize,
        selectedFlavors: item.selectedFlavors ? [...item.selectedFlavors] : [],
        
        // Flag para indicar que está em modo de edição
        isEditing: true
    }
    
    console.log('Produto preparado para edição - cartItemId:', productToEdit.cartItemId)
    
    selectedProduct.value = productToEdit
    showProductModal.value = true
    }

    // Computed para saber se pode confirmar
    const canConfirm = computed(() => {
        if (!userStore.isLogged) return false
        if (!selectedDeliveryMethod.value) return false
        if (selectedDeliveryMethod.value.value === 'delivery' && !selectedAddress.value) return false
        if (!selectedPaymentMethod.value) return false
        return true
    })

    const handleIdentify = (data) => {
        userStore.login({ 
            fullName: data.fullName, 
            whatsapp: data.whatsapp,
            deliveryMethod: data.deliveryMethod,
            paymentMethod: data.paymentMethod,
            selectedAddress: data.selectedAddress
        })
        
        showModal.value = false
        
        if (data.deliveryMethod) {
            selectedDeliveryMethod.value = data.deliveryMethod
            localStorage.setItem('selectedDeliveryMethod', JSON.stringify(data.deliveryMethod))
        }
        
        if (data.paymentMethod) {
            selectedPaymentMethod.value = data.paymentMethod
            localStorage.setItem('selectedPaymentMethod', data.paymentMethod)
        }
        
        if (data.selectedAddress) {
            selectedAddress.value = data.selectedAddress
            localStorage.setItem('selectedAddressId', data.selectedAddress.id.toString())
            localStorage.setItem('selectedAddress', JSON.stringify(data.selectedAddress))
        }
        
        toast.success(`Bem-vindo(a), ${data.fullName}!`, {
            timeout: 4000
        })
        
        setTimeout(() => {
            updateSelectedAddress()
            loadSavedDeliveryMethod()
            loadSavedPaymentMethod()
        }, 100)
    }

    const handleStorageChange = (e) => {
        if ((e.key === 'addresses' || e.key === 'addressesUpdated') && userStore.isLogged) {
            updateSelectedAddress()
        }
    }

    const handleCustomAddressUpdate = () => {
        if (userStore.isLogged) {
            updateSelectedAddress()
        }
    }

    watch(() => userStore.isLogged, async (isLogged) => {
        if (isLogged) {
            await updateSelectedAddress()
            loadSavedDeliveryMethod()
            loadSavedPaymentMethod()
        } else {
            selectedAddress.value = null
            selectedDeliveryMethod.value = null
            selectedPaymentMethod.value = ''
            localStorage.removeItem('selectedAddressId')
            localStorage.removeItem('selectedAddress')
            localStorage.removeItem('selectedDeliveryMethod')
            localStorage.removeItem('selectedPaymentMethod')
        }
    })

    const forceUpdate = ref(0)

    watch(() => cart.items, (newItems) => {
    console.log('Carrinho atualizado:', JSON.parse(JSON.stringify(newItems)))
    // Força re-renderização
    forceUpdate.value++
    }, { deep: true })

    const checkAddressChanges = () => {
        const checkInterval = setInterval(() => {
            if (userStore.isLogged) {
                const savedAddressId = localStorage.getItem('selectedAddressId')
                const savedAddress = localStorage.getItem('selectedAddress')
                
                if (savedAddressId && savedAddress) {
                    const parsedAddress = JSON.parse(savedAddress)
                    if (selectedAddress.value?.id !== parsedAddress.id) {
                        updateSelectedAddress()
                    }
                }
            }
        }, 500)
        
        onUnmounted(() => {
            clearInterval(checkInterval)
        })
    }

    onMounted(() => {
        // Expor products para o window para acesso no Cart
        if (typeof window !== 'undefined') {
            // O App.vue expõe os produtos, mas aqui também podemos buscar
            // Isso é apenas para referência
        }
        
        userStore.loadUserFromStorage()
        loadSavedAddress()
        loadSavedDeliveryMethod()
        loadSavedPaymentMethod()
        checkAddressChanges()
        
        window.addEventListener('storage', handleStorageChange)
        window.addEventListener('addresses-updated', handleCustomAddressUpdate)
        
        window.addEventListener('user-login', (event) => {
            if (event.detail) {
                userStore.login({
                    fullName: event.detail.fullName,
                    whatsapp: event.detail.whatsapp,
                    deliveryMethod: event.detail.deliveryMethod,
                    paymentMethod: event.detail.paymentMethod,
                    selectedAddress: event.detail.selectedAddress
                })
                
                if (event.detail.deliveryMethod) {
                    selectedDeliveryMethod.value = event.detail.deliveryMethod
                    localStorage.setItem('selectedDeliveryMethod', JSON.stringify(event.detail.deliveryMethod))
                }
                
                if (event.detail.paymentMethod) {
                    selectedPaymentMethod.value = event.detail.paymentMethod
                    localStorage.setItem('selectedPaymentMethod', event.detail.paymentMethod)
                }
                
                if (event.detail.selectedAddress) {
                    selectedAddress.value = event.detail.selectedAddress
                    localStorage.setItem('selectedAddressId', event.detail.selectedAddress.id.toString())
                    localStorage.setItem('selectedAddress', JSON.stringify(event.detail.selectedAddress))
                }
                
                setTimeout(() => {
                    updateSelectedAddress()
                    loadSavedDeliveryMethod()
                    loadSavedPaymentMethod()
                }, 100)
            }
        })
    })

    onUnmounted(() => {
        window.removeEventListener('storage', handleStorageChange)
        window.removeEventListener('addresses-updated', handleCustomAddressUpdate)
    })

    const handleConfirm = () => {
        if (!canConfirm.value) {
            if (!userStore.isLogged) {
                toast.warning('Você precisa se identificar antes de continuar!', {
                    timeout: 3000
                })
            } else if (!selectedDeliveryMethod.value) {
                toast.warning('Por favor, selecione uma forma de entrega antes de continuar!', {
                    timeout: 3000
                })
            } else if (selectedDeliveryMethod.value.value === 'delivery' && !selectedAddress.value) {
                toast.warning('Por favor, selecione um endereço de entrega antes de continuar!', {
                    timeout: 3000
                })
            } else if (!selectedPaymentMethod.value) {
                toast.warning('Por favor, selecione uma forma de pagamento antes de continuar!', {
                    timeout: 3000
                })
            }
            return
        }
        
        const orderData = {
            user: {
                fullName: userStore.fullName,
                whatsapp: userStore.whatsapp
            },
            deliveryMethod: selectedDeliveryMethod.value,
            paymentMethod: selectedPaymentMethod.value,
            address: selectedAddress.value,
            cart: cart.items,
            totals: {
                subTotal: cart.subTotal,
                discount: cart.discount,
                total: cart.total
            },
            completedAt: new Date().toISOString()
        }
        
        console.log('Pedido confirmado:', orderData)
        
        toast.success('Pedido confirmado com sucesso!', {
            timeout: 4000
        })
    }

    const formatPrice = (value) => {
        if (!value && value !== 0) return 'R$ 0,00'
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(value)
    }
    
    const getItemTotal = (item) => {
        if (!item) return 0
        // Se for combo, usa o finalPrice
        if (item.isCombo) {
            return item.finalPrice || item.price
        }
        // Para produtos normais
        const productTotal = (item.price || 0) * (item.quantity || 1)
        const aditionalsTotal = getItemAditionalsTotal(item)
        return productTotal + aditionalsTotal
    }
    
    const getItemAditionalsTotal = (item) => {
        if (!item || !item.aditionals) return 0
        
        let total = 0
        item.aditionals.forEach(group => {
            group.items.forEach(aditional => {
                if (aditional.quantity > 0 && aditional.price) {
                    total += (aditional.price * aditional.quantity)
                }
            })
        })
        return total
    }
    
    const getItemAditionalsList = (item) => {
        if (!item || !item.aditionals) return []
        
        const list = []
        item.aditionals.forEach(group => {
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
        })
        return list
    }
    
    const formatReorderDate = (date) => {
        if (!date) return 'agora mesmo'
        const now = new Date()
        const reorderDate = new Date(date)
        const diffMinutes = Math.floor((now - reorderDate) / 60000)
        
        if (diffMinutes < 1) return 'agora mesmo'
        if (diffMinutes < 60) return `${diffMinutes} minuto(s) atrás`
        if (diffMinutes < 1440) return `${Math.floor(diffMinutes / 60)} hora(s) atrás`
        return reorderDate.toLocaleDateString('pt-BR')
    }

    // Função para debug - ver o que tem no carrinho
    const debugCart = () => {
        console.log('Itens no carrinho:', JSON.parse(JSON.stringify(cart.items)))
    }
    
    // Chamar debug quando montar
    onMounted(() => {
        setTimeout(debugCart, 1000)
    })
</script>

<style scoped>
.is-reorder{
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
    font-size: 0.65rem;
}
.pointer{
    cursor: pointer;
    display: flex;
}
.icon-address.not-address{
    background: #E9ECEF;
}
.icon-address.not-address svg path {
  fill: #595959;
}
.contorno.not-address{
    border-color: #E0E0E0;
}
.contorno{
    border: 1px solid var(--bg-orange);
}
.finish{
    font-size: 0.75rem !important;
    line-height: 18px;
    display: table;
}
.address-title{
    color: #595959;
    font-size: 0.938rem;
}
.icon-address{
    background: var(--bg-orange); 
}
.cart-item .fw-bold{
    font-size: 0.938rem;
}
.info-value{
    font-size: 0.875rem;
    font-weight: 600;
}
.btn-reset {
    all: unset;
    cursor: pointer;
}
.btn-red{
    background: #D93030;
    font-size: 0.625rem;
    font-weight: 600;
}
.btn-red:hover, .btn:focus-visible, :not(.btn-check)+.btn:active:focus-visible{
    background: #D93030;
}
.price{
    font-size: clamp(0.75rem, 1.25vw, 1rem);
}
.cashback{
    background: #FFC400;
    color: #2F2B2B;
    font-size: clamp(0.75rem, 0.75vw, 0.875rem) !important;
}
.small{
    font-size: clamp(0.75rem, 0.75vw, 0.875rem) !important;
}
.percent{
    background: var(--primary);
    font-size: 0.75rem;
    font-weight: 600;
}
.my-cart{
    color: #595959;
}
.arrow{
    border-color: #6D6B6B !important;
    height: 28px;
    width: 28px;
}
.icon-user{
    background: #E9ECEF;
}
.cart-canvas {
  width: 360px;
}

/* Header */
.cart-header {
  background: var(--bg-ouro);
  padding: 12px 16px;
  font-weight: 600;
}

/* Identificação */
.identify-box {
  background: rgb(222 226 230 / 10%);
  border-radius: 10px;
  padding: 12px;
  cursor: pointer;
}

/* Item */
.cart-item {
  border: 1px solid #eee;
  border-radius: 10px;
  padding: 10px;
  cursor: pointer;
}

.item-img {
    width: 80px;
    height: 80px;
    object-fit: cover;
}

/* Quantidade */
.qty-control button {
  border: none;
  background: transparent;
}
.qty-control span{
    font-weight: 800;
}
.btn-plus {
    color: #5CB85C;
    font-size: 1.563rem;
    font-weight: 800;
}

.btn-minus {
    color: #D93030;
    font-size: 1.563rem;
    font-weight: 800;
}

/* Botão final */
.btn-confirm {
  background: var(--bg-yellow) !important;
  color: var(--text-dark) !important;
  border: none;
  border-radius: inherit;
  font-weight: 500;
  height: 30px;
  font-size: 0.875rem;
}
@media (max-width: 475px) { 
    .badge.bg-warning.p-2.text-dark{
        font-size: 0.625rem;
    }
}
</style>