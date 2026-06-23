<template>
  <div class="min-h-[80vh] flex items-center justify-center">
    <div class="bg-white p-8 rounded-sm shadow-warm max-w-md w-full border border-earth-100">
      <h2 class="text-2xl font-display font-bold text-coffee-800 text-center mb-2">Create Account</h2>
      <p class="text-earth-500 text-center mb-6">Join the Ethiopian coffee community</p>
      
      <form @submit.prevent="handleRegister">
        <div class="mb-4">
          <label class="input-label">Full Name</label>
          <input v-model="name" type="text" class="input-field" placeholder="John Doe" required />
        </div>
        <div class="mb-4">
          <label class="input-label">Email Address</label>
          <input v-model="email" type="email" class="input-field" placeholder="you@example.com" required />
        </div>
        <div class="mb-4">
          <label class="input-label">Role</label>
          <select v-model="role" class="select-field">
            <option value="farmer">🌱 Farmer</option>
            <option value="trader">📦 Trader</option>
            <option value="investor">💼 Investor</option>
          </select>
        </div>
        <div class="mb-4">
          <label class="input-label">Password</label>
          <input v-model="password" type="password" class="input-field" placeholder="••••••••" required />
        </div>
        <div class="mb-6">
          <label class="input-label">Confirm Password</label>
          <input v-model="password_confirmation" type="password" class="input-field" placeholder="••••••••" required />
        </div>
        <button type="submit" class="btn-primary w-full justify-center">Register</button>
      </form>
      
      <p class="text-center text-sm text-earth-500 mt-4">
        Already have an account? 
        <router-link to="/login" class="text-coffee-700 font-semibold hover:underline">Login</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const name = ref('')
const email = ref('')
const role = ref('farmer')
const password = ref('')
const password_confirmation = ref('')
const router = useRouter()

const handleRegister = async () => {
  // Will be implemented with backend API
  alert('Registration functionality will be connected to backend')
  router.push('/login')
}
</script>