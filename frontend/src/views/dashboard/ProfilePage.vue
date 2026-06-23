<template>
  <div>
    <h1 class="text-2xl font-display font-bold text-coffee-800 mb-2">My Profile</h1>
    <p class="text-earth-500 mb-8">Manage your profile settings</p>

    <div v-if="loading" class="text-center py-8">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-coffee-600 border-t-transparent"></div>
      <p class="mt-2 text-earth-500">Loading profile...</p>
    </div>

    <div v-else class="grid md:grid-cols-2 gap-8">
      <!-- Profile Info -->
      <div class="card p-6">
        <h3 class="font-display text-lg font-semibold text-earth-900 mb-4">Profile Information</h3>
        <form @submit.prevent="updateProfile" class="space-y-4">
          <div>
            <label class="input-label">Full Name</label>
            <input v-model="form.name" type="text" class="input-field" required />
          </div>
          <div>
            <label class="input-label">Email</label>
            <input v-model="form.email" type="email" class="input-field" disabled />
          </div>
          <div>
            <label class="input-label">Phone</label>
            <input v-model="form.phone" type="text" class="input-field" placeholder="+251 911 000 000" />
          </div>
          <div>
            <label class="input-label">Country</label>
            <input v-model="form.country" type="text" class="input-field" placeholder="Ethiopia" />
          </div>
          <div>
            <label class="input-label">Region</label>
            <input v-model="form.region" type="text" class="input-field" placeholder="Addis Ababa" />
          </div>
          <button type="submit" class="btn-primary w-full" :disabled="saving">
            {{ saving ? 'Saving...' : 'Save Changes' }}
          </button>
        </form>
      </div>

      <!-- User Info Card -->
      <div class="card p-6">
        <h3 class="font-display text-lg font-semibold text-earth-900 mb-4">Account Details</h3>
        <div class="space-y-4">
          <div class="flex justify-between py-2 border-b border-earth-100">
            <span class="text-earth-500">Role</span>
            <span :class="roleBadgeClass(user?.role)">{{ user?.role }}</span>
          </div>
          <div class="flex justify-between py-2 border-b border-earth-100">
            <span class="text-earth-500">Status</span>
            <span :class="user?.is_active ? 'badge-approved' : 'badge-pending'">
              {{ user?.is_active ? 'Active' : 'Suspended' }}
            </span>
          </div>
          <div class="flex justify-between py-2 border-b border-earth-100">
            <span class="text-earth-500">Joined</span>
            <span class="text-earth-700">{{ formatDate(user?.created_at) }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { authApi } from '@/api'

const auth = useAuthStore()
const user = computed(() => auth.user)
const loading = ref(true)
const saving = ref(false)

const form = ref({
  name: '',
  email: '',
  phone: '',
  country: '',
  region: ''
})

const roleBadgeClass = (role) => {
  const classes = {
    admin: 'badge-admin',
    farmer: 'badge-farmer',
    trader: 'badge-trader',
    investor: 'badge-investor',
    customer: 'badge-pending'
  }
  return classes[role] || 'badge-pending'
}

const formatDate = (date) => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const updateProfile = async () => {
  saving.value = true
  try {
    await authApi.updateProfile(form.value)
    await auth.fetchUser()
    alert('Profile updated successfully!')
  } catch (error) {
    console.error('Error updating profile:', error)
    alert('Failed to update profile.')
  } finally {
    saving.value = false
  }
}

onMounted(async () => {
  await auth.fetchUser()
  if (user.value) {
    form.value = {
      name: user.value.name || '',
      email: user.value.email || '',
      phone: user.value.phone || '',
      country: user.value.country || '',
      region: user.value.region || ''
    }
  }
  loading.value = false
})
</script>