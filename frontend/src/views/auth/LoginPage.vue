<template>
  <div class="min-h-screen bg-ethiopian-weave flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
      <!-- Logo -->
      <div class="text-center mb-8">
        <RouterLink to="/" class="inline-flex items-center gap-3 mb-6">
          <div class="w-11 h-11 rounded-full bg-coffee-700 flex items-center justify-center shadow-warm">
            <span class="text-white font-display font-bold">ቡ</span>
          </div>
          <span class="font-display font-bold text-2xl text-earth-900">BUNNA</span>
        </RouterLink>
        <h1 class="font-display text-3xl font-bold text-earth-900">{{ t('auth.login_title') }}</h1>
        <p class="text-earth-500 mt-2">{{ t('auth.login_sub') }}</p>
      </div>

      <!-- Card -->
      <div class="card p-8">
        <div class="pattern-border mb-6"></div>

        <form @submit.prevent="handleLogin" class="space-y-5">
          <!-- Error -->
          <div v-if="error" class="bg-red-50 border border-red-200 rounded-sm px-4 py-3 text-sm text-red-700">
            {{ error }}
          </div>

          <div>
            <label class="input-label">{{ t('auth.email') }}</label>
            <input v-model="form.email" type="email" required class="input-field"
              placeholder="hello@bunna.et" />
          </div>

          <div>
            <div class="flex justify-between mb-1.5">
              <label class="input-label">{{ t('auth.password') }}</label>
              <a href="#" class="text-xs text-coffee-600 hover:text-coffee-800 font-medium">{{ t('auth.forgot') }}</a>
            </div>
            <input v-model="form.password" type="password" required class="input-field"
              placeholder="••••••••" />
          </div>

          <button type="submit" class="btn-primary w-full justify-center py-3.5" :disabled="auth.loading">
            <svg v-if="auth.loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
            </svg>
            {{ auth.loading ? t('common.loading') : t('auth.login_btn') }}
          </button>
        </form>

        <p class="text-center text-sm text-earth-500 mt-6">
          {{ t('auth.no_account') }}
          <RouterLink to="/register" class="text-coffee-700 font-semibold hover:text-coffee-900">
            {{ t('nav.register') }}
          </RouterLink>
        </p>
      </div>

      <!-- Demo accounts -->
      <div class="mt-6 card p-5">
        <p class="text-xs font-semibold text-earth-500 uppercase tracking-wider mb-3">Demo Accounts</p>
        <div class="grid grid-cols-2 gap-2">
          <button v-for="demo in demoAccounts" :key="demo.role"
            @click="fillDemo(demo)"
            class="flex items-center gap-2 px-3 py-2 bg-earth-50 hover:bg-earth-100 rounded-sm text-xs text-earth-700 transition-colors text-left">
            <span>{{ demo.icon }}</span>
            <div>
              <p class="font-semibold capitalize">{{ demo.role }}</p>
              <p class="text-earth-400">{{ demo.email }}</p>
            </div>
          </button>
        </div>
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

const form  = ref({ email: '', password: '' })
const error = ref('')

const demoAccounts = [
  { role: 'admin',    icon: '👑', email: 'admin@bunna.et',    password: 'password' },
  { role: 'farmer',   icon: '🌱', email: 'farmer@bunna.et',   password: 'password' },
  { role: 'trader',   icon: '🤝', email: 'trader@bunna.et',   password: 'password' },
  { role: 'investor', icon: '💼', email: 'investor@bunna.et', password: 'password' },
]

function fillDemo(demo) {
  form.value.email    = demo.email
  form.value.password = demo.password
}

async function handleLogin() {
  error.value = ''
  try {
    await auth.login(form.value)
  } catch (e) {
    error.value = e.response?.data?.message || 'Login failed. Please check your credentials.'
  }
}
</script>