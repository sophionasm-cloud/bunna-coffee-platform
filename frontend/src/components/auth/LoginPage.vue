<template>
  <div class="min-h-[80vh] flex items-center justify-center">
    <div class="bg-white p-8 rounded-sm shadow-warm max-w-md w-full border border-earth-100">
      <h2 class="text-2xl font-display font-bold text-coffee-800 text-center mb-2">Welcome Back</h2>
      <p class="text-earth-500 text-center mb-6">Login to your BUNNA account</p>
      
      <form @submit.prevent="handleLogin">
        <div class="mb-4">
          <label class="input-label">Email Address</label>
          <input v-model="email" type="email" class="input-field" placeholder="you@example.com" required />
        </div>
        <div class="mb-6">
          <label class="input-label">Password</label>
          <input v-model="password" type="password" class="input-field" placeholder="••••••••" required />
        </div>
        <button type="submit" class="btn-primary w-full justify-center">Login</button>
      </form>
      
      <p class="text-center text-sm text-earth-500 mt-4">
        Don't have an account? 
        <router-link to="/register" class="text-coffee-700 font-semibold hover:underline">Register</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const email = ref('')
const password = ref('')
const router = useRouter()
const auth = useAuthStore()

const handleLogin = async () => {
  // Will be implemented with backend API
  alert('Login functionality will be connected to backend')
  router.push('/dashboard')
}
</script>