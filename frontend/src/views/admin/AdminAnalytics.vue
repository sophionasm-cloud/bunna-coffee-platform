<template>
  <div>
    <h1 class="text-2xl font-display font-bold text-coffee-800 mb-2">Analytics</h1>
    <p class="text-earth-500 mb-8">Platform overview and statistics</p>

    <div v-if="loading" class="text-center py-8">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-coffee-600 border-t-transparent"></div>
      <p class="mt-2 text-earth-500">Loading stats...</p>
    </div>

    <div v-else>
      <!-- User Stats -->
      <div class="mb-8">
        <h3 class="font-display text-lg font-semibold text-earth-900 mb-4">👥 Users</h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
          <div class="stat-card text-center">
            <div class="stat-number text-2xl">{{ stats.users?.total || 0 }}</div>
            <div class="stat-label">Total</div>
          </div>
          <div class="stat-card text-center">
            <div class="stat-number text-2xl text-forest-600">{{ stats.users?.farmers || 0 }}</div>
            <div class="stat-label">🌱 Farmers</div>
          </div>
          <div class="stat-card text-center">
            <div class="stat-number text-2xl text-coffee-600">{{ stats.users?.traders || 0 }}</div>
            <div class="stat-label">🤝 Traders</div>
          </div>
          <div class="stat-card text-center">
            <div class="stat-number text-2xl text-gold-600">{{ stats.users?.investors || 0 }}</div>
            <div class="stat-label">💼 Investors</div>
          </div>
          <div class="stat-card text-center">
            <div class="stat-number text-2xl text-earth-600">{{ stats.users?.customers || 0 }}</div>
            <div class="stat-label">☕ Customers</div>
          </div>
        </div>
      </div>

      <!-- Order Stats -->
      <div class="mb-8">
        <h3 class="font-display text-lg font-semibold text-earth-900 mb-4">📦 Orders</h3>
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
          <div class="stat-card text-center">
            <div class="stat-number text-2xl">{{ stats.orders?.total || 0 }}</div>
            <div class="stat-label">Total</div>
          </div>
          <div class="stat-card text-center">
            <div class="stat-number text-2xl text-gold-600">{{ stats.orders?.pending || 0 }}</div>
            <div class="stat-label">⏳ Pending</div>
          </div>
          <div class="stat-card text-center">
            <div class="stat-number text-2xl text-coffee-600">{{ stats.orders?.processing || 0 }}</div>
            <div class="stat-label">🔄 Processing</div>
          </div>
          <div class="stat-card text-center">
            <div class="stat-number text-2xl text-forest-600">{{ stats.orders?.completed || 0 }}</div>
            <div class="stat-label">✅ Completed</div>
          </div>
          <div class="stat-card text-center">
            <div class="stat-number text-2xl text-red-600">{{ stats.orders?.cancelled || 0 }}</div>
            <div class="stat-label">❌ Cancelled</div>
          </div>
          <div class="stat-card text-center">
            <div class="stat-number text-2xl text-gold-600">${{ stats.orders?.revenue || 0 }}</div>
            <div class="stat-label">💰 Revenue</div>
          </div>
        </div>
      </div>

      <!-- Product Stats -->
      <div>
        <h3 class="font-display text-lg font-semibold text-earth-900 mb-4">☕ Products</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
          <div class="stat-card text-center">
            <div class="stat-number text-2xl">{{ stats.products?.total || 0 }}</div>
            <div class="stat-label">Total Products</div>
          </div>
          <div class="stat-card text-center">
            <div class="stat-number text-2xl text-forest-600">{{ stats.products?.available || 0 }}</div>
            <div class="stat-label">Available</div>
          </div>
          <div class="stat-card text-center">
            <div class="stat-number text-2xl text-gold-600">{{ stats.products?.featured || 0 }}</div>
            <div class="stat-label">⭐ Featured</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { adminApi } from '@/api'

const stats = ref({})
const loading = ref(true)

const fetchStats = async () => {
  loading.value = true
  try {
    const response = await adminApi.stats()
    stats.value = response.data
  } catch (error) {
    console.error('Error fetching stats:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchStats()
})
</script>