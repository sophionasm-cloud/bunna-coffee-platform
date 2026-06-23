<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h2 class="font-display text-2xl font-bold text-earth-900">My Coffee Listings</h2>
        <p class="text-earth-500 text-sm mt-1">Manage your coffee crops available for sale</p>
      </div>
      <button @click="openModal()" class="btn-primary text-sm px-4 py-2.5">+ Add Listing</button>
    </div>

    <!-- Stats row -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
      <div class="stat-card">
        <p class="text-sm text-earth-500 mb-1">Total Listings</p>
        <p class="font-display text-3xl font-bold text-earth-900">{{ listings.length }}</p>
      </div>
      <div class="stat-card">
        <p class="text-sm text-earth-500 mb-1">Approved</p>
        <p class="font-display text-3xl font-bold text-forest-700">{{ listings.filter(l=>l.status==='approved').length }}</p>
      </div>
      <div class="stat-card">
        <p class="text-sm text-earth-500 mb-1">Pending</p>
        <p class="font-display text-3xl font-bold text-gold-600">{{ listings.filter(l=>l.status==='pending').length }}</p>
      </div>
      <div class="stat-card">
        <p class="text-sm text-earth-500 mb-1">Sold</p>
        <p class="font-display text-3xl font-bold text-coffee-700">{{ listings.filter(l=>l.status==='sold').length }}</p>
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="space-y-3">
      <div v-for="n in 3" :key="n" class="card p-5 animate-pulse flex gap-4">
        <div class="w-20 h-20 bg-earth-100 rounded-sm shrink-0"></div>
        <div class="flex-1">
          <div class="h-4 bg-earth-100 rounded w-1/2 mb-2"></div>
          <div class="h-3 bg-earth-100 rounded w-1/3 mb-3"></div>
          <div class="h-3 bg-earth-100 rounded w-1/4"></div>
        </div>
      </div>
    </div>

    <!-- Empty -->
    <div v-else-if="!listings.length" class="card p-16 text-center">
      <p class="text-5xl mb-4">🌿</p>
      <p class="font-display text-xl text-earth-700 mb-2">No listings yet</p>
      <p class="text-earth-400 text-sm mb-6">List your coffee crops to start connecting with traders and buyers worldwide.</p>
      <button @click="openModal()" class="btn-primary">Add Your First Listing</button>
    </div>

    <!-- Listings table -->
    <div v-else class="card overflow-hidden">
      <table class="w-full">
        <thead>
          <tr>
            <th class="table-header">Coffee</th>
            <th class="table-header hidden sm:table-cell">Origin</th>
            <th class="table-header hidden md:table-cell">Grade</th>
            <th class="table-header">Qty (kg)</th>
            <th class="table-header">Price/kg</th>
            <th class="table-header">Status</th>
            <th class="table-header text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="l in listings" :key="l.id" class="hover:bg-earth-50 transition-colors">
            <td class="table-cell">
              <div>
                <p class="font-medium text-earth-900">{{ l.title }}</p>
                <p class="text-xs text-earth-400 mt-0.5">{{ formatDate(l.created_at) }}</p>
              </div>
            </td>
            <td class="table-cell hidden sm:table-cell text-earth-600">{{ l.origin }}</td>
            <td class="table-cell hidden md:table-cell">
              <span v-if="l.grade" class="badge-approved text-xs">{{ l.grade }}</span>
              <span v-else class="text-earth-400 text-xs">—</span>
            </td>
            <td class="table-cell font-medium text-earth-800">{{ Number(l.quantity_kg).toLocaleString() }}</td>
            <td class="table-cell font-semibold text-coffee-700">${{ Number(l.asking_price_per_kg).toFixed(2) }}</td>
            <td class="table-cell">
              <span :class="statusBadge(l.status)">{{ l.status }}</span>
            </td>
            <td class="table-cell text-right">
              <div class="flex justify-end gap-2">
                <button @click="openModal(l)" class="text-xs px-3 py-1.5 border border-earth-200 text-earth-600 rounded-sm hover:bg-earth-50 transition-colors">Edit</button>
                <button @click="confirmDelete(l)" class="text-xs px-3 py-1.5 border border-red-200 text-red-600 rounded-sm hover:bg-red-50 transition-colors">Delete</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Add/Edit Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center px-4 overflow-y-auto">
      <div class="bg-white rounded-sm shadow-warm-lg w-full max-w-lg my-6">
        <div class="flex items-center justify-between p-5 border-b border-earth-100">
          <h3 class="font-display text-lg font-semibold text-earth-900">{{ editing ? 'Edit Listing' : 'Add New Listing' }}</h3>
          <button @click="closeModal" class="text-earth-400 hover:text-earth-700 text-xl leading-none">×</button>
        </div>
        <form @submit.prevent="saveListing" class="p-5 space-y-4">
          <div v-if="formError" class="bg-red-50 border border-red-200 rounded-sm px-4 py-3 text-sm text-red-700">{{ formError }}</div>
          <div>
            <label class="input-label">Coffee Title / Name</label>
            <input v-model="form.title" required class="input-field" placeholder="Yirgacheffe Natural Grade 1" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="input-label">Origin Region</label>
              <input v-model="form.origin" required class="input-field" placeholder="Yirgacheffe, SNNPR" />
            </div>
            <div>
              <label class="input-label">Grade</label>
              <select v-model="form.grade" class="select-field">
                <option value="">Select grade</option>
                <option>Grade 1</option><option>Grade 2</option><option>Grade 3</option><option>Grade 4</option><option>Grade 5</option>
              </select>
            </div>
            <div>
              <label class="input-label">Process Method</label>
              <select v-model="form.process" class="select-field">
                <option value="">Select process</option>
                <option>Washed</option><option>Natural</option><option>Honey</option><option>Anaerobic Natural</option>
              </select>
            </div>
            <div>
              <label class="input-label">Harvest Date</label>
              <input v-model="form.harvest_date" type="date" class="input-field" />
            </div>
            <div>
              <label class="input-label">Quantity (kg)</label>
              <input v-model.number="form.quantity_kg" type="number" required min="1" class="input-field" placeholder="500" />
            </div>
            <div>
              <label class="input-label">Asking Price ($/kg)</label>
              <input v-model.number="form.asking_price_per_kg" type="number" required min="0" step="0.01" class="input-field" placeholder="8.50" />
            </div>
          </div>
          <div>
            <label class="input-label">Description</label>
            <textarea v-model="form.description" rows="3" class="input-field resize-none" placeholder="Describe your coffee — flavour notes, processing details..."></textarea>
          </div>
          <div class="flex gap-3 pt-2">
            <button type="button" @click="closeModal" class="btn-secondary flex-1 py-3">Cancel</button>
            <button type="submit" :disabled="saving" class="btn-primary flex-1 py-3">
              {{ saving ? 'Saving...' : (editing ? 'Update Listing' : 'Submit Listing') }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete confirm -->
    <div v-if="deleteTarget" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center px-4">
      <div class="bg-white rounded-sm shadow-warm-lg p-6 max-w-sm w-full">
        <h3 class="font-display text-lg font-semibold text-earth-900 mb-2">Delete Listing?</h3>
        <p class="text-earth-600 text-sm mb-5">Are you sure you want to delete <strong>{{ deleteTarget.title }}</strong>?</p>
        <div class="flex gap-3">
          <button @click="deleteTarget = null" class="btn-secondary flex-1 py-2.5">Cancel</button>
          <button @click="doDelete" :disabled="deleting" class="flex-1 py-2.5 bg-red-600 text-white font-semibold rounded-sm hover:bg-red-700 disabled:opacity-50 transition-colors">
            {{ deleting ? 'Deleting...' : 'Delete' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { listingsApi } from '@/api'

const listings    = ref([])
const loading     = ref(true)
const showModal   = ref(false)
const editing     = ref(null)
const saving      = ref(false)
const formError   = ref('')
const deleteTarget = ref(null)
const deleting    = ref(false)

const form = reactive({ title:'', origin:'', grade:'', process:'', harvest_date:'', quantity_kg:'', asking_price_per_kg:'', description:'' })

const DEMO = [
  { id:1, title:'Yirgacheffe Natural Grade 1', origin:'Yirgacheffe, SNNPR', grade:'Grade 1', process:'Natural', quantity_kg:2000, asking_price_per_kg:'8.50', status:'approved', created_at:'2025-05-10T00:00:00Z' },
  { id:2, title:'Sidama Washed — New Harvest',  origin:'Sidama, Ethiopia',   grade:'Grade 2', process:'Washed',  quantity_kg:5000, asking_price_per_kg:'7.00', status:'pending',  created_at:'2025-06-01T00:00:00Z' },
  { id:3, title:'Harrar Natural Grade 4',        origin:'Harrar, Oromia',     grade:'Grade 4', process:'Natural', quantity_kg:3500, asking_price_per_kg:'6.50', status:'sold',     created_at:'2025-04-15T00:00:00Z' },
]

async function fetchListings() {
  loading.value = true
  try {
    const res = await listingsApi.list()
    listings.value = res.data.data || res.data
  } catch { listings.value = [...DEMO] }
  finally { loading.value = false }
}

function openModal(listing = null) {
  formError.value = ''
  if (listing) {
    editing.value = listing.id
    Object.assign(form, { title:listing.title, origin:listing.origin, grade:listing.grade||'', process:listing.process||'', harvest_date:listing.harvest_date||'', quantity_kg:listing.quantity_kg, asking_price_per_kg:listing.asking_price_per_kg, description:listing.description||'' })
  } else {
    editing.value = null
    Object.assign(form, { title:'', origin:'', grade:'', process:'', harvest_date:'', quantity_kg:'', asking_price_per_kg:'', description:'' })
  }
  showModal.value = true
}

function closeModal() { showModal.value = false; editing.value = null }

async function saveListing() {
  saving.value = true; formError.value = ''
  const payload = { ...form }
  try {
    if (editing.value) {
      const res = await listingsApi.update(editing.value, payload)
      const idx = listings.value.findIndex(l => l.id === editing.value)
      if (idx !== -1) listings.value[idx] = res.data
    } else {
      const res = await listingsApi.create(payload)
      listings.value.unshift(res.data)
    }
    closeModal()
  } catch {
    // Demo mode
    if (editing.value) {
      const idx = listings.value.findIndex(l => l.id === editing.value)
      if (idx !== -1) listings.value[idx] = { ...listings.value[idx], ...payload }
    } else {
      listings.value.unshift({ id: Date.now(), ...payload, status:'pending', created_at: new Date().toISOString() })
    }
    closeModal()
  } finally { saving.value = false }
}

function confirmDelete(l) { deleteTarget.value = l }
async function doDelete() {
  deleting.value = true
  try {
    await listingsApi.delete(deleteTarget.value.id)
  } catch {}
  listings.value = listings.value.filter(l => l.id !== deleteTarget.value.id)
  deleteTarget.value = null
  deleting.value = false
}

function statusBadge(s) {
  return { approved:'badge-approved', pending:'badge-pending', sold:'badge-trader', draft:'badge-admin' }[s] || 'badge-pending'
}
function formatDate(d) { return d ? new Date(d).toLocaleDateString('en-ET', { day:'numeric', month:'short', year:'numeric' }) : '' }

onMounted(fetchListings)
</script>