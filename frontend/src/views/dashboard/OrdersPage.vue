<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h2 class="font-display text-2xl font-bold text-earth-900">My Orders</h2>
        <p class="text-earth-500 text-sm mt-1">Track all your coffee orders in real-time</p>
      </div>
      <RouterLink to="/products" class="btn-primary text-sm px-4 py-2.5">+ New Order</RouterLink>
    </div>

    <!-- Status filter tabs -->
    <div class="flex flex-wrap gap-2">
      <button v-for="tab in tabs" :key="tab.value" @click="activeTab = tab.value; fetchOrders()"
        :class="['px-4 py-2 text-sm font-medium rounded-sm border transition-all',
          activeTab === tab.value ? 'bg-coffee-700 text-white border-coffee-700 shadow-warm' : 'bg-white text-earth-600 border-earth-200 hover:border-coffee-400']">
        {{ tab.label }}
        <span v-if="counts[tab.value]" class="ml-1.5 px-1.5 py-0.5 rounded-full text-xs"
          :class="activeTab === tab.value ? 'bg-white/20' : 'bg-earth-100'">
          {{ counts[tab.value] }}
        </span>
      </button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="space-y-3">
      <div v-for="n in 3" :key="n" class="card p-5 animate-pulse">
        <div class="flex justify-between mb-3">
          <div class="h-4 bg-earth-100 rounded w-32"></div>
          <div class="h-6 bg-earth-100 rounded w-20"></div>
        </div>
        <div class="h-3 bg-earth-100 rounded w-48 mb-2"></div>
        <div class="h-3 bg-earth-100 rounded w-24"></div>
      </div>
    </div>

    <!-- Empty -->
    <div v-else-if="!orders.length" class="card p-16 text-center">
      <p class="text-5xl mb-4">📦</p>
      <p class="font-display text-xl text-earth-700 mb-2">No orders yet</p>
      <p class="text-earth-400 text-sm mb-6">Browse our premium Ethiopian coffees and place your first order.</p>
      <RouterLink to="/products" class="btn-primary">Browse Coffee</RouterLink>
    </div>

    <!-- Orders list -->
    <div v-else class="space-y-4">
      <div v-for="order in orders" :key="order.id" class="card p-5 hover:shadow-warm transition-shadow">
        <!-- Top row -->
        <div class="flex items-start justify-between mb-3">
          <div>
            <div class="flex items-center gap-3 mb-1">
              <span class="font-semibold text-earth-900 font-mono text-sm">{{ order.order_number }}</span>
              <span :class="statusBadge(order.status)">{{ statusLabel(order.status) }}</span>
            </div>
            <p class="text-xs text-earth-400">Placed {{ formatDate(order.created_at) }}</p>
          </div>
          <div class="text-right">
            <p class="font-bold text-lg text-earth-900">${{ Number(order.total).toFixed(2) }}</p>
            <p class="text-xs text-earth-400">{{ order.items?.length || 0 }} item(s)</p>
          </div>
        </div>

        <!-- Progress tracker -->
        <div class="my-4">
          <div class="flex items-center justify-between relative">
            <div class="absolute top-3 left-0 right-0 h-0.5 bg-earth-100 z-0"></div>
            <div class="absolute top-3 left-0 h-0.5 bg-coffee-600 z-0 transition-all duration-500"
              :style="{ width: progressWidth(order.status) }"></div>
            <div v-for="step in statusSteps" :key="step.key"
              class="relative z-10 flex flex-col items-center gap-1">
              <div :class="['w-6 h-6 rounded-full border-2 flex items-center justify-center text-xs transition-all',
                stepDone(order.status, step.key) ? 'bg-coffee-700 border-coffee-700 text-white' : 'bg-white border-earth-200 text-earth-400']">
                {{ stepDone(order.status, step.key) ? '✓' : '·' }}
              </div>
              <span class="text-xs text-earth-400 hidden sm:block">{{ step.label }}</span>
            </div>
          </div>
        </div>

        <!-- Items preview -->
        <div v-if="order.items?.length" class="bg-earth-50 rounded-sm p-3 mb-3">
          <div v-for="item in order.items" :key="item.id" class="flex justify-between text-sm py-1 border-b border-earth-100 last:border-0">
            <span class="text-earth-700">{{ item.product?.name || 'Coffee Product' }}</span>
            <span class="text-earth-500">{{ item.quantity_kg }} kg × ${{ Number(item.price_per_kg).toFixed(2) }}</span>
          </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2 text-xs text-earth-400">
            <span v-if="order.shipping_country">🌍 {{ order.shipping_country }}</span>
            <span v-if="order.shipped_at">· Shipped {{ formatDate(order.shipped_at) }}</span>
          </div>
          <div class="flex gap-2">
            <RouterLink :to="`/dashboard/orders/${order.id}`" class="btn-secondary text-xs px-3 py-1.5">View Details</RouterLink>
            <button v-if="order.status === 'pending'" @click="cancelOrder(order)"
              class="text-xs px-3 py-1.5 border border-red-200 text-red-600 rounded-sm hover:bg-red-50 transition-colors">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Cancel confirm modal -->
    <div v-if="cancelTarget" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center px-4">
      <div class="bg-white rounded-sm shadow-warm-lg p-6 max-w-sm w-full">
        <h3 class="font-display text-lg font-semibold text-earth-900 mb-2">Cancel Order?</h3>
        <p class="text-earth-600 text-sm mb-5">Are you sure you want to cancel order <strong>{{ cancelTarget.order_number }}</strong>? This cannot be undone.</p>
        <div class="flex gap-3">
          <button @click="cancelTarget = null" class="btn-secondary flex-1 py-2.5">Keep Order</button>
          <button @click="confirmCancel" :disabled="cancelling" class="flex-1 py-2.5 bg-red-600 text-white font-semibold rounded-sm hover:bg-red-700 disabled:opacity-50 transition-colors">
            {{ cancelling ? 'Cancelling...' : 'Yes, Cancel' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { RouterLink } from 'vue-router'
import { ordersApi } from '@/api'

const orders      = ref([])
const loading     = ref(true)
const activeTab   = ref('')
const cancelTarget = ref(null)
const cancelling  = ref(false)
const counts      = reactive({})

const tabs = [
  { label: 'All',        value: '' },
  { label: 'Pending',    value: 'pending' },
  { label: 'Approved',   value: 'approved' },
  { label: 'Processing', value: 'processing' },
  { label: 'Shipped',    value: 'shipped' },
  { label: 'Delivered',  value: 'delivered' },
]

const statusSteps = [
  { key: 'pending',    label: 'Pending' },
  { key: 'approved',   label: 'Approved' },
  { key: 'processing', label: 'Processing' },
  { key: 'shipped',    label: 'Shipped' },
  { key: 'delivered',  label: 'Delivered' },
]

const statusOrder = ['pending','approved','processing','shipped','delivered']

const DEMO_ORDERS = [
  { id:1, order_number:'BUN-A1B2C3D4', status:'delivered', total:'255.00', created_at:'2025-06-01T10:00:00Z', shipped_at:'2025-06-05T10:00:00Z', shipping_country:'Germany', items:[{id:1,product:{name:'Yirgacheffe Grade 1'},quantity_kg:20,price_per_kg:'8.50'},{id:2,product:{name:'Sidama Grade 2'},quantity_kg:10,price_per_kg:'7.20'}] },
  { id:2, order_number:'BUN-E5F6G7H8', status:'shipped',   total:'136.00', created_at:'2025-06-10T09:00:00Z', shipped_at:'2025-06-13T10:00:00Z', shipping_country:'Japan',   items:[{id:3,product:{name:'Guji Anaerobic Natural'},quantity_kg:8,price_per_kg:'12.00'},{id:4,product:{name:'Kaffa Forest Coffee'},quantity_kg:7,price_per_kg:'9.50'}] },
  { id:3, order_number:'BUN-I9J0K1L2', status:'pending',   total:'88.00',  created_at:'2025-06-18T14:00:00Z', shipped_at:null, shipping_country:'USA',    items:[{id:5,product:{name:'Harrar Natural Grade 4'},quantity_kg:13,price_per_kg:'6.80'}] },
]

async function fetchOrders() {
  loading.value = true
  try {
    const res = await ordersApi.list(activeTab.value ? { status: activeTab.value } : {})
    orders.value = res.data.data || res.data
    buildCounts(orders.value)
  } catch {
    orders.value = activeTab.value ? DEMO_ORDERS.filter(o => o.status === activeTab.value) : DEMO_ORDERS
    buildCounts(DEMO_ORDERS)
  } finally { loading.value = false }
}

function buildCounts(list) {
  statusOrder.forEach(s => { counts[s] = list.filter(o => o.status === s).length })
}

function cancelOrder(order) { cancelTarget.value = order }
async function confirmCancel() {
  cancelling.value = true
  try {
    await ordersApi.updateStatus(cancelTarget.value.id, 'cancelled')
    const idx = orders.value.findIndex(o => o.id === cancelTarget.value.id)
    if (idx !== -1) orders.value[idx].status = 'cancelled'
  } catch {
    const idx = orders.value.findIndex(o => o.id === cancelTarget.value.id)
    if (idx !== -1) orders.value[idx].status = 'cancelled'
  } finally { cancelling.value = false; cancelTarget.value = null }
}

function statusBadge(s) {
  return { pending:'badge-pending', approved:'badge-approved', processing:'badge-farmer', shipped:'badge-trader', delivered:'badge-approved', cancelled:'bg-red-100 text-red-700 inline-flex px-3 py-1 text-xs font-semibold rounded-full' }[s] || 'badge-pending'
}
function statusLabel(s) { return s.charAt(0).toUpperCase() + s.slice(1) }

function stepDone(status, step) {
  const idx = statusOrder.indexOf(status)
  return statusOrder.indexOf(step) <= idx
}
function progressWidth(status) {
  const idx = statusOrder.indexOf(status)
  return idx < 0 ? '0%' : `${(idx / (statusOrder.length - 1)) * 100}%`
}
function formatDate(d) { return d ? new Date(d).toLocaleDateString('en-ET', { day:'numeric', month:'short', year:'numeric' }) : '' }

onMounted(fetchOrders)
</script>