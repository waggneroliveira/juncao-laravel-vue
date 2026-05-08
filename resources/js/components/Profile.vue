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

        <div class="modal-body" :class="{ 'loading-overlay': isLoading }">

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

              <div class="info-card mb-3">
                <div class="info-label">
                  <i class="bi bi-envelope"></i>
                  <span>E-mail</span>
                </div>
                <div class="info-value">{{ profile.email || 'Não informado' }}</div>
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
                <label class="form-label">E-mail</label>
                <input 
                  v-model="form.email" 
                  class="form-control" 
                  type="email"
                  placeholder="seu@email.com"
                  :class="{ 'is-invalid': errors.email }"
                >
                <div class="invalid-feedback" v-if="errors.email">{{ errors.email }}</div>
              </div>

              <div class="col-md-6 d-none">
                <label class="form-label">Data de Nascimento <small>(opcional)</small></label>
                <input 
                  v-model="form.dataNascimento" 
                  class="form-control" 
                  type="date"
                  disabled
                  :max="getMaxDate()"
                >
              </div>

              <div class="col-md-6 d-none">
                <label class="form-label">Gênero <small>(opcional)</small></label>
                <select v-model="form.genero" class="form-select disabled">
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
              <button class="btn btn-primary ms-2" @click="saveProfile" :disabled="isLoading">
                <span v-if="isLoading" class="spinner-border spinner-border-sm me-2"></span>
                Salvar Perfil
              </button>
            </div>
          </div>

          <!-- Indicador de loading -->
          <div v-if="isLoading" class="loading-spinner position-absolute d-flex justify-content-center align-items-center w-100 h-100">
            <div class="spinner-border" role="status">
              <span class="visually-hidden">Carregando...</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Input de arquivo oculto -->
    <input 
      type="file" 
      ref="avatarInput" 
      style="display: none" 
      accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
      @change="handleImageUpload"
    >
  </div>
</template>

<script setup>
import { ref, watch, onMounted, nextTick } from 'vue'
import { useToast } from 'vue-toastification'
import { useUserStore } from '@/stores/useUserStore'
import axios from 'axios'

const toast = useToast()
const userStore = useUserStore()

const props = defineProps({
  modelValue: Boolean
})

const emit = defineEmits(['update:modelValue', 'profile-updated'])

const view = ref('view')
const avatarInput = ref(null)
const errors = ref({})
const isLoading = ref(false)

// Perfil do usuário
const profile = ref({
  id: null,
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

// Buscar perfil do backend
const loadProfile = async () => {
  if (!userStore.isLogged) return
  
  isLoading.value = true
  try {
    const response = await axios.get('/client/profile')
    
    if (response.data.success) {
      const data = response.data.profile
      profile.value = {
        id: data.id,
        nome: data.nome,
        telefone: data.telefone,
        email: data.email,
        dataNascimento: data.data_nascimento || '',
        genero: data.genero || '',
        avatar: data.avatar
      }
      form.value = { ...profile.value }
      
      // Sincronizar com userStore
      if (userStore.id === data.id) {
        userStore.fullName = data.nome
        userStore.whatsapp = data.telefone
        userStore.email = data.email
        userStore.saveToStorage()
      }
    }
  } catch (error) {
    console.error('Erro ao carregar perfil:', error)
    toast.error('Erro ao carregar perfil')
  } finally {
    isLoading.value = false
  }
}

// Salvar perfil no backend
const saveProfileToBackend = async () => {
  isLoading.value = true
  try {
    const response = await axios.put('/client/profile', {
      nome: form.value.nome,
      telefone: form.value.telefone,
      email: form.value.email,
      data_nascimento: form.value.dataNascimento,
      genero: form.value.genero
    })
    
    if (response.data.success) {
      const data = response.data.profile
      profile.value = {
        id: data.id,
        nome: data.nome,
        telefone: data.telefone,
        email: data.email,
        dataNascimento: data.data_nascimento,
        genero: data.genero,
        avatar: data.avatar
      }
      
      // Sincronizar com userStore
      userStore.fullName = data.nome
      userStore.whatsapp = data.telefone
      userStore.email = data.email
      userStore.saveToStorage()
      
      // Disparar eventos
      emit('profile-updated', profile.value)
      window.dispatchEvent(new CustomEvent('profile-updated', { detail: profile.value }))
      window.dispatchEvent(new CustomEvent('user-data-updated', { 
        detail: { 
          fullName: data.nome,
          whatsapp: data.telefone,
          email: data.email
        } 
      }))
      
      toast.success('Perfil atualizado com sucesso!')
      return true
    }
  } catch (error) {
    console.error('Erro ao salvar perfil:', error)
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
      toast.error('Corrija os erros no formulário')
    } else {
      toast.error(error.response?.data?.message || 'Erro ao salvar perfil')
    }
    return false
  } finally {
    isLoading.value = false
  }
}

// Upload de avatar
const uploadAvatar = async (file) => {
  isLoading.value = true
  try {
    const formData = new FormData()
    formData.append('avatar', file)
    
    const response = await axios.post('/client/profile/avatar', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    
    if (response.data.success) {
      const avatarUrl = response.data.avatar
      profile.value.avatar = avatarUrl
      if (view.value === 'edit') {
        form.value.avatar = avatarUrl
      }
      
      // Atualizar userStore se necessário
      window.dispatchEvent(new CustomEvent('user-data-updated', { 
        detail: { avatar: avatarUrl } 
      }))
      
      toast.success('Foto atualizada com sucesso!')
      return true
    }
  } catch (error) {
    console.error('Erro ao fazer upload:', error)
    toast.error(error.response?.data?.message || 'Erro ao fazer upload da imagem')
    return false
  } finally {
    isLoading.value = false
  }
}

// Formatar telefone
const handlePhoneInput = () => {
  let v = form.value.telefone.replace(/\D/g, '')
  if (v.length > 11) v = v.slice(0, 11)
  if (v.length > 0) v = v.replace(/^(\d{2})(\d)/g, '($1) $2')
  if (v.length > 10) v = v.replace(/^(\d{2}) (\d{5})(\d{4})$/, '($1) $2-$3')
  else if (v.length > 6) v = v.replace(/^(\d{2}) (\d{4})(\d{0,4})$/, '($1) $2-$3')
  form.value.telefone = v
}

const formatPhone = (phone) => {
  if (!phone) return ''
  let v = phone.toString()
  if (v.length === 11) return `(${v.slice(0,2)}) ${v.slice(2,7)}-${v.slice(7)}`
  if (v.length === 10) return `(${v.slice(0,2)}) ${v.slice(2,6)}-${v.slice(6)}`
  return v
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
  const year = today.getFullYear() - 18
  return `${year}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`
}

// Upload de imagem
const triggerImageUpload = () => {
  if (avatarInput.value) {
    avatarInput.value.click()
  }
}

const handleImageUpload = async (event) => {
  const file = event.target.files[0]
  if (!file) return
  
  if (!file.type.startsWith('image/')) {
    toast.error('Por favor, selecione uma imagem válida')
    return
  }
  
  if (file.size > 5 * 1024 * 1024) {
    toast.error('A imagem deve ter no máximo 5MB')
    return
  }
  
  // Preview imediato
  const reader = new FileReader()
  reader.onload = (e) => {
    if (view.value === 'edit') {
      form.value.avatar = e.target.result
    } else {
      profile.value.avatar = e.target.result
    }
  }
  reader.readAsDataURL(file)
  
  // Upload para o servidor
  const success = await uploadAvatar(file)
  if (!success && view.value !== 'edit') {
    // Se falhou, recarregar o avatar antigo
    await loadProfile()
  }
  
  event.target.value = ''
}

// Validação
const validateForm = () => {
  errors.value = {}
  
  // 🔥 VALIDAÇÃO DO NOME - APENAS PRIMEIRO NOME (sem exigir sobrenome)
  if (!form.value.nome || form.value.nome.trim() === '') {
    errors.value.nome = 'Nome é obrigatório'
  } else if (form.value.nome.trim().length < 3) {
    errors.value.nome = 'Nome deve ter no mínimo 3 caracteres'
  } else if (form.value.nome.trim().length > 100) {
    errors.value.nome = 'Nome deve ter no máximo 100 caracteres'
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
  
  // Validação do E-mail (opcional)
  if (form.value.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.value.email)) {
    errors.value.email = 'E-mail inválido'
  }
  
  return Object.keys(errors.value).length === 0
}

// Ações do perfil
const editProfile = () => {
  form.value = { ...profile.value }
  view.value = 'edit'
}

const cancelEdit = () => {
  form.value = { ...profile.value }
  view.value = 'view'
  errors.value = {}
}

const saveProfile = async () => {
  if (validateForm()) {
    const success = await saveProfileToBackend()
    if (success) {
      view.value = 'view'
      errors.value = {}
    }
  } else {
    toast.error('Por favor, corrija os erros no formulário')
  }
}

// Fechar modal
const close = () => {
  emit('update:modelValue', false)
  view.value = 'view'
  errors.value = {}
}

// Observar quando o modal abre
watch(() => props.modelValue, async (newValue) => {
  if (newValue && userStore.isLogged) {
    await loadProfile()
    view.value = 'view'
    errors.value = {}
  }
})

onMounted(() => {
  // Se o modal for aberto programaticamente
  if (props.modelValue && userStore.isLogged) {
    loadProfile()
  }
})
</script>

<style scoped>
.modal-overlay {
  background: rgba(0, 0, 0, 0.5);
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1050;
}

.modal-content {
  border-radius: 12px;
  overflow: hidden;
}

.loading-overlay {
  position: relative;
  min-height: 300px;
}

.loading-spinner {
  background: rgba(255, 255, 255, 0.8);
  top: 0;
  left: 0;
  z-index: 10;
  border-radius: 12px;
}

.avatar-container {
  position: relative;
  width: 120px;
  height: 120px;
  margin: 0 auto;
  cursor: pointer;
  border-radius: 50%;
  overflow: hidden;
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
  font-size: 48px;
  color: white;
}

.avatar-edit-overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 8px;
  opacity: 0;
  transition: opacity 0.3s;
  color: white;
}

.avatar-container:hover .avatar-edit-overlay {
  opacity: 1;
}

.avatar-container-edit {
  position: relative;
  width: 100px;
  height: 100px;
  margin: 0 auto;
  cursor: pointer;
  border-radius: 50%;
  overflow: hidden;
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
  font-size: 40px;
  color: white;
}

.avatar-edit-overlay-edit {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 6px;
  opacity: 0;
  transition: opacity 0.3s;
  color: white;
  font-size: 14px;
}

.avatar-container-edit:hover .avatar-edit-overlay-edit {
  opacity: 1;
}

.info-card {
  background: #f8f9fa;
  border-radius: 8px;
  padding: 12px 16px;
}

.info-label {
  font-size: 12px;
  color: #6c757d;
  margin-bottom: 4px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.info-value {
  font-size: 16px;
  font-weight: 500;
  color: #212529;
}
</style>