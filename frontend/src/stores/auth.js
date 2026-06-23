import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { authApi } from '@/api'
import router from '@/router'

export const useAuthStore = defineStore('auth', () => {
  const user  = ref(null)
  const token = ref(localStorage.getItem('bunna_token') || null)
  const loading = ref(false)

  const isLoggedIn = computed(() => !!token.value && !!user.value)
  const isAdmin    = computed(() => user.value?.role === 'admin')
  const isFarmer   = computed(() => user.value?.role === 'farmer')
  const isTrader   = computed(() => user.value?.role === 'trader')
  const isInvestor = computed(() => user.value?.role === 'investor')
  const isCustomer = computed(() => user.value?.role === 'customer')

  async function register(data) {
    loading.value = true
    try {
      const res = await authApi.register(data)
      setSession(res.data)
      return res.data
    } finally {
      loading.value = false
    }
  }

  async function login(credentials) {
    loading.value = true
    try {
      const res = await authApi.login(credentials)
      setSession(res.data)
      return res.data
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    try { await authApi.logout() } catch {}
    clearSession()
    router.push({ name: 'home' })
  }

  async function fetchUser() {
    if (!token.value) return
    try {
      const res = await authApi.me()
      user.value = res.data.user
    } catch {
      clearSession()
    }
  }

  function setSession(data) {
    token.value = data.token
    user.value  = data.user
    localStorage.setItem('bunna_token', data.token)
    router.push({ name: 'dashboard' })
  }

  function clearSession() {
    token.value = null
    user.value  = null
    localStorage.removeItem('bunna_token')
  }

  return {
    user, token, loading,
    isLoggedIn, isAdmin, isFarmer, isTrader, isInvestor, isCustomer,
    register, login, logout, fetchUser,
  }
})