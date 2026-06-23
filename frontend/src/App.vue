<template>
  <div id="app-root" class="min-h-screen bg-cream">
    <RouterView />
  </div>
</template>

<script setup>
import { RouterView } from 'vue-router'
import { onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()

onMounted(async () => {
  await auth.fetchUser()
})
</script>