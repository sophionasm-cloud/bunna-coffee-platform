<template>
<div class="min-h-screen flex flex-col bg-gray-600">
    <!-- Ethiopian Flag Stripe -->
    <div class="pattern-border"></div>

    <!-- Navbar -->
    <nav class="bg-cream-200 text-coffee-700 sticky top-0 z-50 shadow-md border-b border-coffee-200">
      <div class="container-main">
        <div class="flex items-center justify-between h-16">
          <!-- Logo -->
          <RouterLink to="/" class="flex items-center gap-3 group">
            <div class="w-9 h-9 rounded-full bg-gold-500 flex items-center justify-center shadow-warm">
              <span class="text-coffee-950 font-display font-bold text-sm">ቡ</span>
            </div>
            <div>
              <span class="font-display font-bold text-xl text-white tracking-wide">BUNNA</span>
              <span class="hidden sm:block text-xs text-coffee-300 font-body -mt-0.5">ቡና · Coffee Trade Platform</span>
            </div>
          </RouterLink>

          <!-- Desktop Nav -->
          <div class="hidden lg:flex items-center gap-1">
            <RouterLink v-for="link in navLinks" :key="link.to"
              :to="link.to"
              class="px-4 py-2 text-sm font-medium text-coffee-200 hover:text-white hover:bg-coffee-800 rounded-sm transition-all duration-200"
              :class="{ 'text-white bg-coffee-800': $route.path === link.to }">
              {{ t(link.label) }}
            </RouterLink>
          </div>

          <!-- Right side -->
          <div class="flex items-center gap-3">
            <!-- Language toggle -->
            <button @click="toggleLocale"
              class="px-3 py-1.5 text-xs font-semibold border border-coffee-600 text-coffee-200 hover:bg-coffee-800 rounded-sm transition-all">
              {{ locale === 'en' ? 'አማ' : 'EN' }}
            </button>

            <!-- Auth buttons -->
            <template v-if="!auth.isLoggedIn">
              <RouterLink to="/login"
                class="px-4 py-2 text-sm font-semibold text-coffee-200 hover:text-white transition-colors">
                {{ t('nav.login') }}
              </RouterLink>
              <RouterLink to="/register" class="btn-gold text-sm px-4 py-2">
                {{ t('nav.register') }}
              </RouterLink>
            </template>
            <template v-else>
              <RouterLink to="/dashboard"
                class="px-4 py-2 text-sm font-semibold text-coffee-200 hover:text-white transition-colors">
                {{ t('nav.dashboard') }}
              </RouterLink>
              <button @click="auth.logout()"
                class="px-4 py-2 text-sm font-semibold text-coffee-300 hover:text-white transition-colors">
                {{ t('nav.logout') }}
              </button>
            </template>

            <!-- Mobile menu toggle -->
            <button @click="mobileOpen = !mobileOpen"
              class="lg:hidden p-2 text-coffee-200 hover:text-white hover:bg-coffee-800 rounded-sm transition-all">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path v-if="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"/>
                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Mobile menu -->
        <Transition name="slide-down">
          <div v-if="mobileOpen" class="lg:hidden border-t border-coffee-800 py-3 space-y-1">
            <RouterLink v-for="link in navLinks" :key="link.to"
              :to="link.to"
              @click="mobileOpen = false"
              class="block px-4 py-2.5 text-sm text-coffee-200 hover:text-white hover:bg-coffee-800 rounded-sm transition-all">
              {{ t(link.label) }}
            </RouterLink>
          </div>
        </Transition>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1">
      <RouterView />
    </main>

    <!-- Footer -->
    <footer class="bg-teff-weave text-coffee-200">
      <!-- Top Footer -->
      <div class="container-main py-14">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <!-- Brand -->
          <div class="lg:col-span-1">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-9 h-9 rounded-full bg-gold-500 flex items-center justify-center">
                <span class="text-coffee-950 font-display font-bold text-sm">ቡ</span>
              </div>
              <span class="font-display font-bold text-xl text-white">BUNNA</span>
            </div>
            <p class="text-sm text-coffee-400 leading-relaxed">
              Ethiopia's premier digital platform connecting coffee farmers, traders, customers and international investors.
            </p>
            <p class="text-sm text-coffee-500 mt-2 font-amharic">
              ቡና · Coffee · قهوة
            </p>
          </div>

          <!-- Platform -->
          <div>
            <h4 class="text-white font-semibold text-sm mb-4 uppercase tracking-wider">Platform</h4>
            <ul class="space-y-2">
              <li v-for="l in footerPlatform" :key="l.to">
                <RouterLink :to="l.to" class="text-sm text-coffee-400 hover:text-gold-400 transition-colors">{{ l.label }}</RouterLink>
              </li>
            </ul>
          </div>

          <!-- Join As -->
          <div>
            <h4 class="text-white font-semibold text-sm mb-4 uppercase tracking-wider">Join As</h4>
            <ul class="space-y-2">
              <li class="text-sm text-coffee-400">🌱 Coffee Farmer</li>
              <li class="text-sm text-coffee-400">🤝 Trader / Broker</li>
              <li class="text-sm text-coffee-400">💼 International Investor</li>
              <li class="text-sm text-coffee-400">☕ Coffee Buyer</li>
            </ul>
          </div>

          <!-- Contact -->
          <div>
            <h4 class="text-white font-semibold text-sm mb-4 uppercase tracking-wider">Contact</h4>
            <ul class="space-y-2">
              <li class="text-sm text-coffee-400">📍 Addis Ababa, Ethiopia</li>
              <li class="text-sm text-coffee-400">📧 hello@bunna.et</li>
              <li class="text-sm text-coffee-400">📞 +251 911 000 000</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Bottom bar -->
      <div class="border-t border-coffee-900">
        <div class="container-main py-4 flex flex-col sm:flex-row justify-between items-center gap-2">
          <p class="text-xs text-coffee-500">© {{ new Date().getFullYear() }} BUNNA Coffee Platform. Made in Ethiopia 🇪🇹</p>
          <p class="text-xs text-coffee-600"></p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { RouterLink, RouterView, useRoute } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useAuthStore } from '@/stores/auth'

const { t, locale } = useI18n()
const auth  = useAuthStore()
const $route = useRoute()
const mobileOpen = ref(false)

const navLinks = [
  { to: '/',         label: 'nav.home' },
  { to: '/products', label: 'nav.products' },
  { to: '/services', label: 'nav.services' },
  { to: '/market',   label: 'nav.market' },
  { to: '/about',    label: 'nav.about' },
  { to: '/contact',  label: 'nav.contact' },
]

const footerPlatform = [
  { to: '/products', label: 'Browse Coffee' },
  { to: '/services', label: 'Our Services' },
  { to: '/market',   label: 'Market Prices' },
  { to: '/about',    label: 'About Us' },
  { to: '/contact',  label: 'Contact' },
]

function toggleLocale() {
  locale.value = locale.value === 'en' ? 'am' : 'en'
  localStorage.setItem('bunna_locale', locale.value)
}
</script>

<style scoped>
.slide-down-enter-active,
.slide-down-leave-active { transition: all 0.25s ease; }
.slide-down-enter-from,
.slide-down-leave-to    { opacity: 0; transform: translateY(-8px); }
</style>