<template>
  <div class="space-y-6">
    <div>
      <h2 class="font-display text-2xl font-bold text-earth-900">Manage Orders</h2>
      <p class="text-earth-500 text-sm mt-1">View all orders across the platform and update their status</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
      <div v-for="s in statusStats" :key="s.label" class="stat-card text-center py-4">
        <p class="font-display text-2xl font-bold" :class="s.color">{{ s.count }}</p>
        <p class="text-xs text-earth-500 mt-1">{{ s.label }}</p>
      </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-3 items-end">
      <div class="flex-1 min-w-40">
        <label class="input-label text-xs">Search order #</label>
        <input v-model="search" @input="debouncedFetch" class="input-field" placeholder="BUN-..." />
      </div>
      <div>
        <label class="input-label text-xs">Status</label>
        <select v-model="statusFilter" @change="fetchOrders" class="select-field">
          <option value="">All Statuses</option>
          <option v-for="s in statuses" :key="s" :value="s" class="capitalize">{{ s }}</option>
        </select>
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="space-y-3">
      <div v-for="n in 5" :key="n" class="card p-4 animate-pulse flex justify-between">
        <div class="space-y-2 flex-1">
          <div class="h-3 bg-earth-100 rounded w-32"></div>
          <div class="h-2 bg-earth-100 rounded w-48"></div>
        </div>
        <div class="h-6 bg-earth-100 rounded w-20"></div>
      </div>
    </div>

    <!-- Table -->
    <div v-else-if="orders.length" class="card overflow-hidden">
      <table class="w-full">
        <thead>
          <tr>
            <th class="table-header">Order #</th>
            <th class="table-header hidden sm:table-cell">Customer</th>
            <th class="table-header hidden md:table-cell">Items</th>
            <th class="table-header">Total</th>
            <th class="table-header">Status</th>
            <th class="table-header">Update</th>
            <th class="table-header hidden lg:table-cell">Date</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="o in orders" :key="o.id" class="hover:bg-earth-50 border-b border-earth-100 last:border-0 transition-colors">
            <td class="table-cell font-mono text-sm text-earth-800 font-medium">{{ o.order_number }}</td>
            <td class="table-cell hidden sm:table-cell">
              <div>
                <p class="text-sm font-medium text-earth-800">{{ o.user?.name || 'Customer' }}</p>
                <p class="text-xs text-earth-400">{{ o.user?.email }}</p>
              </div>
            </td>
            <td class="table-cell hidden md:table-cell text-earth-600 text-sm">{{ o.items?.length || 0 }} item(s)</td>
            <td class="table-cell font-bold text-earth-900">${{ Number(o.total).toFixed(2) }}</td>
            <td class="table-cell"><span :class="statusBadge(o.status)">{{ o.status }}</span></td>
            <td class="table-cell">
              <select :value="o.status" @change="updateStatus(o, $event.target.value)"
                class="text-xs border border-earth-200 rounded-sm px-2 py-1.5 bg-white focus:border-coffee-500 focus:outline-none cursor-pointer">
                <option v-for="s in statuses" :key="s" :value="s" class="capitalize">{{ s }}</option>
              </select>
            </td>
            <td class="table-cell hidden lg:table-cell text-earth-400 text-sm">{{ formatDate(o.created_at) }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else class="card p-12 text-center">
      <p class="text-4xl mb-3">📦</p>
      <p class="text-earth-600">No orders found</p>
    </div>

    <Transition name="slide-up">
      <div v-if="toast" class="fixed bottom-6 right-6 bg-coffee-800 text-white px-5 py-3 rounded-sm shadow-warm-lg text-sm z-50">{{ toast }}</div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { adminApi, ordersApi } from '@/api'

const orders       = ref([])
const loading      = ref(true)
const search       = ref('')
const statusFilter = ref('')
const toast        = ref('')
const statuses     = ['pending','approved','processing','shipped','delivered','cancelled']

const DEMO = [
  { id:1, order_number:'BUN-A1B2C3D4', status:'delivered', total:'255.00', created_at:'2025-06-01T10:00:00Z', user:{name:'Sara Haile',email:'customer@bunna.et'}, items:[{id:1},{id:2}] },
  { id:2, order_number:'BUN-E5F6G7H8', status:'shipped',   total:'136.00', created_at:'2025-06-10T09:00:00Z', user:{name:'Dawit Tesfaye',email:'dawit@example.com'}, items:[{id:3}] },
  { id:3, order_number:'BUN-I9J0K1L2', status:'pending',   total:'88.00',  created_at:'2025-06-18T14:00:00Z', user:{name:'James Morgan',email:'investor@bunna.et'}, items:[{id:4}] },
  { id:4, order_number:'BUN-M3N4O5P6', status:'approved',  total:'342.00', created_at:'2025-06-19T11:00:00Z', user:{name:'Selam Bekele',email:'trader@bunna.et'}, items:[{id:5},{id:6}] },
  { id:5, order_number:'BUN-Q7R8S9T0', status:'processing',total:'178.00', created_at:'2025-06-20T08:00:00Z', user:{name:'Tigist Alemu',email:'tigist@example.com'}, items:[{id:7}] },
]

const statusStats = computed(() => [
  { label:'Pending',    color:'text-gold-600',   count: orders.value.filter(o=>o.status==='pending').length },
  { label:'Approved',   color:'text-forest-600', count: orders.value.filter(o=>o.status==='approved').length },
  { label:'Processing', color:'text-blue-600',   count: orders.value.filter(o=>o.status==='processing').length },
  { label:'Shipped',    color:'text-coffee-600', count: orders.value.filter(o=>o.status==='shipped').length },
  { label:'Delivered',  color:'text-earth-700',  count: orders.value.filter(o=>o.status==='delivered').length },
])

let timer = null
function debouncedFetch() { clearTimeout(timer); timer = setTimeout(fetchOrders, 400) }

async function fetchOrders() {
  loading.value = true
  try {
    const res = await adminApi.allOrders({ status: statusFilter.value || undefined, search: search.value || undefined })
    orders.value = res.data.data || res.data
  } catch {
    let list = [...DEMO]
    if (statusFilter.value) list = list.filter(o => o.status === statusFilter.value)
    if (search.value) list = list.filter(o => o.order_number.includes(search.value.toUpperCase()))
    orders.value = list
  } finally { loading.value = false }
}

async function updateStatus(order, newStatus) {
  const prev = order.status
  order.status = newStatus
  try {
    await ordersApi.updateStatus(order.id, newStatus)
    showToast(`Order ${order.order_number} → ${newStatus}`)
  } catch {
    showToast(`Status updated (demo mode)`)
  }
}

function statusBadge(s) {
  return { pending:'badge-pending', approved:'badge-approved', processing:'badge-farmer', shipped:'badge-trader', delivered:'badge-approved text-forest-800', cancelled:'bg-red-100 text-red-700 inline-flex px-2 py-1 text-xs font-semibold rounded-full' }[s] || 'badge-pending'
}
function formatDate(d) { return d ? new Date(d).toLocaleDateString('en-ET', { day:'numeric', month:'short', year:'numeric' }) : '' }
function showToast(msg) { toast.value = msg; setTimeout(() => { toast.value = '' }, 2500) }

onMounted(fetchOrders)
</script>

<style scoped>
.slide-up-enter-active,.slide-up-leave-active { transition:all 0.3s ease; }
.slide-up-enter-from,.slide-up-leave-to { opacity:0; transform:translateY(20px); }
</style>