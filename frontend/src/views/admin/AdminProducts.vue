<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="font-display text-2xl font-bold text-earth-900">Manage Products</h2>
        <p class="text-earth-500 text-sm mt-1">Add, edit and manage all coffee products on the platform</p>
      </div>
      <button @click="openModal()" class="btn-primary text-sm px-4 py-2.5">+ Add Product</button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4">
      <div class="stat-card"><p class="text-sm text-earth-500 mb-1">Total Products</p><p class="font-display text-3xl font-bold text-earth-900">{{ products.length }}</p></div>
      <div class="stat-card"><p class="text-sm text-earth-500 mb-1">Available</p><p class="font-display text-3xl font-bold text-forest-700">{{ products.filter(p=>p.is_available).length }}</p></div>
      <div class="stat-card"><p class="text-sm text-earth-500 mb-1">Featured</p><p class="font-display text-3xl font-bold text-gold-600">{{ products.filter(p=>p.is_featured).length }}</p></div>
    </div>

    <!-- Search -->
    <input v-model="search" @input="debouncedFetch" type="text" class="input-field max-w-sm" placeholder="Search products..." />

    <!-- Loading -->
    <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <div v-for="n in 6" :key="n" class="card p-4 animate-pulse">
        <div class="h-32 bg-earth-100 rounded-sm mb-3"></div>
        <div class="h-3 bg-earth-100 rounded mb-2 w-3/4"></div>
        <div class="h-3 bg-earth-100 rounded w-1/2"></div>
      </div>
    </div>

    <!-- Grid -->
    <div v-else-if="products.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <div v-for="p in products" :key="p.id" class="card overflow-hidden">
        <div class="h-32 bg-ethiopian-weave flex items-center justify-center relative">
          <img v-if="p.image" :src="`/storage/${p.image}`" :alt="p.name" class="w-full h-full object-cover" />
          <span v-else class="text-4xl">☕</span>
          <div class="absolute top-2 right-2 flex flex-col gap-1">
            <button @click="toggleFeatured(p)" :class="['text-xs px-2 py-0.5 rounded-sm font-medium transition-colors', p.is_featured ? 'bg-gold-500 text-white' : 'bg-white/80 text-earth-600 hover:bg-gold-100']">⭐ Featured</button>
            <button @click="toggleAvailable(p)" :class="['text-xs px-2 py-0.5 rounded-sm font-medium transition-colors', p.is_available ? 'bg-forest-600 text-white' : 'bg-red-100 text-red-700 hover:bg-red-200']">{{ p.is_available ? '● Live' : '○ Hidden' }}</button>
          </div>
        </div>
        <div class="p-4">
          <div class="flex justify-between items-start mb-1">
            <h3 class="font-semibold text-earth-900 text-sm leading-tight flex-1">{{ p.name }}</h3>
            <span class="text-coffee-700 font-bold shrink-0 ml-2">${{ Number(p.price_per_kg).toFixed(2) }}</span>
          </div>
          <p class="text-xs text-earth-500 mb-1">📍 {{ p.origin }} <span v-if="p.grade">· {{ p.grade }}</span></p>
          <p class="text-xs text-earth-400 mb-3">Stock: {{ Number(p.stock_kg).toLocaleString() }} kg</p>
          <div class="flex gap-2">
            <button @click="openModal(p)" class="btn-secondary text-xs px-3 py-1.5 flex-1">Edit</button>
            <button @click="confirmDelete(p)" class="text-xs px-3 py-1.5 border border-red-200 text-red-600 rounded-sm hover:bg-red-50 transition-colors flex-1">Delete</button>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="card p-12 text-center">
      <p class="text-4xl mb-3">☕</p>
      <p class="text-earth-600">No products found</p>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center px-4 overflow-y-auto">
      <div class="bg-white rounded-sm shadow-warm-lg w-full max-w-lg my-6">
        <div class="flex items-center justify-between p-5 border-b border-earth-100">
          <h3 class="font-display text-lg font-semibold">{{ editing ? 'Edit Product' : 'Add Product' }}</h3>
          <button @click="closeModal" class="text-earth-400 hover:text-earth-700 text-xl">×</button>
        </div>
        <form @submit.prevent="saveProduct" class="p-5 space-y-4">
          <div v-if="formError" class="bg-red-50 border border-red-200 rounded-sm px-4 py-3 text-sm text-red-700">{{ formError }}</div>
          <div>
            <label class="input-label">Product Name</label>
            <input v-model="form.name" required class="input-field" placeholder="Yirgacheffe Grade 1 — Washed" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="input-label">Origin</label>
              <input v-model="form.origin" required class="input-field" placeholder="Yirgacheffe, SNNPR" />
            </div>
            <div>
              <label class="input-label">Grade</label>
              <select v-model="form.grade" class="select-field">
                <option value="">No grade</option>
                <option>Grade 1</option><option>Grade 2</option><option>Grade 3</option><option>Grade 4</option><option>Grade 5</option>
              </select>
            </div>
            <div>
              <label class="input-label">Process</label>
              <select v-model="form.process" class="select-field">
                <option value="">Select</option>
                <option>Washed</option><option>Natural</option><option>Honey</option><option>Anaerobic Natural</option>
              </select>
            </div>
            <div>
              <label class="input-label">Price per kg ($)</label>
              <input v-model.number="form.price_per_kg" type="number" step="0.01" min="0" required class="input-field" placeholder="8.50" />
            </div>
            <div>
              <label class="input-label">Stock (kg)</label>
              <input v-model.number="form.stock_kg" type="number" min="0" required class="input-field" placeholder="5000" />
            </div>
            <div>
              <label class="input-label">Category ID</label>
              <input v-model.number="form.category_id" type="number" class="input-field" placeholder="1" />
            </div>
          </div>
          <div>
            <label class="input-label">Description</label>
            <textarea v-model="form.description" rows="3" class="input-field resize-none" placeholder="Flavour notes, processing details..."></textarea>
          </div>
          <div class="flex gap-4">
            <label class="flex items-center gap-2 text-sm text-earth-700 cursor-pointer">
              <input v-model="form.is_available" type="checkbox" class="w-4 h-4 accent-coffee-700" /> Available for sale
            </label>
            <label class="flex items-center gap-2 text-sm text-earth-700 cursor-pointer">
              <input v-model="form.is_featured" type="checkbox" class="w-4 h-4 accent-coffee-700" /> Featured product
            </label>
          </div>
          <div class="flex gap-3 pt-2">
            <button type="button" @click="closeModal" class="btn-secondary flex-1 py-3">Cancel</button>
            <button type="submit" :disabled="saving" class="btn-primary flex-1 py-3">{{ saving ? 'Saving...' : (editing ? 'Update' : 'Add Product') }}</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete confirm -->
    <div v-if="deleteTarget" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center px-4">
      <div class="bg-white rounded-sm shadow-warm-lg p-6 max-w-sm w-full">
        <h3 class="font-display text-lg font-semibold text-earth-900 mb-2">Delete Product?</h3>
        <p class="text-earth-600 text-sm mb-5">Delete <strong>{{ deleteTarget.name }}</strong>? This cannot be undone.</p>
        <div class="flex gap-3">
          <button @click="deleteTarget = null" class="btn-secondary flex-1 py-2.5">Cancel</button>
          <button @click="doDelete" :disabled="deleting" class="flex-1 py-2.5 bg-red-600 text-white font-semibold rounded-sm hover:bg-red-700 disabled:opacity-50">{{ deleting ? 'Deleting...' : 'Delete' }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { productsApi, adminApi } from '@/api'

const products    = ref([])
const loading     = ref(true)
const search      = ref('')
const showModal   = ref(false)
const editing     = ref(null)
const saving      = ref(false)
const formError   = ref('')
const deleteTarget = ref(null)
const deleting    = ref(false)

const form = reactive({ name:'', origin:'', grade:'', process:'', price_per_kg:'', stock_kg:'', category_id:1, description:'', is_available:true, is_featured:false })

const DEMO = [
  { id:1, name:'Yirgacheffe Grade 1 — Washed', origin:'Yirgacheffe, SNNPR', grade:'Grade 1', process:'Washed', price_per_kg:'8.50', stock_kg:5000, is_available:true, is_featured:true, description:"Ethiopia's most celebrated single-origin." },
  { id:2, name:'Sidama Washed Grade 2', origin:'Sidama, Ethiopia', grade:'Grade 2', process:'Washed', price_per_kg:'7.20', stock_kg:8000, is_available:true, is_featured:true, description:'Clean, balanced, with stone fruit sweetness.' },
  { id:3, name:'Harrar Natural Grade 4', origin:'Harrar, Oromia', grade:'Grade 4', process:'Natural', price_per_kg:'6.80', stock_kg:12000, is_available:true, is_featured:false, description:'Bold and wine-like with deep fruity notes.' },
  { id:4, name:'Jimma Grade 3 — Natural', origin:'Jimma, Oromia', grade:'Grade 3', process:'Natural', price_per_kg:'5.90', stock_kg:20000, is_available:true, is_featured:false, description:'Rich, earthy flavor with chocolate undertones.' },
  { id:5, name:'Guji Anaerobic Natural', origin:'Guji, Oromia', grade:'Grade 1', process:'Anaerobic Natural', price_per_kg:'12.00', stock_kg:2000, is_available:true, is_featured:true, description:'Experimental processing. Tropical fruit forward.' },
]

let timer = null
function debouncedFetch() { clearTimeout(timer); timer = setTimeout(fetchProducts, 400) }

async function fetchProducts() {
  loading.value = true
  try {
    const res = await adminApi.allProducts({ search: search.value || undefined })
    products.value = res.data.data || res.data
  } catch {
    products.value = search.value ? DEMO.filter(p => p.name.toLowerCase().includes(search.value.toLowerCase())) : [...DEMO]
  } finally { loading.value = false }
}

function openModal(p = null) {
  formError.value = ''
  if (p) {
    editing.value = p.id
    Object.assign(form, { name:p.name, origin:p.origin, grade:p.grade||'', process:p.process||'', price_per_kg:p.price_per_kg, stock_kg:p.stock_kg, category_id:p.category_id||1, description:p.description||'', is_available:p.is_available, is_featured:p.is_featured })
  } else {
    editing.value = null
    Object.assign(form, { name:'', origin:'', grade:'', process:'', price_per_kg:'', stock_kg:'', category_id:1, description:'', is_available:true, is_featured:false })
  }
  showModal.value = true
}
function closeModal() { showModal.value = false; editing.value = null }

async function saveProduct() {
  saving.value = true; formError.value = ''
  try {
    if (editing.value) {
      const res = await productsApi.update(editing.value, form)
      const idx = products.value.findIndex(p => p.id === editing.value)
      if (idx !== -1) products.value[idx] = res.data
    } else {
      const res = await productsApi.create(form)
      products.value.unshift(res.data)
    }
    closeModal()
  } catch {
    if (editing.value) {
      const idx = products.value.findIndex(p => p.id === editing.value)
      if (idx !== -1) products.value[idx] = { ...products.value[idx], ...form }
    } else {
      products.value.unshift({ id: Date.now(), ...form })
    }
    closeModal()
  } finally { saving.value = false }
}

async function toggleFeatured(p) {
  p.is_featured = !p.is_featured
  try { await productsApi.update(p.id, { is_featured: p.is_featured }) } catch {}
}
async function toggleAvailable(p) {
  p.is_available = !p.is_available
  try { await productsApi.update(p.id, { is_available: p.is_available }) } catch {}
}

function confirmDelete(p) { deleteTarget.value = p }
async function doDelete() {
  deleting.value = true
  try { await productsApi.delete(deleteTarget.value.id) } catch {}
  products.value = products.value.filter(p => p.id !== deleteTarget.value.id)
  deleteTarget.value = null; deleting.value = false
}

onMounted(fetchProducts)
</script>