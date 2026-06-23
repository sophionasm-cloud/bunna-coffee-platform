<template>
  <div class="min-h-screen flex bg-gray-200">
    <!-- Sidebar -->
    <aside :class="[
      'fixed inset-y-0 left-0 z-40 flex flex-col bg-coffee-950 transition-all duration-300',
      sidebarOpen ? 'w-64' : 'w-16'
    ]">
      <!-- Logo -->
      <div class="flex items-center gap-3 px-4 h-16 border-b border-coffee-800">
        <div class="w-8 h-8 shrink-0 rounded-full bg-gold-500 flex items-center justify-center">
          <span class="text-coffee-950 font-display font-bold text-xs">ቡ</span>
        </div>
        <Transition name="fade">
          <span v-if="sidebarOpen" class="font-display font-bold text-white text-lg">BUNNA</span>
        </Transition>
      </div>

      <!-- User info -->
      <div class="px-3 py-4 border-b border-coffee-800">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 shrink-0 rounded-full bg-coffee-700 flex items-center justify-center">
            <span class="text-white font-semibold text-sm">{{ userInitials }}</span>
          </div>
          <Transition name="fade">
            <div v-if="sidebarOpen" class="min-w-0">
              <p class="text-white text-sm font-medium truncate">{{ auth.user?.name }}</p>
              <span :class="roleBadgeClass">{{ t(`roles.${auth.user?.role}`) }}</span>
            </div>
          </Transition>
        </div>
      </div>

      <!-- Nav -->
      <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
        <RouterLink v-for="link in visibleLinks" :key="link.to"
          :to="link.to"
          :title="!sidebarOpen ? link.label : undefined"
          :class="[
            'flex items-center gap-3 px-3 py-2.5 rounded-sm text-coffee-300 hover:text-white hover:bg-coffee-800 transition-all duration-150',
            $route.path.startsWith(link.to) && link.to !== '/dashboard'
              ? 'bg-coffee-700 text-white'
              : $route.path === '/dashboard' && link.to === '/dashboard'
              ? 'bg-coffee-700 text-white'
              : ''
          ]">
          <span class="text-lg shrink-0">{{ link.icon }}</span>
          <Transition name="fade">
            <span v-if="sidebarOpen" class="text-sm font-medium">{{ link.label }}</span>
          </Transition>
        </RouterLink>
      </nav>

      <!-- Bottom: toggle + logout -->
      <div class="px-2 py-4 border-t border-coffee-800 space-y-1">
        <button @click="sidebarOpen = !sidebarOpen"
          class="flex items-center gap-3 w-full px-3 py-2.5 text-coffee-400 hover:text-white hover:bg-coffee-800 rounded-sm transition-all">
          <span class="text-lg">{{ sidebarOpen ? '◀' : '▶' }}</span>
          <Transition name="fade">
            <span v-if="sidebarOpen" class="text-sm">Collapse</span>
          </Transition>
        </button>
        <RouterLink to="/"
          class="flex items-center gap-3 px-3 py-2.5 text-coffee-400 hover:text-white hover:bg-coffee-800 rounded-sm transition-all">
          <span class="text-lg">🌐</span>
          <Transition name="fade">
            <span v-if="sidebarOpen" class="text-sm">Public Site</span>
          </Transition>
        </RouterLink>
        <button @click="auth.logout()"
          class="flex items-center gap-3 w-full px-3 py-2.5 text-coffee-400 hover:text-red-300 hover:bg-coffee-800 rounded-sm transition-all">
          <span class="text-lg">🚪</span>
          <Transition name="fade">
            <span v-if="sidebarOpen" class="text-sm">Sign Out</span>
          </Transition>
        </button>
      </div>
    </aside>

    <!-- Main content -->
    <div :class="['flex-1 flex flex-col transition-all duration-300', sidebarOpen ? 'ml-64' : 'ml-16']">
      <!-- Top bar -->
      <header class="h-16 bg-white border-b border-earth-100 flex items-center justify-between px-6 sticky top-0 z-30 shadow-sm">
        <div>
          <h1 class="text-lg font-display font-semibold text-earth-900">{{ pageTitle }}</h1>
          <p class="text-xs text-earth-400">{{ formattedDate }}</p>
        </div>
        <div class="flex items-center gap-3">
          <!-- Notifications -->
          <RouterLink to="/dashboard/notifications"
            class="relative p-2 text-earth-500 hover:text-earth-800 hover:bg-earth-50 rounded-sm transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
          </RouterLink>
          <!-- Profile -->
          <RouterLink to="/dashboard/profile"
            class="flex items-center gap-2 px-3 py-1.5 hover:bg-earth-50 rounded-sm transition-all">
            <div class="w-7 h-7 rounded-full bg-coffee-700 flex items-center justify-center">
              <span class="text-white text-xs font-semibold">{{ userInitials }}</span>
            </div>
            <span class="text-sm font-medium text-earth-700 hidden sm:block">{{ auth.user?.name?.split(' ')[0] }}</span>
          </RouterLink>
        </div>
      </header>

      <!-- Page content -->
      <main class="flex-1 p-6">
        <RouterView />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { RouterLink, RouterView, useRoute } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useAuthStore } from '@/stores/auth'

const { t } = useI18n()
const auth   = useAuthStore()
const $route = useRoute()
const sidebarOpen = ref(true)

const userInitials = computed(() => {
  const name = auth.user?.name || ''
  return name.split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase()
})

const roleBadgeClass = computed(() => ({
  farmer:   'badge-farmer text-xs',
  trader:   'badge-trader text-xs',
  investor: 'badge-investor text-xs',
  customer: 'badge-pending text-xs',
  admin:    'badge-admin text-xs',
}[auth.user?.role] || 'badge-admin text-xs'))

const allLinks = [
  // Common
  { to: '/dashboard',               icon: '📊', label: 'Dashboard',       roles: ['all'] },
  { to: '/dashboard/orders',        icon: '📦', label: 'My Orders',       roles: ['customer', 'admin'] },
  { to: '/dashboard/notifications', icon: '🔔', label: 'Notifications',   roles: ['all'] },
  { to: '/dashboard/profile',       icon: '👤', label: 'Profile',         roles: ['all'] },
  // Farmer
  { to: '/dashboard/listings',      icon: '🌿', label: 'My Listings',     roles: ['farmer', 'admin'] },
  // Trader
  { to: '/dashboard/market',        icon: '📈', label: 'Market',          roles: ['trader', 'admin'] },
  { to: '/dashboard/trade-orders',  icon: '🤝', label: 'Trade Orders',    roles: ['trader', 'admin'] },
  // Investor
  { to: '/dashboard/opportunities', icon: '💼', label: 'Opportunities',   roles: ['investor', 'admin'] },
  { to: '/dashboard/portfolio',     icon: '📋', label: 'Portfolio',       roles: ['investor', 'admin'] },
  // Admin
  { to: '/dashboard/admin/users',     icon: '👥', label: 'Manage Users',   roles: ['admin'] },
  { to: '/dashboard/admin/products',  icon: '☕', label: 'Manage Products', roles: ['admin'] },
  { to: '/dashboard/admin/orders',    icon: '🗂️', label: 'Manage Orders',  roles: ['admin'] },
  { to: '/dashboard/admin/analytics', icon: '📉', label: 'Analytics',      roles: ['admin'] },
]

const visibleLinks = computed(() =>
  allLinks.filter(l => l.roles.includes('all') || l.roles.includes(auth.user?.role))
)

const pageTitle = computed(() => {
  const link = allLinks.find(l => $route.path.startsWith(l.to) && l.to !== '/dashboard')
    || allLinks.find(l => l.to === '/dashboard')
  return link?.label || 'Dashboard'
})

const formattedDate = computed(() =>
  new Date().toLocaleDateString('en-ET', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
)
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to       { opacity: 0; }
</style>