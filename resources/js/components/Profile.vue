<template>
  <div
    v-if="modelValue"
    class="modal fade show modal-overlay"
    tabindex="-1"
    style="display: block;"
    @click.self="close"
  >
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">

        <!-- HEADER -->
        <div class="modal-header py-2">
          <h5 class="modal-title">
            <i class="bi bi-person-circle me-2"></i>Meu Perfil
          </h5>
          <button type="button" class="btn-close" @click="close"></button>
        </div>

        <div class="modal-body">

          <!-- Input de arquivo ÚNICO fora das views condicionais -->
          <input 
            type="file" 
            ref="avatarInput" 
            style="display: none" 
            accept="image/jpeg,image/png,image/jpg,image/gif"
            @change="handleImageUpload"
          >

          <!-- VISUALIZAÇÃO DO PERFIL -->
          <div v-if="view === 'view'" class="profile-view">
            
            <!-- Avatar e Info -->
            <div class="text-center mb-4">
              <div class="avatar-container mb-3" @click="triggerImageUpload">
                <img 
                  v-if="profile.avatar" 
                  :src="profile.avatar" 
                  class="avatar-img"
                  alt="Avatar"
                >
                <div v-else class="avatar-placeholder">
                  <i class="bi bi-person-fill"></i>
                </div>
                <div class="avatar-edit-overlay">
                  <i class="bi bi-camera-fill"></i>
                </div>
              </div>
              <h5 class="mb-1">{{ profile.nome || 'Usuário' }}</h5>
              <p class="text-muted small">Clique na foto para alterar</p>
            </div>

            <!-- Informações do Perfil -->
            <div class="profile-info">
              <div class="info-card mb-3">
                <div class="info-label">
                  <i class="bi bi-person"></i>
                  <span>Nome Completo</span>
                </div>
                <div class="info-value">{{ profile.nome || 'Não informado' }}</div>
              </div>

              <div class="info-card mb-3">
                <div class="info-label">
                  <i class="bi bi-telephone"></i>
                  <span>Telefone</span>
                </div>
                <div class="info-value">{{ formatPhone(profile.telefone) || 'Não informado' }}</div>
              </div>


              <div class="info-card mb-3" v-if="profile.genero">
                <div class="info-label">
                  <i class="bi bi-gender-ambiguous"></i>
                  <span>Gênero</span>
                </div>
                <div class="info-value">{{ formatGenero(profile.genero) }}</div>
              </div>
            </div>

            <!-- Botões de Ação -->
            <div class="action-buttons mt-4 d-flex justify-content-end align-items-center">
              <button class="btn btn-outline-secondary me-2" @click="close">
                <i class="bi bi-x-circle me-2"></i>Fechar
              </button>
              <button class="btn btn-primary" @click="editProfile">
                <i class="bi bi-pencil-square me-2"></i>Editar Perfil
              </button>
            </div>
          </div>

          <!-- FORMULÁRIO DE EDIÇÃO -->
          <div v-else>
            <h6 class="mb-3">Editar Perfil</h6>

            <div class="row g-3">
              <div class="col-12 text-center mb-3">
                <div class="avatar-container-edit mb-2" @click="triggerImageUpload">
                  <img 
                    v-if="form.avatar" 
                    :src="form.avatar" 
                    class="avatar-img-edit"
                    alt="Avatar"
                  >
                  <div v-else class="avatar-placeholder-edit">
                    <i class="bi bi-person-fill"></i>
                  </div>
                  <div class="avatar-edit-overlay-edit">
                    <i class="bi bi-camera-fill"></i>
                  </div>
                </div>
                <small class="text-muted">Clique para alterar a foto</small>
              </div>

              <div class="col-12 col-md-7">
                <label class="form-label">
                  Nome Completo <span class="text-danger">*</span>
                </label>
                <input 
                  v-model="form.nome" 
                  class="form-control" 
                  placeholder="Digite seu nome completo"
                  :class="{ 'is-invalid': errors.nome }"
                >
                <div class="invalid-feedback" v-if="errors.nome">{{ errors.nome }}</div>
              </div>

              <div class="col-12 col-md-5">
                <label class="form-label">
                  Telefone <span class="text-danger">*</span>
                </label>
                <input 
                  v-model="form.telefone" 
                  class="form-control" 
                  placeholder="(00) 00000-0000"
                  maxlength="16"
                  @input="handlePhoneInput"
                  :class="{ 'is-invalid': errors.telefone }"
                >
                <div class="invalid-feedback" v-if="errors.telefone">{{ errors.telefone }}</div>
              </div>

              <div class="col-md-12">
                <label class="form-label">E-mail <small>(opicional)</small></label>
                <input 
                  v-model="form.email" 
                  class="form-control" 
                  type="email"
                  placeholder="seu@email.com"
                  :class="{ 'is-invalid': errors.email }"
                >
                <div class="invalid-feedback" v-if="errors.email">{{ errors.email }}</div>
              </div>

              <div class="col-md-6">
                <label class="form-label">Data de Nascimento <small>(opicional)</small></label>
                <input 
                  v-model="form.dataNascimento" 
                  class="form-control" 
                  type="date"
                  :max="getMaxDate()"
                >
              </div>

              <div class="col-md-6">
                <label class="form-label">Gênero <small>(opicional)</small></label>
                <select v-model="form.genero" class="form-select">
                  <option value="">Selecione</option>
                  <option value="masculino">Masculino</option>
                  <option value="feminino">Feminino</option>
                  <option value="nao_binario">Não Binário</option>
                  <option value="prefiro_nao_dizer">Prefiro não dizer</option>
                  <option value="outro">Outro</option>
                </select>
              </div>
            </div>

            <div class="d-flex justify-content-end align-items-center mt-4">
              <button class="btn btn-secondary" @click="cancelEdit">
                Cancelar
              </button>
              <button class="btn btn-primary ms-2" @click="saveProfile">
                Salvar Perfil
              </button>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, nextTick } from 'vue'
import { useToast } from 'vue-toastification'

const toast = useToast()

const props = defineProps({
  modelValue: Boolean
})

const emit = defineEmits(['update:modelValue', 'profile-updated'])

const view = ref('view') // 'view' ou 'edit'
const avatarInput = ref(null)
const errors = ref({})

// Perfil do usuário
const profile = ref({
  nome: '',
  telefone: '',
  email: '',
  dataNascimento: '',
  genero: '',
  avatar: null
})

// Formulário de edição
const form = ref({
  nome: '',
  telefone: '',
  email: '',
  dataNascimento: '',
  genero: '',
  avatar: null
})

// Carregar perfil do localStorage
const loadProfile = () => {
  const savedProfile = localStorage.getItem('userProfile')
  if (savedProfile) {
    profile.value = JSON.parse(savedProfile)
    // Reset do formulário com os dados atuais
    form.value = { ...profile.value }
  } else {
    // Perfil padrão
    const defaultProfile = {
      nome: '',
      telefone: '',
      email: '',
      dataNascimento: '',
      genero: '',
      avatar: null
    }
    profile.value = defaultProfile
    form.value = { ...defaultProfile }
  }
}

// Salvar perfil no localStorage
const saveProfileToStorage = () => {
  localStorage.setItem('userProfile', JSON.stringify(profile.value))
  emit('profile-updated', profile.value)
  
  // Disparar evento para outros componentes
  window.dispatchEvent(new CustomEvent('profile-updated'))
  localStorage.setItem('profileUpdated', Date.now().toString())
}

// Formatar telefone
const handlePhoneInput = () => {
  let v = form.value.telefone.replace(/\D/g, '')
  
  if (v.length > 11) {
    v = v.slice(0, 11)
  }
  
  if (v.length > 0) {
    v = v.replace(/^(\d{2})(\d)/g, '($1) $2')
  }
  
  if (v.length > 10) {
    v = v.replace(/^(\d{2}) (\d{5})(\d{4})$/, '($1) $2-$3')
  } else if (v.length > 6) {
    v = v.replace(/^(\d{2}) (\d{4})(\d{0,4})$/, '($1) $2-$3')
  }
  
  form.value.telefone = v
}

const formatPhone = (phone) => {
  if (!phone) return ''
  return phone
}

const formatDate = (date) => {
  if (!date) return ''
  const [year, month, day] = date.split('-')
  return `${day}/${month}/${year}`
}

const formatGenero = (genero) => {
  const generos = {
    'masculino': 'Masculino',
    'feminino': 'Feminino',
    'nao_binario': 'Não Binário',
    'prefiro_nao_dizer': 'Prefiro não dizer',
    'outro': 'Outro'
  }
  return generos[genero] || genero
}

const getMaxDate = () => {
  const today = new Date()
  const year = today.getFullYear() - 18 // Maior de 18 anos
  return `${year}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`
}

// Upload de imagem
const triggerImageUpload = () => {
  if (avatarInput.value) {
    avatarInput.value.click()
  } else {
    // Fallback: tenta encontrar qualquer input file no modal
    const fileInput = document.querySelector('input[type="file"][accept*="image"]')
    if (fileInput) {
      fileInput.click()
    } else {
      toast.error('Erro ao abrir seletor de imagem', { timeout: 3000 })
    }
  }
}

const handleImageUpload = (event) => {
  const file = event.target.files[0]
  
  if (file && file.type.startsWith('image/')) {
    // Valida tamanho máximo (5MB)
    if (file.size > 5 * 1024 * 1024) {
      toast.error('A imagem deve ter no máximo 5MB', { timeout: 3000 })
      return
    }
    
    const reader = new FileReader()
    reader.onload = (e) => {
      const avatarUrl = e.target.result
      if (view.value === 'edit') {
        form.value.avatar = avatarUrl
        toast.success('Foto alterada! Clique em "Salvar Perfil" para confirmar.', { timeout: 3000 })
      } else {
        profile.value.avatar = avatarUrl
        form.value.avatar = avatarUrl
        saveProfileToStorage()
        toast.success('Foto atualizada com sucesso!', { timeout: 3000 })
      }
    }
    reader.onerror = () => {
      toast.error('Erro ao carregar a imagem', { timeout: 3000 })
    }
    reader.readAsDataURL(file)
    
    // Limpa o input para permitir o mesmo arquivo novamente
    event.target.value = ''
  } else {
    toast.error('Por favor, selecione uma imagem válida (JPG, PNG, GIF)', { timeout: 3000 })
  }
}

// Validação
const validateForm = () => {
  errors.value = {}
  
  // Validação do Nome (obrigatório)
  if (!form.value.nome || form.value.nome.trim() === '') {
    errors.value.nome = 'Nome completo é obrigatório'
  } else if (form.value.nome.trim().length < 3) {
    errors.value.nome = 'Nome deve ter no mínimo 3 caracteres'
  } else if (form.value.nome.trim().split(' ').length < 2) {
    errors.value.nome = 'Por favor, informe seu nome completo'
  }
  
  // Validação do Telefone (obrigatório)
  if (!form.value.telefone || form.value.telefone.trim() === '') {
    errors.value.telefone = 'Telefone é obrigatório'
  } else {
    const phoneDigits = form.value.telefone.replace(/\D/g, '')
    if (phoneDigits.length !== 11) {
      errors.value.telefone = 'Telefone deve ter 11 dígitos (DDD + 9 dígitos)'
    }
  }
  
  // Validação do E-mail (opcional, mas se preenchido deve ser válido)
  if (form.value.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.value.email)) {
    errors.value.email = 'E-mail inválido'
  }
  
  return Object.keys(errors.value).length === 0
}

// Ações do perfil
const editProfile = async () => {
  form.value = { ...profile.value }
  view.value = 'edit'
  // Aguarda o DOM ser atualizado
  await nextTick()
}

const cancelEdit = async () => {
  form.value = { ...profile.value }
  view.value = 'view'
  await nextTick()
}

const saveProfile = () => {
  if (validateForm()) {
    profile.value = { ...form.value }
    saveProfileToStorage()
    view.value = 'view'
    toast.success('Perfil atualizado com sucesso!', { timeout: 3000 })
  } else {
    toast.error('Por favor, corrija os erros no formulário', { timeout: 3000 })
  }
}

// Fechar modal
const close = () => {
  emit('update:modelValue', false)
  view.value = 'view'
  form.value = { ...profile.value }
  errors.value = {}
}

// Observar quando o modal abre
watch(() => props.modelValue, async (newValue) => {
  if (newValue) {
    loadProfile()
    view.value = 'view'
    errors.value = {}
    // Aguarda a renderização do DOM
    await nextTick()
  }
})

// Inicializar
onMounted(() => {
  loadProfile()
})
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  backdrop-filter: blur(4px);
}

.modal-container {
  background: white;
  border-radius: 16px;
  width: 90%;
  max-width: 700px;
  max-height: 85vh;
  display: flex;
  flex-direction: column;
  box-shadow: 0 20px 35px rgba(0, 0, 0, 0.2);
  animation: slideIn 0.3s ease-out;
  overflow: hidden;
}

@keyframes slideIn {
  from {
    transform: translateY(-30px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.modal-title {
  font-size: clamp(1rem, 1.125vw, 1.125rem);
}

h6, .form-label, .form-check-label {
  font-size: clamp(0.938rem, 1vw, 1rem);
}

.text-danger {
  color: #dc3545;
}

/* Avatar estilos */
.avatar-container {
  position: relative;
  width: 120px;
  height: 120px;
  margin: 0 auto;
  cursor: pointer;
  border-radius: 50%;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: transform 0.2s ease;
}

.avatar-container:hover {
  transform: scale(1.05);
}

.avatar-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.avatar-placeholder {
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
}

.avatar-placeholder i {
  font-size: 60px;
}

.avatar-edit-overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(0,0,0,0.7);
  color: white;
  text-align: center;
  padding: 8px;
  font-size: 20px;
  transform: translateY(100%);
  transition: transform 0.3s ease;
}

.avatar-container:hover .avatar-edit-overlay {
  transform: translateY(0);
}

/* Avatar para edição */
.avatar-container-edit {
  position: relative;
  width: 100px;
  height: 100px;
  margin: 0 auto;
  cursor: pointer;
  border-radius: 50%;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: transform 0.2s ease;
}

.avatar-container-edit:hover {
  transform: scale(1.05);
}

.avatar-img-edit {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.avatar-placeholder-edit {
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
}

.avatar-placeholder-edit i {
  font-size: 50px;
}

.avatar-edit-overlay-edit {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(0,0,0,0.7);
  color: white;
  text-align: center;
  padding: 6px;
  font-size: 16px;
  transform: translateY(100%);
  transition: transform 0.3s ease;
}

.avatar-container-edit:hover .avatar-edit-overlay-edit {
  transform: translateY(0);
}

/* Cards de informação */
.info-card {
  background: #f8f9fa;
  border-radius: 10px;
  padding: 15px;
  transition: all 0.3s ease;
}

.info-card:hover {
  background: #f1f3f5;
  transform: translateX(5px);
}

.info-label {
  font-size: 0.85rem;
  color: var(--text-medium);
  margin-bottom: 5px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.info-label i {
  font-size: 1rem;
}

.info-value {
  font-size: 1rem;
  font-weight: 500;
  color: #212529;
  margin-left: 24px;
}

/* Botões */
.btn-primary {
  border-color: var(--primary) !important;
  color: #FFF !important;
  font-weight: 600;
}

.btn-primary:hover {
  background: var(--bg-primary-hover) !important;
  border-color: var(--bg-primary-hover) !important;
  transform: translateY(-1px);
}

.btn-secondary {
  background: var(--text-medium) !important;
  border-color: var(--text-medium) !important;
}

.btn-secondary:hover {
  background: #5a6268 !important;
  transform: translateY(-1px);
}

.btn-outline-secondary {
  color: var(--text-medium) !important;
  border-color: var(--text-medium) !important;
}

.btn-outline-secondary:hover {
  background: var(--text-medium) !important;
  color: #fff !important;
  transform: translateY(-1px);
}

/* Animações */
.profile-view {
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsividade */
@media (max-width: 768px) {
  .avatar-container {
    width: 100px;
    height: 100px;
  }
  
  .avatar-container-edit {
    width: 80px;
    height: 80px;
  }
  
  .info-value {
    font-size: 0.9rem;
  }
  
  .info-label {
    font-size: 0.75rem;
  }
}
</style>