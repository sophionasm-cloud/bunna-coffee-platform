<template>
  <div class="space-y-6">
    <!-- Welcome banner -->
    <div class="bg-teff-weave rounded-sm p-6 flex items-center justify-between">
      <div>
        <h2 class="font-display text-2xl font-bold text-white">
          {{ t('dashboard.welcome') }}, {{ auth.user?.name?.split(' ')[0] }} 👋
        </h2>
        <p class="text-coffee-300 mt-1 text-sm">
          {{ roleWelcome }}
        </p>
      </div>
      <div class="text-5xl hidden sm:block">{{ roleIcon }}</div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <div v-for="stat in stats" :key="stat.label" class="stat-card">
        <div class="flex items-center justify-between mb-3">
          <p class="text-sm text-earth-500 font-medium">{{ stat.label }}</p>
          <span class="text-2xl">{{ stat.icon }}</span>
        </div>
        <p class="font-display text-3xl font-bold text-earth-900">{{ stat.value }}</p>
        <p v-if="stat.change" class="text-xs mt-1" :class="stat.positive ? 'text-forest-600' : 'text-red-500'">
          {{ stat.change }}
        </p>
      </div>
    </div>

    <!-- Quick actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Recent activity -->
      <div class="card p-6">
        <h3 class="font-display text-lg font-semibold text-earth-900 mb-4">Recent Activity</h3>
        <div class="space-y-3">
          <div v-for="act in recentActivity" :key="act.id"
            class="flex items-start gap-3 py-3 border-b border-earth-100 last:border-0">
            <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0"
              :class="act.bg">
              <span class="text-sm">{{ act.icon }}</span>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-earth-800">{{ act.title }}</p>
              <p class="text-xs text-earth-400">{{ act.time }}</p>
            </div>
            <span :class="act.badgeClass" class="text-xs shrink-0">{{ act.status }}</span>
          </div>
        </div>
      </div>

      <!-- Quick links -->
      <div class="card p-6">
        <h3 class="font-display text-lg font-semibold text-earth-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 gap-3">
          <RouterLink v-for="action in quickActions" :key="action.to"
            :to="action.to"
            class="flex items-center gap-3 p-4 bg-earth-50 hover:bg-coffee-50 rounded-sm transition-all group">
            <span class="text-2xl group-hover:scale-110 transition-transform">{{ action.icon }}</span>
            <span class="text-sm font-medium text-earth-700 group-hover:text-coffee-800">{{ action.label }}</span>
          </RouterLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { RouterLink } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useAuthStore } from '@/stores/auth'

const { t } = useI18n()
const auth = useAuthStore()

const roleIcon = computed(() => ({
  farmer: '🌱', trader: '🤝', investor: '💼', customer: '☕', admin: '👑'
}[auth.user?.role] || '👤'))

const roleWelcome = computed(() => ({
  farmer:   'Manage your listings and track your crop sales.',
  trader:   'Browse market lots and manage your trade orders.',
  investor: 'Explore investment opportunities in Ethiopian coffee.',
  customer: 'Browse premium coffees and track your orders.',
  admin:    'Full platform overview and management.',
}[auth.user?.role] || 'Welcome to BUNNA.'))

const stats = computed(() => {
  const base = [
    { label: 'Total Orders',   icon: '📦', value: '12',    change: '+3 this week',   positive: true },
    { label: 'Pending',        icon: '⏳', value: '3',     change: 'Needs attention', positive: false },
    { label: 'Completed',      icon: '✅', value: '9',     change: '75% completion',  positive: true },
    { label: 'Notifications',  icon: '🔔', value: '5',     change: '2 unread', positive: false },
  ]
  if (auth.isAdmin) {
    return [
      { label: 'Total Users',    icon: '👥', value: '248',   change: '+12 this month',  positive: true },
      { label: 'Total Orders',   icon: '📦', value: '1,340', change: '+45 this week',   positive: true },
      { label: 'Active Farmers', icon: '🌱', value: '89',    change: '+5 this month',   positive: true },
      { label: 'Revenue (ETB)',  icon: '💰', value: '45.2K', change: '+8% vs last mo',  positive: true },
    ]
  }
  if (auth.isFarmer) {
    return [
      { label: 'My Listings',    icon: '🌿', value: '6',   change: '2 active',       positive: true },
      { label: 'Total Sales',    icon: '💰', value: '18',  change: '+2 this week',   positive: true },
      { label: 'Pending Orders', icon: '⏳', value: '3',   change: '',               positive: false },
      { label: 'Revenue (ETB)',  icon: '📈', value: '12K', change: '+15% this month', positive: true },
    ]
  }
  return base
})

const recentActivity = [
  { id: 1, icon: '📦', bg: 'bg-gold-100',   title: 'Order #1023 placed',          time: '2 hours ago', status: 'Pending',   badgeClass: 'badge-pending' },
  { id: 2, icon: '✅', bg: 'bg-forest-100', title: 'Order #1018 delivered',       time: 'Yesterday',   status: 'Delivered', badgeClass: 'badge-delivered' },
  { id: 3, icon: '🔔', bg: 'bg-coffee-100', title: 'New message from trader',     time: '3 hours ago', status: 'New',       badgeClass: 'badge-farmer' },
  { id: 4, icon: '🌿', bg: 'bg-forest-100', title: 'Listing approved',            time: '1 day ago',   status: 'Approved',  badgeClass: 'badge-approved' },
]

const quickActions = computed(() => {
  const common = [
    { to: '/dashboard/orders',        icon: '📦', label: 'My Orders' },
    { to: '/dashboard/profile',       icon: '👤', label: 'My Profile' },
    { to: '/dashboard/notifications', icon: '🔔', label: 'Notifications' },
    { to: '/products',                icon: '☕', label: 'Browse Coffee' },
  ]
  if (auth.isFarmer)   return [{ to: '/dashboard/listings', icon: '🌿', label: 'My Listings' }, { to: '/dashboard/listings/add', icon: '➕', label: 'Add Listing' }, ...common.slice(0,2)]
  if (auth.isTrader)   return [{ to: '/dashboard/market', icon: '📈', label: 'Market' }, { to: '/dashboard/trade-orders', icon: '🤝', label: 'Trade Orders' }, ...common.slice(0,2)]
  if (auth.isInvestor) return [{ to: '/dashboard/opportunities', icon: '💼', label: 'Opportunities' }, { to: '/dashboard/portfolio', icon: '📋', label: 'Portfolio' }, ...common.slice(0,2)]
  if (auth.isAdmin)    return [{ to: '/dashboard/admin/users', icon: '👥', label: 'Users' }, { to: '/dashboard/admin/orders', icon: '🗂️', label: 'Orders' }, { to: '/dashboard/admin/products', icon: '☕', label: 'Products' }, { to: '/dashboard/admin/analytics', icon: '📉', label: 'Analytics' }]
  return common
})
</script>