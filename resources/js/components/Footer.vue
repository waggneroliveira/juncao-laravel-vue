<template>
  <footer class="footer">
    <div class="container container-footer">

      <!-- LOGO -->
      <div class="footer-col logo-col">
        <img 
          src="@/assets/images/logo.png" 
          alt="Oxente Açaiteria"
          class="logo"
        />
      </div>

      <!-- MENU -->
      <div class="footer-col">
        <h4>Menu</h4>
        <ul>
          <li>Áreas de entregas</li>
          <li>Fale Conosco</li>
          <li>Meus endereços</li>
          <li>Meu carrinho</li>
        </ul>
      </div>

      <!-- ATENDIMENTO -->
      <div class="footer-col">
        <h4>Atendimento</h4>
        <ul>
          <li>(71) 9 0000-0000</li>
          <li>oxenteacaiteria@gmail.com</li>
        </ul>
      </div>

      <!-- LOCALIZAÇÃO -->
      <div class="footer-col location-col">
        <h4>Localização</h4>
        <p class="text-white">Lauro de Freitas - BA</p>

        <div class="delivery">
          <span class="f-icon">F</span>
          <div class="delivery-text">
            <span>DELI</span>
            <strong>FAST</strong>
          </div>
        </div>
      </div>

    </div>
  </footer>
</template>

<script>
export default {
  name: "Footer"
}
</script>

<style scoped>
.footer {
  background: var(--primary);
  color: #fff;
  padding: 30px 20px;
}

.container-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
}

/* COLUNAS */
.footer-col {
  min-width: 180px;
}

.footer-col h4 {
  font-weight: 600;
  margin-bottom: 10px;
}

.footer-col ul {
  list-style: none;
  padding: 0;
}

.footer-col ul li {
  margin-bottom: 6px;
  font-size: 14px;
}

/* LOGO */
.logo-col {
  display: flex;
  align-items: center;
}

.logo {
  width: 90px;
  height: auto;
}

/* LOCALIZAÇÃO */
.location-col p {
  margin-bottom: 10px;
}

/* DELIVERY ICON */
.delivery {
  display: flex;
  align-items: center;
  gap: 8px;
}

.f-icon {
  font-size: 40px;
  font-weight: bold;
}

.delivery-text {
  display: flex;
  flex-direction: column;
  line-height: 1;
}

.delivery-text span {
  font-size: 12px;
}

.delivery-text strong {
  font-size: 16px;
}

/* RESPONSIVO */
@media (max-width: 768px) {
  .container-footer {
    flex-direction: column;
    align-items: flex-start;
    gap: 20px;
  }

  .logo {
    width: 70px;
  }
}
</style>