<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h2 class="font-display text-2xl font-bold text-earth-900">Notifications</h2>
        <p class="text-earth-500 text-sm mt-1">
          <span v-if="unreadCount > 0" class="text-coffee-700 font-medium">{{ unreadCount }} unread</span>
          <span v-else>All caught up!</span>
        </p>
      </div>
      <button v-if="unreadCount > 0" @click="markAllRead" :disabled="markingAll"
        class="btn-secondary text-sm px-4 py-2.5">
        {{ markingAll ? 'Marking...' : '✓ Mark All Read' }}
      </button>
    </div>

    <!-- Filter tabs -->
    <div class="flex gap-2">
      <button @click="filter = 'all'"
        :class="['px-4 py-2 text-sm font-medium rounded-sm border transition-all', filter==='all' ? 'bg-coffee-700 text-white border-coffee-700' : 'bg-white text-earth-600 border-earth-200 hover:border-coffee-400']">
        All ({{ notifications.length }})
      </button>
      <button @click="filter = 'unread'"
        :class="['px-4 py-2 text-sm font-medium rounded-sm border transition-all', filter==='unread' ? 'bg-coffee-700 text-white border-coffee-700' : 'bg-white text-earth-600 border-earth-200 hover:border-coffee-400']">
        Unread ({{ unreadCount }})
      </button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="space-y-2">
      <div v-for="n in 4" :key="n" class="card p-4 animate-pulse flex gap-3">
        <div class="w-10 h-10 rounded-full bg-earth-100 shrink-0"></div>
        <div class="flex-1">
          <div class="h-3 bg-earth-100 rounded w-3/4 mb-2"></div>
          <div class="h-2 bg-earth-100 rounded w-1/3"></div>
        </div>
      </div>
    </div>

    <!-- Empty -->
    <div v-else-if="!filtered.length" class="card p-16 text-center">
      <p class="text-5xl mb-4">🔔</p>
      <p class="font-display text-xl text-earth-700 mb-2">
        {{ filter === 'unread' ? 'No unread notifications' : 'No notifications yet' }}
      </p>
      <p class="text-earth-400 text-sm">You'll be notified of orders, approvals and updates here.</p>
    </div>

    <!-- List -->
    <div v-else class="space-y-2">
      <div v-for="n in filtered" :key="n.id"
        :class="['card p-4 flex items-start gap-4 cursor-pointer transition-all hover:shadow-warm', !n.read_at ? 'border-l-4 border-coffee-600 bg-coffee-50/30' : '']"
        @click="markRead(n)">
        <!-- Icon -->
        <div :class="['w-10 h-10 rounded-full flex items-center justify-center text-lg shrink-0', typeStyle(n.type).bg]">
          {{ typeStyle(n.type).icon }}
        </div>
        <!-- Content -->
        <div class="flex-1 min-w-0">
          <div class="flex items-start justify-between gap-2">
            <p :class="['text-sm leading-snug', !n.read_at ? 'font-semibold text-earth-900' : 'text-earth-700']">
              {{ n.data?.message || n.message || 'New notification' }}
            </p>
            <span v-if="!n.read_at" class="w-2 h-2 rounded-full bg-coffee-600 shrink-0 mt-1.5"></span>
          </div>
          <div class="flex items-center gap-3 mt-1.5">
            <span :class="['text-xs px-2 py-0.5 rounded-full', typeStyle(n.type).badge]">{{ typeStyle(n.type).label }}</span>
            <span class="text-xs text-earth-400">{{ timeAgo(n.created_at) }}</span>
            <span v-if="n.read_at" class="text-xs text-earth-300">· Read {{ timeAgo(n.read_at) }}</span>
          </div>
          <!-- Action link -->
          <RouterLink v-if="n.data?.link" :to="n.data.link" class="text-xs text-coffee-600 hover:text-coffee-800 font-medium mt-1 inline-block">
            View details →
          </RouterLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { notificationsApi } from '@/api'

const notifications = ref([])
const loading       = ref(true)
const filter        = ref('all')
const markingAll    = ref(false)

const unreadCount = computed(() => notifications.value.filter(n => !n.read_at).length)
const filtered    = computed(() => filter.value === 'unread' ? notifications.value.filter(n => !n.read_at) : notifications.value)

const DEMO = [
  { id:'1', type:'order_placed',   data:{ message:'Your order BUN-A1B2C3D4 has been placed successfully.', link:'/dashboard/orders' }, created_at: new Date(Date.now()-3600000).toISOString(), read_at:null },
  { id:'2', type:'order_approved', data:{ message:'Order BUN-E5F6G7H8 has been approved and is being processed.', link:'/dashboard/orders' }, created_at: new Date(Date.now()-7200000).toISOString(), read_at:null },
  { id:'3', type:'order_shipped',  data:{ message:'Your order BUN-A1B2C3D4 has been shipped! Expected delivery in 5-7 days.', link:'/dashboard/orders' }, created_at: new Date(Date.now()-86400000).toISOString(), read_at: new Date(Date.now()-80000000).toISOString() },
  { id:'4', type:'listing',        data:{ message:'Your listing "Yirgacheffe Grade 1" has been approved by admin.', link:'/dashboard/listings' }, created_at: new Date(Date.now()-172800000).toISOString(), read_at: new Date(Date.now()-160000000).toISOString() },
  { id:'5', type:'general',        data:{ message:'Welcome to BUNNA! Complete your profile to get started.' }, created_at: new Date(Date.now()-259200000).toISOString(), read_at:null },
]

async function fetchNotifications() {
  loading.value = true
  try {
    const res = await notificationsApi.list()
    notifications.value = res.data || []
  } catch { notifications.value = [...DEMO] }
  finally { loading.value = false }
}

async function markRead(n) {
  if (n.read_at) return
  n.read_at = new Date().toISOString()
  try { await notificationsApi.markRead(n.id) } catch {}
}

async function markAllRead() {
  markingAll.value = true
  try { await notificationsApi.markAllRead() } catch {}
  notifications.value.forEach(n => { if (!n.read_at) n.read_at = new Date().toISOString() })
  markingAll.value = false
}

function typeStyle(type) {
  const map = {
    order_placed:   { icon:'📦', label:'Order', bg:'bg-gold-100', badge:'bg-gold-100 text-gold-800' },
    order_approved: { icon:'✅', label:'Approved', bg:'bg-forest-100', badge:'bg-forest-100 text-forest-800' },
    order_shipped:  { icon:'🚚', label:'Shipped', bg:'bg-blue-100', badge:'bg-blue-100 text-blue-800' },
    order_delivered:{ icon:'🎉', label:'Delivered', bg:'bg-forest-100', badge:'bg-forest-100 text-forest-800' },
    listing:        { icon:'🌿', label:'Listing', bg:'bg-earth-100', badge:'bg-earth-100 text-earth-700' },
    general:        { icon:'🔔', label:'Info', bg:'bg-coffee-100', badge:'bg-coffee-100 text-coffee-800' },
  }
  return map[type] || map.general
}

function timeAgo(d) {
  if (!d) return ''
  const diff = Date.now() - new Date(d).getTime()
  if (diff < 60000)    return 'Just now'
  if (diff < 3600000)  return Math.floor(diff/60000) + 'm ago'
  if (diff < 86400000) return Math.floor(diff/3600000) + 'h ago'
  if (diff < 604800000) return Math.floor(diff/86400000) + 'd ago'
  return new Date(d).toLocaleDateString('en-ET', { day:'numeric', month:'short' })
}

onMounted(fetchNotifications)
</script>