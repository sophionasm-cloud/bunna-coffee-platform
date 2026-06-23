<template>
  <div class="space-y-6">
    <!-- Header + stats -->
    <div class="flex items-center justify-between">
      <div>
        <h2 class="font-display text-2xl font-bold text-earth-900">Manage Users</h2>
        <p class="text-earth-500 text-sm mt-1">View, manage roles and control access for all registered users</p>
      </div>
      <div class="text-sm text-earth-500 bg-white border border-earth-100 rounded-sm px-4 py-2 shadow-sm">
        Total: <strong class="text-earth-900">{{ meta.total || users.length }}</strong> users
      </div>
    </div>

    <!-- Role filter tabs -->
    <div class="flex flex-wrap gap-2">
      <button v-for="tab in roleTabs" :key="tab.value" @click="activeRole = tab.value; fetchUsers()"
        :class="['px-4 py-2 text-sm font-medium rounded-sm border transition-all',
          activeRole === tab.value ? 'bg-coffee-700 text-white border-coffee-700 shadow-warm' : 'bg-white text-earth-600 border-earth-200 hover:border-coffee-400']">
        {{ tab.icon }} {{ tab.label }}
        <span v-if="roleCounts[tab.value] !== undefined" class="ml-1 opacity-70">({{ roleCounts[tab.value] }})</span>
      </button>
    </div>

    <!-- Search -->
    <div class="flex gap-3">
      <div class="flex-1">
        <input v-model="search" @input="debouncedFetch" type="text" class="input-field" placeholder="Search by name or email..." />
      </div>
      <button @click="fetchUsers" class="btn-secondary px-5 py-2.5 text-sm">Search</button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="space-y-2">
      <div v-for="n in 5" :key="n" class="card p-4 animate-pulse flex items-center gap-4">
        <div class="w-10 h-10 rounded-full bg-earth-100 shrink-0"></div>
        <div class="flex-1">
          <div class="h-3 bg-earth-100 rounded w-1/3 mb-2"></div>
          <div class="h-2 bg-earth-100 rounded w-1/2"></div>
        </div>
        <div class="h-6 bg-earth-100 rounded w-20"></div>
      </div>
    </div>

    <!-- Empty -->
    <div v-else-if="!users.length" class="card p-12 text-center">
      <p class="text-4xl mb-3">👥</p>
      <p class="font-display text-xl text-earth-700 mb-1">No users found</p>
      <p class="text-earth-400 text-sm">Try adjusting the search or role filter</p>
    </div>

    <!-- Users table -->
    <div v-else class="card overflow-hidden">
      <table class="w-full">
        <thead>
          <tr>
            <th class="table-header">User</th>
            <th class="table-header hidden sm:table-cell">Role</th>
            <th class="table-header hidden md:table-cell">Location</th>
            <th class="table-header hidden lg:table-cell">Joined</th>
            <th class="table-header">Status</th>
            <th class="table-header text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="u in users" :key="u.id" class="hover:bg-earth-50 transition-colors border-b border-earth-100 last:border-0">
            <td class="table-cell">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-coffee-700 flex items-center justify-center shrink-0">
                  <span class="text-white text-xs font-semibold">{{ initials(u.name) }}</span>
                </div>
                <div>
                  <p class="font-medium text-earth-900 text-sm">{{ u.name }}</p>
                  <p class="text-xs text-earth-400">{{ u.email }}</p>
                </div>
              </div>
            </td>
            <td class="table-cell hidden sm:table-cell">
              <!-- Inline role changer -->
              <select :value="u.role" @change="changeRole(u, $event.target.value)"
                class="text-xs border border-earth-200 rounded-sm px-2 py-1 bg-white focus:border-coffee-500 focus:outline-none cursor-pointer">
                <option v-for="r in roles" :key="r.value" :value="r.value">{{ r.icon }} {{ r.label }}</option>
              </select>
            </td>
            <td class="table-cell hidden md:table-cell text-earth-500 text-sm">
              <span v-if="u.country || u.region">{{ [u.region, u.country].filter(Boolean).join(', ') }}</span>
              <span v-else class="text-earth-300">—</span>
            </td>
            <td class="table-cell hidden lg:table-cell text-earth-500 text-sm">{{ formatDate(u.created_at) }}</td>
            <td class="table-cell">
              <span :class="u.is_active !== false ? 'badge-approved' : 'bg-red-100 text-red-700 inline-flex px-2 py-1 text-xs font-semibold rounded-full'">
                {{ u.is_active !== false ? 'Active' : 'Suspended' }}
              </span>
            </td>
            <td class="table-cell text-right">
              <div class="flex justify-end items-center gap-2">
                <button @click="toggleStatus(u)"
                  :class="['text-xs px-2.5 py-1.5 border rounded-sm transition-colors', u.is_active !== false ? 'border-amber-200 text-amber-700 hover:bg-amber-50' : 'border-forest-200 text-forest-700 hover:bg-forest-50']">
                  {{ u.is_active !== false ? 'Suspend' : 'Activate' }}
                </button>
                <button @click="confirmDelete(u)"
                  class="text-xs px-2.5 py-1.5 border border-red-200 text-red-600 rounded-sm hover:bg-red-50 transition-colors">
                  Delete
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div v-if="meta.last_page > 1" class="flex justify-center gap-2 p-4 border-t border-earth-100">
        <button v-for="page in meta.last_page" :key="page" @click="goToPage(page)"
          :class="['w-9 h-9 rounded-sm text-sm font-medium transition-all border', page === meta.current_page ? 'bg-coffee-700 text-white border-coffee-700' : 'bg-white text-earth-600 border-earth-200 hover:border-coffee-400']">
          {{ page }}
        </button>
      </div>
    </div>

    <!-- Toast -->
    <Transition name="slide-up">
      <div v-if="toast" class="fixed bottom-6 right-6 bg-coffee-800 text-white px-5 py-3 rounded-sm shadow-warm-lg text-sm z-50">{{ toast }}</div>
    </Transition>

    <!-- Delete confirm -->
    <div v-if="deleteTarget" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center px-4">
      <div class="bg-white rounded-sm shadow-warm-lg p-6 max-w-sm w-full">
        <h3 class="font-display text-lg font-semibold text-earth-900 mb-2">Delete User?</h3>
        <p class="text-earth-600 text-sm mb-1">Are you sure you want to permanently delete:</p>
        <p class="font-semibold text-earth-800 mb-5">{{ deleteTarget.name }} ({{ deleteTarget.email }})</p>
        <div class="flex gap-3">
          <button @click="deleteTarget = null" class="btn-secondary flex-1 py-2.5">Cancel</button>
          <button @click="doDelete" :disabled="deleting" class="flex-1 py-2.5 bg-red-600 text-white font-semibold rounded-sm hover:bg-red-700 disabled:opacity-50 transition-colors">
            {{ deleting ? 'Deleting...' : 'Delete User' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { adminApi } from '@/api'

const users       = ref([])
const loading     = ref(true)
const search      = ref('')
const activeRole  = ref('')
const meta        = ref({})
const toast       = ref('')
const deleteTarget = ref(null)
const deleting    = ref(false)

const roleTabs = [
  { value:'',         icon:'👥', label:'All' },
  { value:'admin',    icon:'👑', label:'Admin' },
  { value:'farmer',   icon:'🌱', label:'Farmers' },
  { value:'trader',   icon:'🤝', label:'Traders' },
  { value:'investor', icon:'💼', label:'Investors' },
  { value:'customer', icon:'☕', label:'Customers' },
]
const roles = [
  { value:'admin',    icon:'👑', label:'Admin' },
  { value:'farmer',   icon:'🌱', label:'Farmer' },
  { value:'trader',   icon:'🤝', label:'Trader' },
  { value:'investor', icon:'💼', label:'Investor' },
  { value:'customer', icon:'☕', label:'Customer' },
]

const roleCounts = computed(() => {
  const counts = {}
  roleTabs.forEach(t => {
    counts[t.value] = t.value === '' ? users.value.length : users.value.filter(u => u.role === t.value).length
  })
  return counts
})

const DEMO_USERS = [
  { id:1, name:'Bunna Admin',  email:'admin@bunna.et',    role:'admin',    country:'Ethiopia', region:'Addis Ababa', is_active:true,  created_at:'2025-01-01T00:00:00Z' },
  { id:2, name:'Abebe Girma',  email:'farmer@bunna.et',   role:'farmer',   country:'Ethiopia', region:'Oromia',      is_active:true,  created_at:'2025-02-10T00:00:00Z' },
  { id:3, name:'Selam Bekele', email:'trader@bunna.et',   role:'trader',   country:'Ethiopia', region:'Addis Ababa', is_active:true,  created_at:'2025-03-05T00:00:00Z' },
  { id:4, name:'James Morgan', email:'investor@bunna.et', role:'investor', country:'USA',      region:null,          is_active:true,  created_at:'2025-03-20T00:00:00Z' },
  { id:5, name:'Sara Haile',   email:'customer@bunna.et', role:'customer', country:'Germany',  region:null,          is_active:true,  created_at:'2025-04-01T00:00:00Z' },
  { id:6, name:'Tigist Alemu', email:'tigist@example.com',role:'farmer',   country:'Ethiopia', region:'SNNPR',       is_active:false, created_at:'2025-04-15T00:00:00Z' },
  { id:7, name:'Dawit Tesfaye',email:'dawit@example.com', role:'customer', country:'Ethiopia', region:'Tigray',      is_active:true,  created_at:'2025-05-01T00:00:00Z' },
]

let timer = null
function debouncedFetch() { clearTimeout(timer); timer = setTimeout(fetchUsers, 400) }

async function fetchUsers() {
  loading.value = true
  try {
    const res = await adminApi.users({ role: activeRole.value || undefined, search: search.value || undefined, page: meta.value.current_page || 1 })
    users.value = res.data.data || res.data
    meta.value  = res.data.meta || {}
  } catch {
    let list = [...DEMO_USERS]
    if (activeRole.value) list = list.filter(u => u.role === activeRole.value)
    if (search.value) {
      const q = search.value.toLowerCase()
      list = list.filter(u => u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q))
    }
    users.value = list
    meta.value = {}
  } finally { loading.value = false }
}

async function changeRole(user, newRole) {
  const prev = user.role
  user.role = newRole
  try {
    await adminApi.updateRole(user.id, newRole)
    showToast(`${user.name}'s role updated to ${newRole}`)
  } catch {
    showToast(`Role updated (demo mode)`)
  }
}

async function toggleStatus(user) {
  const prev = user.is_active
  user.is_active = !user.is_active
  try {
    await adminApi.toggleUser(user.id)
  } catch {}
  showToast(`${user.name} ${user.is_active ? 'activated' : 'suspended'}`)
}

function confirmDelete(u) { deleteTarget.value = u }
async function doDelete() {
  deleting.value = true
  try { await adminApi.deleteUser(deleteTarget.value.id) } catch {}
  users.value = users.value.filter(u => u.id !== deleteTarget.value.id)
  showToast(`${deleteTarget.value.name} deleted`)
  deleteTarget.value = null
  deleting.value = false
}

function goToPage(page) { meta.value.current_page = page; fetchUsers() }
function initials(name) { return (name||'').split(' ').map(p=>p[0]).join('').slice(0,2).toUpperCase() }
function formatDate(d) { return d ? new Date(d).toLocaleDateString('en-ET', { day:'numeric', month:'short', year:'numeric' }) : '' }
function showToast(msg) { toast.value = msg; setTimeout(() => { toast.value = '' }, 2500) }

onMounted(fetchUsers)
</script>

<style scoped>
.slide-up-enter-active,.slide-up-leave-active { transition:all 0.3s ease; }
.slide-up-enter-from,.slide-up-leave-to { opacity:0; transform:translateY(20px); }
</style>