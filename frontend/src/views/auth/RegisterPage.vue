<template>
  <div class="min-h-screen bg-ethiopian-weave flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-lg">
      <div class="text-center mb-8">
        <RouterLink to="/" class="inline-flex items-center gap-3 mb-6">
          <div class="w-11 h-11 rounded-full bg-coffee-700 flex items-center justify-center shadow-warm">
            <span class="text-white font-display font-bold">ቡ</span>
          </div>
          <span class="font-display font-bold text-2xl text-earth-900">BUNNA</span>
        </RouterLink>
        <h1 class="font-display text-3xl font-bold text-earth-900">{{ t('auth.register_title') }}</h1>
        <p class="text-earth-500 mt-2">{{ t('auth.register_sub') }}</p>
      </div>

      <div class="card p-8">
        <div class="pattern-border mb-6"></div>

        <form @submit.prevent="handleRegister" class="space-y-5">
          <div v-if="error" class="bg-red-50 border border-red-200 rounded-sm px-4 py-3 text-sm text-red-700">
            {{ error }}
          </div>

          <div>
            <label class="input-label">{{ t('auth.name') }}</label>
            <input v-model="form.name" type="text" required class="input-field" placeholder="Abebe Girma" />
          </div>

          <div>
            <label class="input-label">{{ t('auth.email') }}</label>
            <input v-model="form.email" type="email" required class="input-field" placeholder="you@example.com" />
          </div>

          <!-- Role selection -->
          <div>
            <label class="input-label">{{ t('auth.role') }}</label>
            <div class="grid grid-cols-2 gap-3 mt-2">
              <button v-for="r in roleOptions" :key="r.value"
                type="button"
                @click="form.role = r.value"
                :class="[
                  'flex items-center gap-3 p-3 rounded-sm border-2 text-left transition-all',
                  form.role === r.value
                    ? 'border-coffee-600 bg-coffee-50 text-coffee-800'
                    : 'border-earth-200 hover:border-earth-300 text-earth-600'
                ]">
                <span class="text-xl">{{ r.icon }}</span>
                <div>
                  <p class="text-sm font-semibold">{{ r.label }}</p>
                  <p class="text-xs text-earth-400">{{ r.desc }}</p>
                </div>
              </button>
            </div>
          </div>

          <div>
            <label class="input-label">{{ t('auth.password') }}</label>
            <input v-model="form.password" type="password" required minlength="8" class="input-field" placeholder="At least 8 characters" />
          </div>

          <div>
            <label class="input-label">{{ t('auth.confirm_password') }}</label>
            <input v-model="form.password_confirmation" type="password" required class="input-field" placeholder="••••••••" />
          </div>

          <button type="submit" class="btn-primary w-full justify-center py-3.5" :disabled="auth.loading">
            {{ auth.loading ? t('common.loading') : t('auth.register_btn') }}
          </button>
        </form>

        <p class="text-center text-sm text-earth-500 mt-6">
          {{ t('auth.have_account') }}
          <RouterLink to="/login" class="text-coffee-700 font-semibold hover:text-coffee-900">
            {{ t('nav.login') }}
          </RouterLink>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useAuthStore } from '@/stores/auth'

const { t } = useI18n()
const auth  = useAuthStore()
const error = ref('')

const form = ref({
  name: '', email: '', password: '', password_confirmation: '', role: 'customer'
})

const roleOptions = [
  { value: 'farmer',   icon: '🌱', label: 'Farmer',   desc: 'I grow coffee' },
  { value: 'trader',   icon: '🤝', label: 'Trader',   desc: 'I buy & sell' },
  { value: 'investor', icon: '💼', label: 'Investor', desc: 'I invest' },
  { value: 'customer', icon: '☕', label: 'Customer', desc: 'I buy coffee' },
]

async function handleRegister() {
  error.value = ''
  if (form.value.password !== form.value.password_confirmation) {
    error.value = 'Passwords do not match.'
    return
  }
  try {
    await auth.register(form.value)
  } catch (e) {
    const errors = e.response?.data?.errors
    error.value = errors
      ? Object.values(errors).flat().join('. ')
      : e.response?.data?.message || 'Registration failed.'
  }
}
</script>