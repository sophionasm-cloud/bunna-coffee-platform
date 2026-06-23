<template>
  <div>
    <div class="bg-teff-weave py-14">
      <div class="container-main text-center">
        <p class="text-gold-400 font-semibold text-sm uppercase tracking-widest mb-2">Premium Selection</p>
        <h1 class="font-display text-4xl font-bold text-white mb-3">Ethiopian Coffee</h1>
        <p class="text-coffee-300 max-w-xl mx-auto">Browse our full range of single-origin Ethiopian coffees — from farm to your cup.</p>
      </div>
    </div>
    <div class="pattern-border"></div>

    <div class="section bg-ethiopian-weave">
      <div class="container-main">
        <!-- Filters -->
        <div class="bg-white rounded-sm shadow-sm border border-earth-100 p-4 mb-8 flex flex-wrap gap-3 items-end">
          <div class="flex-1 min-w-48">
            <label class="input-label text-xs">Search</label>
            <input v-model="filters.search" @input="debouncedFetch" type="text" class="input-field" placeholder="Yirgacheffe, Sidama..." />
          </div>
          <div class="min-w-36">
            <label class="input-label text-xs">Grade</label>
            <select v-model="filters.grade" @change="fetchProducts" class="select-field">
              <option value="">All Grades</option>
              <option v-for="g in grades" :key="g">{{ g }}</option>
            </select>
          </div>
          <div class="min-w-36">
            <label class="input-label text-xs">Process</label>
            <select v-model="filters.process" @change="fetchProducts" class="select-field">
              <option value="">All Processes</option>
              <option>Washed</option><option>Natural</option><option>Honey</option><option>Anaerobic Natural</option>
            </select>
          </div>
          <div class="min-w-36">
            <label class="input-label text-xs">Sort By</label>
            <select v-model="filters.sort" @change="fetchProducts" class="select-field">
              <option value="newest">Newest First</option>
              <option value="price_asc">Price: Low to High</option>
              <option value="price_desc">Price: High to Low</option>
            </select>
          </div>
          <button @click="resetFilters" class="btn-secondary text-sm px-4 py-2.5">Reset</button>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="n in 6" :key="n" class="card p-5 animate-pulse">
            <div class="h-44 bg-earth-100 rounded-sm mb-4"></div>
            <div class="h-4 bg-earth-100 rounded mb-2 w-3/4"></div>
            <div class="h-3 bg-earth-100 rounded mb-4 w-1/2"></div>
            <div class="h-10 bg-earth-100 rounded"></div>
          </div>
        </div>

        <!-- Error -->
        <div v-else-if="error" class="text-center py-16">
          <p class="text-4xl mb-3">⚠️</p>
          <p class="text-earth-600 mb-4">{{ error }}</p>
          <button @click="fetchProducts" class="btn-primary">Try Again</button>
        </div>

        <!-- Empty -->
        <div v-else-if="!products.length" class="text-center py-16">
          <p class="text-5xl mb-4">☕</p>
          <p class="font-display text-xl text-earth-700 mb-2">No coffees found</p>
          <button @click="resetFilters" class="btn-secondary mt-2">Clear Filters</button>
        </div>

        <!-- Grid -->
        <div v-else>
          <p class="text-sm text-earth-500 mb-5">Showing <strong>{{ products.length }}</strong> coffees</p>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="p in products" :key="p.id" class="card overflow-hidden group flex flex-col">
              <div class="h-48 bg-ethiopian-weave flex items-center justify-center relative overflow-hidden">
                <img v-if="p.image" :src="`/storage/${p.image}`" :alt="p.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                <span v-else class="text-6xl group-hover:scale-110 transition-transform duration-300">☕</span>
                <div class="absolute top-3 left-3 flex gap-1">
                  <span v-if="p.grade" class="badge-approved text-xs">{{ p.grade }}</span>
                  <span v-if="p.is_featured" class="badge-investor text-xs">⭐ Featured</span>
                </div>
                <div v-if="!p.stock_kg" class="absolute inset-0 bg-black/50 flex items-center justify-center">
                  <span class="text-white font-semibold bg-red-600 px-3 py-1 rounded-sm text-sm">Out of Stock</span>
                </div>
              </div>
              <div class="p-5 flex flex-col flex-1">
                <div class="flex justify-between items-start mb-1">
                  <h3 class="font-display text-lg font-semibold text-earth-900 leading-tight flex-1">{{ p.name }}</h3>
                  <span class="text-coffee-700 font-bold text-lg shrink-0 ml-3">${{ Number(p.price_per_kg).toFixed(2) }}<span class="text-xs text-earth-400">/kg</span></span>
                </div>
                <div class="flex items-center gap-2 text-xs text-earth-500 mb-2">
                  <span>📍 {{ p.origin }}</span>
                  <span v-if="p.process">· {{ p.process }}</span>
                </div>
                <p class="text-sm text-earth-600 leading-relaxed mb-3 flex-1 line-clamp-3">{{ p.description }}</p>
                <p class="text-xs mb-4">
                  <span v-if="p.stock_kg > 0" class="text-forest-600 font-medium">✓ {{ Number(p.stock_kg).toLocaleString() }} kg available</span>
                  <span v-else class="text-red-500 font-medium">✗ Out of stock</span>
                </p>
                <div class="flex gap-2 mt-auto">
                  <RouterLink :to="`/products/${p.id}`" class="btn-secondary text-sm px-3 py-2 flex-1 text-center">Details</RouterLink>
                  <button v-if="auth.isLoggedIn" @click="addToCart(p)" :disabled="!p.stock_kg"
                    class="btn-primary text-sm px-3 py-2 flex-1 disabled:opacity-40 disabled:cursor-not-allowed">
                    {{ cart[p.id] ? '✓ In Cart (' + cart[p.id] + ')' : '+ Cart' }}
                  </button>
                  <RouterLink v-else to="/register" class="btn-primary text-sm px-3 py-2 flex-1 text-center">Order</RouterLink>
                </div>
              </div>
            </div>
          </div>
          <div v-if="meta.last_page > 1" class="flex justify-center gap-2 mt-10">
            <button v-for="page in meta.last_page" :key="page" @click="goToPage(page)"
              :class="['w-10 h-10 rounded-sm text-sm font-medium transition-all border', page === meta.current_page ? 'bg-coffee-700 text-white border-coffee-700' : 'bg-white text-earth-600 border-earth-200 hover:border-coffee-400']">
              {{ page }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <Transition name="slide-up">
      <div v-if="toast" class="fixed bottom-6 right-6 bg-coffee-800 text-white px-5 py-3 rounded-sm shadow-warm-lg flex items-center gap-3 z-50">
        <span>✓</span><span class="text-sm font-medium">{{ toast }}</span>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { productsApi } from '@/api'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
const products = ref([])
const loading  = ref(true)
const error    = ref('')
const meta     = ref({})
const cart     = reactive({})
const toast    = ref('')
const filters  = reactive({ search: '', grade: '', process: '', sort: 'newest', page: 1 })
const grades   = ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5']

const DEMO = [
  { id:1, name:'Yirgacheffe Grade 1 — Washed',  grade:'Grade 1', price_per_kg:'8.50',  origin:'Yirgacheffe, SNNPR', process:'Washed',           description:"Ethiopia's most celebrated single-origin. Bright floral aroma with citrus and berry notes.", stock_kg:5000,  is_featured:true },
  { id:2, name:'Sidama Washed Grade 2',           grade:'Grade 2', price_per_kg:'7.20',  origin:'Sidama, Ethiopia',   process:'Washed',           description:'Clean, balanced, with stone fruit sweetness and light acidity. Perfect for specialty roasters.', stock_kg:8000,  is_featured:true },
  { id:3, name:'Harrar Natural Grade 4',          grade:'Grade 4', price_per_kg:'6.80',  origin:'Harrar, Oromia',     process:'Natural',          description:"Bold and wine-like with deep fruity notes. Ethiopia's oldest growing region.",   stock_kg:12000, is_featured:false },
  { id:4, name:'Jimma Grade 3 — Natural',         grade:'Grade 3', price_per_kg:'5.90',  origin:'Jimma, Oromia',      process:'Natural',          description:'Rich, earthy flavor with chocolate undertones. Excellent for espresso blends.',    stock_kg:20000, is_featured:false },
  { id:5, name:'Guji Anaerobic Natural',           grade:'Grade 1', price_per_kg:'12.00', origin:'Guji, Oromia',       process:'Anaerobic Natural',description:'Experimental processing from the Guji highlands. Tropical fruit forward.',          stock_kg:2000,  is_featured:true },
  { id:6, name:'Kaffa Forest Coffee',              grade:'Grade 2', price_per_kg:'9.50',  origin:'Kaffa, SNNPR',       process:'Natural',          description:"Wild-harvested from the birthplace of coffee itself. Earthy, spicy, deeply complex.", stock_kg:1500,  is_featured:false },
]

let timer = null
function debouncedFetch() { clearTimeout(timer); timer = setTimeout(fetchProducts, 400) }

async function fetchProducts() {
  loading.value = true; error.value = ''
  try {
    const res = await productsApi.list({ search:filters.search||undefined, grade:filters.grade||undefined, sort:filters.sort, page:filters.page })
    products.value = res.data.data || res.data
    meta.value     = res.data.meta || {}
  } catch {
    let list = [...DEMO]
    if (filters.search)  list = list.filter(p => p.name.toLowerCase().includes(filters.search.toLowerCase()) || p.origin.toLowerCase().includes(filters.search.toLowerCase()))
    if (filters.grade)   list = list.filter(p => p.grade === filters.grade)
    if (filters.process) list = list.filter(p => p.process === filters.process)
    if (filters.sort === 'price_asc')  list.sort((a,b) => a.price_per_kg - b.price_per_kg)
    if (filters.sort === 'price_desc') list.sort((a,b) => b.price_per_kg - a.price_per_kg)
    products.value = list
    meta.value = {}
  } finally { loading.value = false }
}

function goToPage(page) { filters.page = page; fetchProducts() }
function resetFilters() { Object.assign(filters, { search:'', grade:'', process:'', sort:'newest', page:1 }); fetchProducts() }
function addToCart(p) {
  cart[p.id] = (cart[p.id] || 0) + 1
  toast.value = p.name.split(' ').slice(0,2).join(' ') + ' added to cart!'
  setTimeout(() => { toast.value = '' }, 2500)
}

onMounted(fetchProducts)
</script>

<style scoped>
.line-clamp-3 { display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }
.slide-up-enter-active,.slide-up-leave-active { transition:all 0.3s ease; }
.slide-up-enter-from,.slide-up-leave-to { opacity:0; transform:translateY(20px); }
</style>