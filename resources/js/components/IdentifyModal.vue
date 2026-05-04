<template>
  <div
    v-if="modelValue"
    class="modal fade show"
    tabindex="-1"
    style="display: block; background: rgba(0,0,0,.5)"
    @click.self="close"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content p-0 identify-modal">

        <!-- HEADER -->
        <div class="modal-header d-flex justify-content-between align-items-center mb-3 py-2 px-4">
            <div class="d-flex gap-2">
                <svg class="svg" width="24" height="27" viewBox="0 0 24 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.3556 14.8065C16.3556 14.6323 19.5556 11.3661 19.5556 7.40323C19.5556 3.30968 16.1778 0 12 0C7.82222 0 4.44444 3.30968 4.44444 7.40323C4.44444 11.3661 7.64444 14.5887 11.6444 14.8065C5.06667 15.0242 0 20.1629 0 27H1.77778C1.77778 20.9032 6.17778 16.5484 12 16.5484C17.8222 16.5484 22.2222 20.9032 22.2222 27H24C24 20.1629 18.9333 15.0242 12.3556 14.8065ZM6.22222 7.44677C6.22222 4.31129 8.8 1.78548 12 1.78548C15.2 1.78548 17.7778 4.31129 17.7778 7.44677C17.7778 10.5823 15.2 13.1081 12 13.1081C8.8 13.1081 6.22222 10.5387 6.22222 7.44677Z" fill="#595959"></path></svg>
                <h5 class="modal-title">Confirme quem você é</h5>
            </div>
            <button type="button" class="btn-close" @click="close"></button>
        </div>

      <div class="modal-body" :class="{ 'loading-overlay': isLoading }">
          
          <!-- ETAPA 1: DADOS INICIAIS -->
          <div v-show="currentStep === 'form'" ref="formSectionRef" class="step-section">
            <div class="mb-3">
              <label class="form-label">Digite seu número de WhatsApp</label>
              <div class="input-group gap-2">
                <span class="input-group-text rounded">
                    <svg class="brasil-icon me-2" width="27" height="20" viewBox="0 0 27 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_16_469)">
                    <path d="M27 0H0V20H27V0Z" fill="#00923F"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.5009 2.42859L2.2959 10L13.5009 17.5714L24.7059 10L13.5009 2.42859Z" fill="#F8C300"/>
                    <path d="M13.4897 14.9897C16.0939 14.9897 18.205 12.7557 18.205 9.99997C18.205 7.24423 16.0939 5.01025 13.4897 5.01025C10.8855 5.01025 8.77441 7.24423 8.77441 9.99997C8.77441 12.7557 10.8855 14.9897 13.4897 14.9897Z" fill="#28166F"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.9677 11.0762L14.0247 11.2285L14.1789 11.2388L14.0601 11.3434L14.0982 11.502L13.9677 11.4142L13.8371 11.502L13.8754 11.3434L13.7563 11.2388L13.9105 11.2285L13.9677 11.0762ZM17.4929 11.6088L17.5415 11.7385L17.673 11.7474L17.5714 11.8365L17.6041 11.9714L17.4929 11.8968L17.3814 11.9714L17.414 11.8365L17.3128 11.7474L17.4443 11.7385L17.4929 11.6088ZM16.9882 12.0222L17.0455 12.1742L17.1996 12.1848L17.0806 12.2891L17.1189 12.4477L16.9882 12.3602L16.8576 12.4477L16.8959 12.2891L16.7768 12.1848L16.9313 12.1742L16.9882 12.0222ZM16.8011 11.4871L16.8689 11.6674L17.0517 11.68L16.9107 11.8037L16.9561 11.9914L16.8011 11.8877L16.6464 11.9914L16.6918 11.8037L16.5508 11.68L16.7336 11.6674L16.8011 11.4871ZM16.7649 12.4191L16.8219 12.5711L16.9764 12.5814L16.8573 12.686L16.8956 12.8445L16.7649 12.7568L16.6343 12.8445L16.6726 12.686L16.5535 12.5814L16.7077 12.5711L16.7649 12.4191ZM16.3235 12.6648L16.3721 12.7942L16.5036 12.8034L16.4023 12.8922L16.435 13.0274L16.3235 12.9528L16.2123 13.0274L16.2449 12.8922L16.1434 12.8034L16.2749 12.7942L16.3235 12.6648ZM15.8696 13.1114L15.9269 13.2634L16.081 13.274L15.962 13.3782L16.0003 13.5368L15.8696 13.4491L15.7392 13.5368L15.7773 13.3782L15.6585 13.274L15.8127 13.2634L15.8696 13.1114ZM15.8642 13.6702L15.9128 13.8L16.0443 13.8088L15.9431 13.898L15.9757 14.0331L15.8642 13.9585L15.753 14.0331L15.7857 13.898L15.6841 13.8088L15.8156 13.8L15.8642 13.6702ZM15.8569 12.66L15.9055 12.7894L16.037 12.7982L15.9355 12.8874L15.9682 13.0225L15.8569 12.948L15.7454 13.0225L15.7781 12.8874L15.6766 12.7982L15.8083 12.7894L15.8569 12.66ZM15.3007 12.7757L15.3496 12.9054L15.4811 12.9142L15.3796 13.0034L15.4122 13.1385L15.3007 13.064L15.1895 13.1385L15.2222 13.0034L15.1206 12.9142L15.2521 12.9054L15.3007 12.7757ZM14.9441 7.98682L15.0118 8.1671L15.1946 8.17939L15.0537 8.3031L15.099 8.49082L14.9441 8.3871L14.7894 8.49082L14.8347 8.3031L14.6938 8.17939L14.8766 8.1671L14.9441 7.98682ZM14.8695 13.2325L14.9265 13.3848L15.081 13.3951L14.9619 13.4997L15.0002 13.658L14.8695 13.5705L14.7389 13.658L14.7772 13.4997L14.6581 13.3951L14.8123 13.3848L14.8695 13.2325ZM14.6071 9.50339L14.656 9.63282L14.7875 9.64196L14.6859 9.73082L14.7186 9.86596L14.6071 9.79139L14.4959 9.86596L14.5285 9.73082L14.427 9.64196L14.5585 9.63282L14.6071 9.50339ZM14.4821 12.65L14.5307 12.7797L14.6622 12.7885L14.5609 12.8774L14.5936 13.0125L14.4821 12.938L14.3709 13.0125L14.4035 12.8774L14.302 12.7885L14.4335 12.7797L14.4821 12.65ZM13.5355 10.6485L13.5924 10.8005L13.7469 10.8111L13.6278 10.9154L13.6662 11.074L13.5355 10.9865L13.4048 11.074L13.4431 10.9154L13.3241 10.8111L13.4782 10.8005L13.5355 10.6485ZM13.5079 12.064L13.5754 12.2442L13.7585 12.2565L13.6173 12.3802L13.6626 12.5682L13.5079 12.4645L13.3532 12.5682L13.3986 12.3802L13.2574 12.2565L13.4404 12.2442L13.5079 12.064ZM13.5233 13.8688L13.5492 13.938L13.6194 13.9428L13.5654 13.9902L13.5827 14.0625L13.5233 14.0225L13.4639 14.0625L13.4812 13.9902L13.4272 13.9428L13.4974 13.938L13.5233 13.8688ZM13.2255 11.4834L13.262 11.5805L13.3602 11.5871L13.2844 11.6537L13.3089 11.7548L13.2255 11.6988L13.1424 11.7548L13.1667 11.6537L13.0908 11.5871L13.1893 11.5805L13.2255 11.4834ZM12.881 11.1682L12.9296 11.298L13.0611 11.3068L12.9596 11.3957L12.9922 11.5308L12.881 11.4562L12.7695 11.5308L12.8022 11.3957L12.7006 11.3068L12.8324 11.298L12.881 11.1682ZM11.6563 9.97682L11.7132 10.1291L11.8674 10.1394L11.7486 10.244L11.7867 10.4025L11.6563 10.3148L11.5256 10.4025L11.5639 10.244L11.4449 10.1394L11.599 10.1291L11.6563 9.97682ZM11.5183 12.4971L11.5858 12.6774L11.7689 12.69L11.6277 12.8137L11.673 13.0014L11.5183 12.8977L11.3633 13.0014L11.409 12.8137L11.2677 12.69L11.4505 12.6774L11.5183 12.4971ZM11.0004 11.5022L11.0577 11.6542L11.2119 11.6648L11.0928 11.7691L11.1311 11.9277L11.0004 11.84L10.87 11.9277L10.9081 11.7691L10.7893 11.6648L10.9435 11.6542L11.0004 11.5022ZM10.8298 12.0628L10.8784 12.1925L11.0099 12.2014L10.9084 12.2902L10.941 12.4254L10.8298 12.3508L10.7183 12.4254L10.751 12.2902L10.6494 12.2014L10.7809 12.1925L10.8298 12.0628ZM10.478 10.7877L10.5144 10.8845L10.6127 10.8914L10.5369 10.958L10.5614 11.0588L10.478 11.0031L10.3948 11.0588L10.4191 10.958L10.3433 10.8914L10.4418 10.8845L10.478 10.7877ZM10.0452 10.9888L10.1127 11.1691L10.2957 11.1814L10.1545 11.3051L10.1999 11.4931L10.0452 11.3894L9.89048 11.4931L9.93584 11.3051L9.79463 11.1814L9.97769 11.1691L10.0452 10.9888ZM9.65666 8.91282L9.72443 9.0931L9.90722 9.10539L9.76628 9.2291L9.81164 9.4171L9.65666 9.31339L9.50195 9.4171L9.54731 9.2291L9.40637 9.10539L9.58916 9.0931L9.65666 8.91282ZM9.55028 11.4631L9.60752 11.6154L9.76169 11.6257L9.64262 11.7302L9.68096 11.8885L9.55028 11.8011L9.4196 11.8885L9.45794 11.7302L9.33887 11.6257L9.49331 11.6154L9.55028 11.4631Z" fill="white"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.6759 8.45846C13.4807 8.45846 16.0519 9.55018 18.0302 11.3519C18.0955 11.109 18.1438 10.8587 18.1738 10.6027C16.1226 8.84218 13.5122 7.78503 10.6759 7.78503C10.1842 7.78503 9.69931 7.81761 9.22276 7.87932C9.12097 8.10932 9.03511 8.34846 8.9668 8.59561C9.52435 8.50532 10.0951 8.45846 10.6759 8.45846Z" fill="white"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.46142 8.19938C9.4652 8.24823 9.4787 8.28509 9.50138 8.31023C9.52406 8.33538 9.55376 8.34623 9.59075 8.34309C9.62774 8.33966 9.65582 8.32366 9.67418 8.29452C9.69281 8.26566 9.69983 8.22681 9.69605 8.17795C9.692 8.12909 9.6785 8.09223 9.65582 8.06709C9.63287 8.04195 9.6029 8.03109 9.56564 8.03452C9.52865 8.03766 9.50111 8.05395 9.48275 8.08281C9.46466 8.11138 9.45737 8.15023 9.46142 8.19938ZM9.3734 8.20738C9.36746 8.13566 9.3815 8.07709 9.41498 8.03166C9.44846 7.98623 9.49652 7.96081 9.55916 7.95509C9.6218 7.94938 9.67337 7.96595 9.71387 8.00452C9.75437 8.04338 9.77759 8.09852 9.78326 8.16995C9.7892 8.24166 9.77516 8.30023 9.74141 8.34566C9.70793 8.39109 9.6596 8.41681 9.59723 8.42252C9.53432 8.42823 9.48302 8.41166 9.44252 8.37281C9.40229 8.33395 9.37907 8.27881 9.3734 8.20738ZM10.0581 8.12452L10.1599 8.12081C10.182 8.11995 10.198 8.11481 10.2082 8.10481C10.2182 8.09509 10.2228 8.07995 10.2223 8.05966C10.2217 8.04023 10.2161 8.02566 10.2058 8.01623C10.1953 8.00652 10.1802 8.00195 10.1602 8.00281L10.0543 8.00652L10.0581 8.12452ZM9.97955 8.37795L9.96551 7.93281L10.1696 7.92566C10.2153 7.92395 10.2495 7.93309 10.2722 7.95309C10.2952 7.97309 10.3071 8.00452 10.3084 8.04709C10.3092 8.07423 10.3046 8.09709 10.2944 8.11566C10.2844 8.13423 10.2693 8.14709 10.2493 8.15452C10.2676 8.16081 10.2811 8.17081 10.2892 8.18452C10.2973 8.19852 10.3025 8.22023 10.3046 8.24995L10.3081 8.30252V8.30423C10.3095 8.33081 10.3154 8.34681 10.326 8.35195L10.3262 8.36566L10.2315 8.36909C10.2282 8.36281 10.2258 8.35538 10.2239 8.34623C10.222 8.33709 10.2204 8.32623 10.2198 8.31338L10.2171 8.26652C10.2153 8.23909 10.2099 8.22052 10.2007 8.21138C10.1915 8.20195 10.1753 8.19766 10.1523 8.19852L10.0605 8.20166L10.0662 8.37481L9.97955 8.37795ZM10.6005 8.27795L10.6699 8.27938C10.7102 8.28023 10.7396 8.26966 10.758 8.24766C10.7763 8.22595 10.786 8.18966 10.7868 8.13995C10.7877 8.08995 10.7801 8.05338 10.7636 8.02966C10.7472 8.00595 10.7212 7.99366 10.6856 7.99309L10.6057 7.99138L10.6005 8.27795ZM10.5141 8.35652L10.5222 7.91138L10.6869 7.91452C10.7517 7.91595 10.7995 7.93538 10.8306 7.97281C10.8616 8.01023 10.8765 8.06652 10.8751 8.14166C10.8743 8.18223 10.8678 8.21795 10.8557 8.24881C10.8433 8.27966 10.8257 8.30423 10.8033 8.32281C10.7863 8.33652 10.7671 8.34623 10.7455 8.35195C10.7242 8.35766 10.6942 8.36023 10.6556 8.35938L10.5141 8.35652ZM11.0482 8.36652L11.0709 7.92195L11.3763 7.93938L11.3722 8.01652L11.1522 8.00395L11.1476 8.09881L11.3484 8.11023L11.3447 8.18623L11.1435 8.17481L11.1378 8.28909L11.3679 8.30223L11.3638 8.38452L11.0482 8.36652ZM11.5437 8.39766L11.5882 7.95481L11.7156 7.96909L11.7648 8.31566L11.8809 7.98795L12.0086 8.00223L11.964 8.44509L11.8833 8.43595L11.9192 8.07881L11.7983 8.42652L11.7102 8.41652L11.6603 8.04966L11.6241 8.40681L11.5437 8.39766ZM12.6588 8.54966L12.7468 8.11423L13.0457 8.18195L13.0303 8.25766L12.8151 8.20881L12.7962 8.30166L12.9933 8.34623L12.9782 8.42081L12.7811 8.37595L12.7584 8.48795L12.9838 8.53909L12.9676 8.61966L12.6588 8.54966ZM13.7725 8.57709L13.8535 8.60023C13.8754 8.60623 13.8924 8.60595 13.9048 8.59909C13.9172 8.59195 13.9264 8.57766 13.9318 8.55566C13.9372 8.53538 13.9361 8.51852 13.9291 8.50509C13.9221 8.49195 13.9089 8.48252 13.8894 8.47709L13.8044 8.45281L13.7725 8.57709ZM13.7523 8.65481L13.7134 8.80766L13.6297 8.78395L13.739 8.35395L13.9199 8.40538C13.9626 8.41766 13.9917 8.43881 14.0079 8.46823C14.0239 8.49795 14.026 8.53566 14.0144 8.58109C14.0034 8.62538 13.9839 8.65652 13.9561 8.67423C13.9286 8.69223 13.8945 8.69538 13.8549 8.68423L13.7523 8.65481ZM14.2582 8.72481L14.3554 8.75652C14.3765 8.76338 14.3935 8.76395 14.4059 8.75795C14.4186 8.75223 14.4275 8.73938 14.4332 8.71995C14.4386 8.70166 14.4378 8.68595 14.431 8.67338C14.4243 8.66052 14.4111 8.65109 14.3922 8.64481L14.2909 8.61195L14.2582 8.72481ZM14.106 8.93738L14.2293 8.51166L14.4246 8.57509C14.4683 8.58938 14.498 8.60995 14.5134 8.63652C14.5288 8.66338 14.5304 8.69709 14.5185 8.73795C14.511 8.76395 14.4996 8.78395 14.4842 8.79795C14.4688 8.81195 14.4507 8.81909 14.4297 8.81909C14.4451 8.83138 14.4548 8.84538 14.4583 8.86138C14.4615 8.87738 14.4597 8.89966 14.4526 8.92852L14.4397 8.97938C14.4397 8.97966 14.4394 8.98023 14.4391 8.98109C14.4324 9.00652 14.4329 9.02366 14.4413 9.03223L14.4375 9.04538L14.3468 9.01566C14.3457 9.00881 14.3454 9.00081 14.3465 8.99138C14.3476 8.98223 14.3495 8.97138 14.3527 8.95909L14.3646 8.91395C14.3714 8.88738 14.3719 8.86795 14.3662 8.85623C14.3603 8.84423 14.3465 8.83452 14.3244 8.82738L14.2366 8.79881L14.1886 8.96423L14.106 8.93738ZM14.7467 8.93595C14.7307 8.98195 14.7283 9.02138 14.7388 9.05452C14.7491 9.08738 14.7718 9.11052 14.8066 9.12395C14.8417 9.13738 14.8733 9.13538 14.9016 9.11738C14.93 9.09938 14.9521 9.06738 14.9681 9.02138C14.9837 8.97538 14.9864 8.93595 14.9759 8.90309C14.9651 8.86995 14.9421 8.84652 14.907 8.83309C14.8725 8.81966 14.8409 8.82195 14.8128 8.83966C14.7847 8.85766 14.7626 8.88966 14.7467 8.93595ZM14.6638 8.90366C14.6873 8.83623 14.7232 8.78938 14.7715 8.76309C14.8201 8.73709 14.8738 8.73538 14.933 8.75823C14.9918 8.78109 15.0321 8.81909 15.0534 8.87223C15.0747 8.92566 15.0736 8.98595 15.0501 9.05338C15.0269 9.12081 14.991 9.16766 14.9421 9.19366C14.8935 9.21995 14.8395 9.22166 14.7807 9.19881C14.7216 9.17595 14.6816 9.13795 14.6605 9.08481C14.6395 9.03166 14.6406 8.97109 14.6638 8.90366ZM15.4425 9.42681C15.4198 9.44081 15.3974 9.44852 15.375 9.44995C15.3528 9.45138 15.3293 9.44652 15.3048 9.43538C15.2508 9.41081 15.2148 9.37138 15.1973 9.31652C15.1797 9.26138 15.1841 9.20166 15.21 9.13709C15.2364 9.07166 15.2742 9.02709 15.3234 9.00309C15.3725 8.97881 15.4252 8.97938 15.4808 9.00452C15.5294 9.02652 15.564 9.05652 15.5847 9.09509C15.6055 9.13338 15.6101 9.17538 15.5985 9.22109L15.5172 9.18452C15.521 9.16109 15.5175 9.14023 15.5067 9.12223C15.4962 9.10423 15.4786 9.08995 15.4543 9.07881C15.4219 9.06423 15.3914 9.06538 15.3628 9.08223C15.3342 9.09881 15.311 9.12938 15.2931 9.17366C15.2751 9.21823 15.2707 9.25738 15.2799 9.29138C15.2891 9.32538 15.3104 9.34995 15.3439 9.36509C15.3693 9.37623 15.3936 9.37795 15.4168 9.36995C15.4403 9.36166 15.4595 9.34481 15.4746 9.31938L15.3868 9.27966L15.4149 9.20966L15.5753 9.28195L15.4859 9.50366L15.4327 9.47966L15.4425 9.42681ZM15.8426 9.41509L15.9339 9.46252C15.9538 9.47281 15.9703 9.47595 15.9835 9.47223C15.9968 9.46852 16.0076 9.45766 16.0162 9.43909C16.024 9.42195 16.0257 9.40652 16.0208 9.39281C16.0159 9.37909 16.0043 9.36766 15.9865 9.35823L15.8915 9.30909L15.8426 9.41509ZM15.6606 9.59966L15.8456 9.19995L16.0289 9.29481C16.0702 9.31623 16.0964 9.34138 16.1075 9.37023C16.1188 9.39909 16.1156 9.43281 16.0977 9.47109C16.0864 9.49566 16.0721 9.51338 16.0551 9.52481C16.0378 9.53595 16.0186 9.53995 15.9978 9.53652C16.0113 9.55138 16.0186 9.56652 16.02 9.58309C16.0208 9.59938 16.0157 9.62109 16.0043 9.64823L15.9841 9.69652C15.9841 9.69681 15.9838 9.69738 15.9833 9.69795C15.9727 9.72195 15.9708 9.73909 15.9779 9.74909L15.9722 9.76109L15.8869 9.71709C15.8869 9.70995 15.888 9.70195 15.8904 9.69309C15.8928 9.68395 15.8963 9.67366 15.9015 9.66223L15.9198 9.61966C15.9303 9.59452 15.9336 9.57538 15.9298 9.56252C15.9258 9.54966 15.9133 9.53795 15.8928 9.52738L15.8105 9.48452L15.7384 9.63995L15.6606 9.59966ZM16.131 9.85538L16.3356 9.46623L16.6026 9.62338L16.567 9.69109L16.3745 9.57766L16.331 9.66081L16.5071 9.76423L16.472 9.83081L16.2959 9.72738L16.2433 9.82738L16.445 9.94595L16.4072 10.0179L16.131 9.85538ZM16.6059 9.99338L16.6791 10.0399C16.6699 10.0625 16.6691 10.0834 16.6766 10.1019C16.6842 10.1205 16.7007 10.1379 16.726 10.1539C16.7476 10.1679 16.766 10.1742 16.7817 10.1728C16.7973 10.1719 16.8095 10.1634 16.8186 10.1474C16.8316 10.1242 16.8111 10.0851 16.7571 10.0299L16.7552 10.0279C16.7538 10.0265 16.7517 10.0242 16.7487 10.0214C16.7193 9.99195 16.7004 9.96795 16.6917 9.94909C16.6836 9.93252 16.6807 9.91452 16.6823 9.89566C16.6839 9.87681 16.6904 9.85766 16.7017 9.83766C16.7228 9.80052 16.7509 9.77995 16.786 9.77509C16.8211 9.77081 16.8613 9.78281 16.9061 9.81138C16.9482 9.83823 16.9752 9.86995 16.9869 9.90595C16.9987 9.94223 16.9941 9.97995 16.9739 10.0191L16.9026 9.97366C16.9118 9.95423 16.9131 9.93595 16.9064 9.91881C16.8999 9.90138 16.8853 9.88538 16.8632 9.87138C16.8438 9.85881 16.8265 9.85338 16.8114 9.85452C16.7962 9.85595 16.7846 9.86395 16.776 9.87881C16.7644 9.89909 16.7765 9.92709 16.8116 9.96338C16.8211 9.97309 16.8286 9.98081 16.8338 9.98623C16.8562 10.0102 16.8716 10.0277 16.8805 10.0385C16.8891 10.0497 16.8961 10.0599 16.9015 10.0702C16.9113 10.0882 16.9158 10.1065 16.915 10.1254C16.9142 10.1445 16.9083 10.1639 16.8969 10.1837C16.8743 10.2234 16.8443 10.2459 16.8065 10.2517C16.7687 10.2574 16.7268 10.2457 16.6809 10.2162C16.6356 10.1874 16.6062 10.1537 16.5932 10.1148C16.5802 10.0762 16.5843 10.0357 16.6059 9.99338ZM17.0492 10.2868L17.12 10.3377C17.1097 10.3597 17.1081 10.3802 17.1148 10.3994C17.1213 10.4182 17.137 10.4365 17.1615 10.4539C17.1823 10.4688 17.2004 10.4762 17.2161 10.4759C17.2317 10.4757 17.2444 10.4679 17.2542 10.4525C17.2685 10.4299 17.2498 10.3899 17.1985 10.3317C17.198 10.3311 17.1972 10.3302 17.1969 10.3297C17.1953 10.3282 17.1934 10.3259 17.1907 10.3225C17.1629 10.2917 17.1451 10.2668 17.1372 10.2474C17.1302 10.2305 17.1281 10.2122 17.1305 10.1937C17.1332 10.1748 17.1405 10.1559 17.1529 10.1368C17.1759 10.1008 17.205 10.0817 17.2401 10.0791C17.2755 10.0765 17.3149 10.0908 17.3584 10.1219C17.3991 10.1511 17.4243 10.1839 17.4342 10.2208C17.4442 10.2577 17.438 10.2948 17.4156 10.3328L17.3465 10.2837C17.3568 10.2648 17.3589 10.2465 17.3532 10.2288C17.3476 10.2111 17.3338 10.1945 17.3125 10.1791C17.2936 10.1657 17.2766 10.1591 17.2614 10.1597C17.2463 10.1602 17.2342 10.1677 17.225 10.1819C17.2126 10.2014 17.2231 10.2302 17.2563 10.2682C17.2655 10.2785 17.2725 10.2868 17.2774 10.2925C17.2984 10.3174 17.313 10.3359 17.3214 10.3471C17.3295 10.3585 17.336 10.3697 17.3408 10.3799C17.3497 10.3985 17.3532 10.4171 17.3516 10.4359C17.35 10.4548 17.343 10.4739 17.3306 10.4931C17.3063 10.5314 17.2749 10.5525 17.2369 10.5559C17.1988 10.5597 17.1578 10.5457 17.1132 10.5137C17.0695 10.4822 17.0419 10.4468 17.0309 10.4077C17.0195 10.3682 17.0257 10.3279 17.0492 10.2868ZM17.5841 10.5871C17.556 10.6262 17.5425 10.6634 17.5436 10.6979C17.5444 10.7328 17.5598 10.7619 17.5892 10.7857C17.6189 10.8094 17.65 10.8171 17.6821 10.8088C17.714 10.8005 17.7439 10.7765 17.772 10.7374C17.7998 10.6985 17.8133 10.6614 17.8122 10.6265C17.8109 10.5917 17.7955 10.5622 17.7658 10.5385C17.7364 10.5148 17.7056 10.5074 17.6737 10.5159C17.6419 10.5242 17.6119 10.5479 17.5841 10.5871ZM17.5136 10.5308C17.5547 10.4737 17.6019 10.4399 17.6554 10.4297C17.7091 10.4197 17.7609 10.4348 17.8112 10.4748C17.8614 10.5151 17.8892 10.5637 17.8949 10.6214C17.9003 10.6788 17.8827 10.7362 17.8419 10.7934C17.8009 10.8508 17.7534 10.8845 17.6997 10.8942C17.6459 10.9042 17.5938 10.8894 17.5439 10.8494C17.4936 10.8091 17.4658 10.7602 17.4604 10.7028C17.455 10.6457 17.4726 10.5882 17.5136 10.5308Z" fill="#00923F"/>
                    </g>
                    <defs>
                    <clipPath id="clip0_16_469">
                    <rect width="27" height="20" fill="white"/>
                    </clipPath>
                    </defs>
                    </svg>
                  +55
                </span>
                <input
                  v-model="whatsapp"
                  type="text"
                  class="form-control rounded"
                  placeholder="(99) 99999-9999"
                  @input="formatWhatsapp"
                  maxlength="15"
                  :disabled="isLoading"
                >
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Digite seu Nome Completo</label>
              <input
                v-model="fullName"
                type="text"
                class="form-control"
                placeholder="Nome completo"
                :disabled="isLoading"
              >
            </div>

            <button 
              class="btn btn-primary w-100" 
              @click="checkAndAdvanceToDelivery"
              :disabled="isLoading"
            >
              <span v-if="isLoading">Verificando...</span>
              <span v-else>{{ isLoggedIn ? 'Sair' : 'Continuar' }}</span>
            </button>
          </div>

          <!-- ETAPA 2: VALIDAÇÃO DE CEP (quando usuário já existe) -->
          <div v-show="currentStep === 'cep'" ref="cepSectionRef" class="step-section py-3">
            <div class="text-center mb-4">
              <h4 class="fw-semibold text-secondary">Confirme seu CEP</h4>
              <p class="text-muted small">Digite o CEP cadastrado em sua conta</p>
            </div>

            <div class="d-flex justify-content-center gap-2 flex-wrap">
              <input 
                v-for="(digit, index) in 8" 
                :key="index"
                ref="cepInputs"
                type="text" 
                maxlength="1" 
                class="form-control text-center cep-box"
                v-model="cepDigits[index]"
                @input="handleCepDigit(index, $event)"
                @keydown="handleCepKeydown(index, $event)"
                @paste="handleCepPaste"
                :disabled="isLoading"
              >
            </div>
            
            <div v-if="cepError" class="text-center mt-3">
              <span class="text-danger small">{{ cepError }}</span>
            </div>

            <div class="d-flex gap-2 mt-4">
              <button class="btn btn-secondary w-50" @click="backToForm" :disabled="isLoading">
                Voltar
              </button>
              <button class="btn btn-primary w-50" @click="validateCepAndLogin" :disabled="isLoading || !isCepComplete">
                <span v-if="isLoading">Verificando...</span>
                <span v-else>Verificar CEP</span>
              </button>
            </div>
          </div>

          <!-- ETAPA 3: FORMA DE ENTREGA (usando o modal) -->
          <div v-show="currentStep === 'delivery'" class="step-section text-center py-4">
            <div class="text-center mb-4">
              <h4 class="fw-semibold text-secondary">Selecione a forma de entrega</h4>
              <p class="text-muted small">Clique no botão abaixo para escolher como deseja receber seu pedido</p>
            </div>

            <div class="d-flex flex-column align-items-center gap-3">
              <div 
                v-if="tempDeliveryMethod" 
                class="selected-option-card p-3 rounded border border-success bg-light"
              >
                <h6 class="mb-1 text-success">✅ Método selecionado:</h6>
                <p class="mb-0 fw-semibold">{{ tempDeliveryMethod.label }}</p>
                <small class="text-muted">{{ getDeliveryMethodTime(tempDeliveryMethod) }}</small>
              </div>

              <button 
                class="btn btn-primary w-100" 
                @click="openDeliveryMethodModal"
                :disabled="isLoading"
              >
                {{ tempDeliveryMethod ? 'Alterar forma de entrega' : 'Selecionar forma de entrega' }}
              </button>

              <div class="d-flex gap-2 w-100 mt-2">
                <button class="btn btn-secondary w-50" @click="backToForm" :disabled="isLoading">
                  Voltar
                </button>
                <button 
                  class="btn btn-primary w-50" 
                  @click="confirmDeliveryMethod" 
                  :disabled="!tempDeliveryMethod || isLoading"
                >
                  Continuar
                </button>
              </div>
            </div>
          </div>

          <!-- ETAPA 4: FORMA DE PAGAMENTO (usando o modal) -->
          <div v-show="currentStep === 'payment'" class="step-section text-center py-4">
            <div class="text-center mb-4">
              <h4 class="fw-semibold text-secondary">Selecione a forma de pagamento</h4>
              <p class="text-muted small">Clique no botão abaixo para escolher como deseja pagar</p>
            </div>

            <div class="d-flex flex-column align-items-center gap-3">
              <div 
                v-if="tempPaymentMethod" 
                class="selected-option-card p-3 rounded border border-success bg-light"
              >
                <h6 class="mb-1 text-success">✅ Forma de pagamento selecionada:</h6>
                <p class="mb-0 fw-semibold">{{ getPaymentMethodLabel(tempPaymentMethod) }}</p>
              </div>

              <button 
                class="btn btn-primary w-100" 
                @click="openPaymentMethodModal"
                :disabled="isLoading"
              >
                {{ tempPaymentMethod ? 'Alterar forma de pagamento' : 'Selecionar forma de pagamento' }}
              </button>

              <div class="d-flex gap-2 w-100 mt-2">
                <button class="btn btn-secondary w-50" @click="backToDeliveryStep" :disabled="isLoading">
                  Voltar
                </button>
                <button 
                  class="btn btn-primary w-50" 
                  @click="submitForm" 
                  :disabled="!tempPaymentMethod || isLoading"
                >
                  <span v-if="isLoading">Finalizando...</span>
                  <span v-else>Continuar</span>
                </button>
              </div>
            </div>
          </div>

          <!-- Indicador de loading global -->
          <div v-if="isLoading" class="loading-spinner position-absolute d-flex justify-content-center align-items-center w-100 h-100">
            <div class="spinner-border" role="status">
              <span class="visually-hidden">Carregando...</span>
            </div>
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

  <!-- Delivery Method Modal -->
  <DeliveryMethodModal
    v-model="showDeliveryMethodModal"
    @method-selected="handleDeliveryMethodSelected"
  />

  <!-- Payment Method Modal -->
  <PaymentMethodModal
    v-model="showPaymentMethodModal"
    :selected-value="tempPaymentMethod"
    @payment-selected="handlePaymentMethodSelected"
  />
</template>

<script setup>
import { ref, watch, nextTick, computed } from 'vue'
import { useToast } from 'vue-toastification'
import AddressModal from './AddressModal.vue'
import DeliveryMethodModal from './DeliveryMethodModal.vue'
import PaymentMethodModal from './PaymentMethodModal.vue'
import { useUserStore } from '@/stores/useUserStore'

const toast = useToast()
const props = defineProps({
  modelValue: Boolean
})

const emit = defineEmits(['update:modelValue', 'submit'])

const userStore = useUserStore()

// Dados do formulário
const whatsapp = ref('')
const fullName = ref('')

// Controle de etapas
const currentStep = ref('form') // form, cep, delivery, payment
const isLoading = ref(false)
const userFound = ref(false)
const isLoggedIn = ref(false)

// CEP fields
const cepDigits = ref(['', '', '', '', '', '', '', ''])
const cepError = ref('')
const cepInputs = ref([])
const expectedCep = ref('')

// Controle do modal de endereços
const showAddressModal = ref(false)
const pendingAddressSelection = ref(false)

// Modais de entrega e pagamento
const showDeliveryMethodModal = ref(false)
const showPaymentMethodModal = ref(false)

// Dados temporários para entrega e pagamento
const tempDeliveryMethod = ref(null)
const tempPaymentMethod = ref('')

// Referências para os elementos do DOM
const formSectionRef = ref(null)
const cepSectionRef = ref(null)

// Computed para verificar se CEP está completo
const isCepComplete = computed(() => {
  return cepDigits.value.every(digit => digit !== '')
})

// Função para obter o CEP completo
const getFullCep = () => {
  return cepDigits.value.join('')
}

// Fecha o modal e reseta
const close = () => {
  emit('update:modelValue', false)
  resetModal()
}

// Reseta o estado do modal
const resetModal = () => {
  setTimeout(() => {
    currentStep.value = 'form'
    userFound.value = false
    isLoading.value = false
    cepDigits.value = ['', '', '', '', '', '', '', '']
    cepError.value = ''
    expectedCep.value = ''
    pendingAddressSelection.value = false
    tempDeliveryMethod.value = null
    tempPaymentMethod.value = ''
  }, 300)
}

// Formatação do WhatsApp
const formatWhatsapp = () => {
  let v = whatsapp.value.replace(/\D/g, '')
  if (v.length > 11) v = v.slice(0, 11)
  if (v.length > 6) {
    whatsapp.value = `(${v.slice(0,2)}) ${v.slice(2,7)}-${v.slice(7)}`
  } else if (v.length > 2) {
    whatsapp.value = `(${v.slice(0,2)}) ${v.slice(2)}`
  } else if (v.length > 0) {
    whatsapp.value = `(${v}`
  }
}

// Busca o CEP do endereço principal nos addresses salvos
const getPrimaryAddressCep = () => {
  const addresses = localStorage.getItem('addresses')
  if (addresses) {
    const parsedAddresses = JSON.parse(addresses)
    const primaryAddress = parsedAddresses.find(addr => addr.primary === true)
    if (primaryAddress && primaryAddress.cep) {
      return primaryAddress.cep.replace(/\D/g, '')
    }
  }
  return null
}

// Simula verificação de usuário no backend
const checkUserExists = async (whatsappNumber, name) => {
  return new Promise((resolve) => {
    setTimeout(() => {
      const savedData = localStorage.getItem('userData')
      if (savedData) {
        const data = JSON.parse(savedData)
        const exists = data.whatsapp === whatsappNumber && data.fullName === name
        resolve(exists)
      } else {
        resolve(true)
      }
    }, 500)
  })
}

// Manipula seleção de endereço vindo do AddressModal
const handleAddressSelected = async (address) => {
  showAddressModal.value = false
  
  if (address && pendingAddressSelection.value) {
    pendingAddressSelection.value = false
    
    // Salva o endereço selecionado
    const userData = {
      whatsapp: whatsapp.value,
      fullName: fullName.value,
      selectedAddress: address
    }
    
    // Se já tinha dados salvos, mescla
    const existingData = localStorage.getItem('userData')
    if (existingData) {
      const parsed = JSON.parse(existingData)
      Object.assign(userData, parsed)
    }
    
    localStorage.setItem('userData', JSON.stringify(userData))
    
    toast.success('Endereço selecionado com sucesso!', { timeout: 3000 })
  }
}

// Verifica se o usuário está logado
const checkIfLoggedIn = () => {
  if (userStore.isLogged) {
    whatsapp.value = userStore.whatsapp || ''
    fullName.value = userStore.fullName || ''
    isLoggedIn.value = true
    userFound.value = true
    return true
  }
  return false
}

// Abre o modal de seleção de entrega
const openDeliveryMethodModal = () => {
  showDeliveryMethodModal.value = true
}

// Manipula a seleção do método de entrega
const handleDeliveryMethodSelected = (method) => {
  tempDeliveryMethod.value = method
  showDeliveryMethodModal.value = false
  toast.info(`Forma de entrega selecionada: ${method.label}`, { timeout: 2000 })
}

// Obtém o texto do tempo estimado do método de entrega
const getDeliveryMethodTime = (method) => {
  if (!method) return ''
  if (method.timeEstimate) return `⏱️ ${method.timeEstimate}`
  return ''
}

// Confirma o método de entrega e avança para pagamento
const confirmDeliveryMethod = async () => {
  if (!tempDeliveryMethod.value) {
    toast.warning('Selecione uma forma de entrega!', { timeout: 3000 })
    return
  }
  
  currentStep.value = 'payment'
}

// Volta para a etapa de entrega
const backToDeliveryStep = () => {
  currentStep.value = 'delivery'
}

// Abre o modal de seleção de pagamento
const openPaymentMethodModal = () => {
  showPaymentMethodModal.value = true
}

// Manipula a seleção do método de pagamento
const handlePaymentMethodSelected = (method) => {
  tempPaymentMethod.value = method
  showPaymentMethodModal.value = false
  toast.info(`Forma de pagamento selecionada: ${getPaymentMethodLabel(method)}`, { timeout: 2000 })
}

// Obtém o label do método de pagamento
const getPaymentMethodLabel = (method) => {
  const labels = {
    card: 'Cartão de Crédito/Débito',
    pix: 'PIX',
    cash: 'Dinheiro'
  }
  return labels[method] || method
}

// Logout do usuário
const logout = () => {
  const userName = userStore.fullName
  userStore.logout()
  
  isLoggedIn.value = false
  userFound.value = false
  whatsapp.value = ''
  fullName.value = ''
  tempPaymentMethod.value = ''
  tempDeliveryMethod.value = null
  
  toast.info(`Até mais, ${userName}! Você saiu da sua conta.`, { timeout: 4000 })
  close()
}

// Verifica dados iniciais
const checkAndAdvanceToDelivery = async () => {
  if (isLoggedIn.value) {
    logout()
    return
  }

  if (!whatsapp.value || !fullName.value) {
    toast.warning('Preencha todos os campos!', { timeout: 3000 })
    return
  }

  isLoading.value = true

  try {
    userFound.value = await checkUserExists(whatsapp.value, fullName.value)

    if (userFound.value) {
      expectedCep.value = getPrimaryAddressCep()
      
      if (expectedCep.value) {
        currentStep.value = 'cep'
        await nextTick()
        cepDigits.value = ['', '', '', '', '', '', '', '']
        cepError.value = ''
        if (cepInputs.value[0]) {
          cepInputs.value[0].focus()
        }
        toast.info('Digite o CEP de validação', { timeout: 3000 })
      } else {
        // Usuário existente mas sem CEP, vai direto para entrega
        currentStep.value = 'delivery'
      }
    } else {
      // Novo usuário, vai para entrega
      currentStep.value = 'delivery'
    }
  } catch (error) {
    console.error('Erro ao verificar usuário:', error)
    toast.error('Erro ao verificar dados. Tente novamente.', { timeout: 3000 })
  } finally {
    isLoading.value = false
  }
}

// Voltar para o formulário inicial
const backToForm = () => {
  currentStep.value = 'form'
  cepDigits.value = ['', '', '', '', '', '', '', '']
  cepError.value = ''
  tempDeliveryMethod.value = null
  tempPaymentMethod.value = ''
}

// Valida o CEP digitado e faz login
const validateCepAndLogin = () => {
  const typedCep = getFullCep()
  
  if (typedCep === expectedCep.value) {
    cepError.value = ''
    const data = {
      whatsapp: whatsapp.value,
      fullName: fullName.value
    }
    
    userStore.login(data)
    isLoggedIn.value = true
    
    // Avança para entrega
    currentStep.value = 'delivery'
  } else {
    cepError.value = 'CEP não encontrado! Verifique o CEP cadastrado.'
    toast.error(cepError.value, { timeout: 3000 })
    cepDigits.value = ['', '', '', '', '', '', '', '']
    if (cepInputs.value[0]) {
      cepInputs.value[0].focus()
    }
  }
}

// Manipula a digitação do CEP (auto-tab)
const handleCepDigit = (index, event) => {
  const value = event.target.value.replace(/\D/g, '')
  
  if (value.length > 0) {
    cepDigits.value[index] = value.charAt(0)
    
    if (index < 7 && cepDigits.value[index] !== '') {
      if (cepInputs.value[index + 1]) {
        cepInputs.value[index + 1].focus()
      }
    }
  } else {
    cepDigits.value[index] = ''
  }
}

// Manipula tecla backspace para voltar
const handleCepKeydown = (index, event) => {
  if (event.key === 'Backspace') {
    if (cepDigits.value[index] === '' && index > 0) {
      if (cepInputs.value[index - 1]) {
        cepInputs.value[index - 1].focus()
      }
    } else if (cepDigits.value[index] !== '') {
      cepDigits.value[index] = ''
      event.preventDefault()
    }
  }
}

// Manipula colagem de CEP completo
const handleCepPaste = (event) => {
  event.preventDefault()
  const pastedText = event.clipboardData.getData('text').replace(/\D/g, '')
  const digits = pastedText.split('').slice(0, 8)
  
  digits.forEach((digit, idx) => {
    if (idx < 8) {
      cepDigits.value[idx] = digit
    }
  })
  
  const nextEmptyIndex = cepDigits.value.findIndex(d => d === '')
  if (nextEmptyIndex !== -1 && cepInputs.value[nextEmptyIndex]) {
    cepInputs.value[nextEmptyIndex].focus()
  } else if (cepInputs.value[7]) {
    cepInputs.value[7].focus()
  }
}

// Submissão final do formulário
const submitForm = async () => {
  if (!tempPaymentMethod.value) {
    toast.warning('Selecione uma forma de pagamento!', { timeout: 3000 })
    return
  }

  if (!tempDeliveryMethod.value) {
    toast.warning('Selecione uma forma de entrega!', { timeout: 3000 })
    return
  }

  isLoading.value = true

  const userData = localStorage.getItem('userData')
  let selectedAddress = null
  
  if (userData) {
    const parsed = JSON.parse(userData)
    selectedAddress = parsed.selectedAddress
  }

  // Para delivery, verifica se tem endereço
  if (tempDeliveryMethod.value.value === 'delivery' && !selectedAddress) {
    pendingAddressSelection.value = true
    showAddressModal.value = true
    isLoading.value = false
    return
  }

  const data = {
    whatsapp: whatsapp.value,
    fullName: fullName.value,
    deliveryMethod: tempDeliveryMethod.value,
    paymentMethod: tempPaymentMethod.value,
    selectedAddress: selectedAddress,
    completedAt: new Date().toISOString()
  }

  try {
    userStore.login(data)
    isLoggedIn.value = true
    
    // toast.success(`Pedido finalizado com sucesso, ${fullName.value}!`, { timeout: 4000 })
    
    const loginEvent = new CustomEvent('user-login', { 
      detail: { 
        fullName: fullName.value, 
        whatsapp: whatsapp.value,
        isLogged: true,
        deliveryMethod: tempDeliveryMethod.value,
        paymentMethod: tempPaymentMethod.value,
        selectedAddress: selectedAddress
      } 
    })
    window.dispatchEvent(loginEvent)
    
    emit('submit', data)
    close()
  } catch (error) {
    console.error('Erro ao salvar dados:', error)
    toast.error('Erro ao finalizar. Tente novamente.', { timeout: 3000 })
  } finally {
    isLoading.value = false
  }
}

// Quando abrir o modal, carrega dados salvos
watch(() => props.modelValue, async (open) => {
  if (open) {
    currentStep.value = 'form'
    cepDigits.value = ['', '', '', '', '', '', '', '']
    cepError.value = ''
    expectedCep.value = ''
    pendingAddressSelection.value = false
    tempDeliveryMethod.value = null
    tempPaymentMethod.value = ''
    
    const loggedIn = checkIfLoggedIn()
    
    if (!loggedIn) {
      fullName.value = ''
      whatsapp.value = ''
      userFound.value = false
      isLoggedIn.value = false
    }
  }
})

watch(() => props.modelValue, async (open) => {
  if (open) {
    await nextTick()
    // Força o foco no primeiro input
    const firstInput = document.querySelector('.identify-modal input')
    if (firstInput) {
      firstInput.focus()
    }
    // Garante que o modal está visível
    document.body.style.overflow = 'hidden'
  } else {
    document.body.style.overflow = ''
  }
})
</script>

<style scoped>
    .identify-modal {
      position: relative;
      z-index: 1060;
    }

    .modal-overlay {
      z-index: 1055;
    }
    .cep-box {
      width: 45px;
      height: 45px;
      border-radius: 8px;
      font-size: 20px;
      text-align: center;
      font-weight: 600;
    }

    .cep-box:focus {
      border-color: var(--bg-orange);
      outline: none;
      box-shadow: 0 0 0 2px rgba(255, 140, 0, 0.2);
    }
    
    .selected-option-card {
      width: 100%;
      text-align: center;
    }
    
    .payment-form-title{
      font-size: clamp(0.938rem, 1vw, 1rem);
    }
    .svg{
        width: 16px;
    }
    .modal-title{
      font-size: clamp(1rem, 1.125vw, 1.125rem);
    }
    .header-modal{
        background: #FFF1C3;
    }
    .modal-content {
        border-radius: 0px;
        max-width: 680px;
        width: 100%;
    }

    .modal-body input {
        height: 42px;
    }
    
    .options-box {
      border: 1px solid #e5e5e5;
      border-radius: 10px;
      background: #fff;
      padding: 8px;
    }
    
    .option-card {
      display: flex;
      gap: 10px;
      padding: 10px;
      border-radius: 8px;
      cursor: pointer;
      align-items: center;
      border: 1px solid transparent;
      margin-bottom: 4px;
    }
    
    .option-card:last-child {
      margin-bottom: 0;
    }
    
    .option-card:hover {
      background: #f5f5f5;
    }
    
    .option-card input {
      accent-color: #A4268E;
    }
    .loading-spinner{
      top: 0;
      left: 0;
    }
    .spinner-border{
      color: #A4268E;
    }
    .option-card small {
      display: block;
      color: #777;
      font-size: 0.75rem;
    }
    
    .form-label {
      font-weight: 500;
      margin-bottom: 8px;
      display: block;
    }

  .step-section {
    transition: all 0.3s ease;
    animation: fadeIn 0.3s ease;
  }

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.btn-primary, .btn-secondary {
  margin-top: 8px;
}

.loading-overlay {
  position: relative;
  opacity: 0.6;
  pointer-events: none;
}
@media (max-width: 475px) {
  .brasil-icon{
    width: 20px;
  }
  .input-group-text.rounded, .modal-body input{
    font-size: 0.983rem;
    height: 40px;
  }
}
</style>