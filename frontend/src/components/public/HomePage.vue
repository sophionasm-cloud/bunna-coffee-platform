<template>
  <div>
    <!-- HERO -->
    <section class="relative min-h-screen flex items-center overflow-hidden bg-teff-weave">
      <!-- Background overlay art -->
      <div class="absolute inset-0 bg-coffee-gradient opacity-90"></div>
      <!-- Decorative coffee bean shapes -->
      <div class="absolute top-20 right-10 w-64 h-64 rounded-full border border-gold-500 opacity-10"></div>
      <div class="absolute bottom-20 left-10 w-48 h-48 rounded-full border border-gold-400 opacity-8"></div>

      <div class="relative container-main py-24 flex flex-col lg:flex-row items-center gap-16">
        <!-- Text -->
        <div class="flex-1 text-center lg:text-left animate-fade-up">
          <!-- Ethiopian flag accent -->
          <div class="flex justify-center lg:justify-start gap-1 mb-6">
            <span class="block w-8 h-1 bg-ethiopia-green rounded-full"></span>
            <span class="block w-8 h-1 bg-ethiopia-yellow rounded-full"></span>
            <span class="block w-8 h-1 bg-ethiopia-red rounded-full"></span>
          </div>

          <p class="text-gold-400 font-body font-semibold tracking-widest text-sm uppercase mb-3">
            ቡና — Ethiopia's Coffee Platform
          </p>
          <h1 class="font-display font-bold text-5xl lg:text-7xl text-white leading-tight mb-4">
            {{ t('hero.title') }}
          </h1>
          <p class="font-amharic text-2xl text-coffee-300 mb-6">
            {{ t('hero.subtitle') }}
          </p>
          <p class="text-coffee-200 text-lg leading-relaxed max-w-xl mx-auto lg:mx-0 mb-10">
            {{ t('hero.description') }}
          </p>

          <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
            <RouterLink to="/products" class="btn-gold text-base px-8 py-4">
              {{ t('hero.cta_primary') }}
            </RouterLink>
            <RouterLink to="/register" class="btn-outline-white text-base px-8 py-4">
              {{ t('hero.cta_secondary') }}
            </RouterLink>
          </div>

          <!-- Stats row -->
          <div class="mt-14 flex flex-wrap justify-center lg:justify-start gap-8">
            <div v-for="s in stats" :key="s.label" class="text-center">
              <p class="text-3xl font-display font-bold text-gold-400">{{ s.value }}</p>
              <p class="text-sm text-coffee-400 mt-1">{{ s.label }}</p>
            </div>
          </div>
        </div>

        <!-- Coffee illustration card -->
        <div class="flex-1 flex justify-center lg:justify-end">
          <div class="relative w-72 h-72 lg:w-96 lg:h-96">
            <!-- Central circle -->
            <div class="absolute inset-0 rounded-full bg-coffee-800 border-2 border-coffee-600 flex items-center justify-center shadow-warm-lg">
              <div class="text-center">
                <div class="text-6xl mb-2">☕</div>
                <p class="font-amharic text-3xl text-gold-400 font-bold">ቡና</p>
                <p class="text-coffee-300 text-sm mt-1">Ethiopian Coffee</p>
              </div>
            </div>
            <!-- Orbiting role bubbles -->
            <div v-for="(role, i) in roles" :key="role.label"
              :style="{ transform: `rotate(${i * 90}deg) translateY(-155px) rotate(-${i * 90}deg)` }"
              class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
              <div class="w-20 h-20 rounded-full bg-coffee-900 border border-coffee-600 flex flex-col items-center justify-center shadow-warm hover:border-gold-500 transition-all cursor-pointer">
                <span class="text-2xl">{{ role.icon }}</span>
                <p class="text-xs text-coffee-300 mt-0.5 font-medium">{{ role.label }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Scroll indicator -->
      <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-coffee-400 animate-bounce">
        <span class="text-xs tracking-widest">SCROLL</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </div>
    </section>

    <!-- WHO WE SERVE -->
    <section class="section bg-ethiopian-weave">
      <div class="container-main">
        <div class="text-center mb-14">
          <p class="text-coffee-600 font-semibold text-sm uppercase tracking-widest mb-3">Built for Everyone</p>
          <h2 class="section-title mb-4">One Platform, Four Pillars</h2>
          <div class="pattern-divider max-w-xs mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div v-for="role in roleCards" :key="role.title"
            class="card p-6 text-center hover:-translate-y-1 transition-transform duration-300">
            <div class="w-14 h-14 rounded-full mx-auto mb-4 flex items-center justify-center"
              :class="role.bg">
              <span class="text-2xl">{{ role.icon }}</span>
            </div>
            <h3 class="font-display text-xl font-semibold text-earth-900 mb-2">{{ role.title }}</h3>
            <p class="text-sm text-earth-600 leading-relaxed">{{ role.desc }}</p>
            <RouterLink to="/register"
              class="inline-block mt-4 text-sm text-coffee-700 font-semibold hover:text-coffee-900 transition-colors">
              Join as {{ role.title }} →
            </RouterLink>
          </div>
        </div>
      </div>
    </section>

    <!-- FEATURED COFFEES -->
    <section class="section bg-white">
      <div class="container-main">
        <div class="flex items-end justify-between mb-10">
          <div>
            <p class="text-coffee-600 font-semibold text-sm uppercase tracking-widest mb-2">Premium Selection</p>
            <h2 class="section-title">{{ t('products.title') }}</h2>
          </div>
          <RouterLink to="/products" class="btn-secondary text-sm px-5 py-2.5">
            View All
          </RouterLink>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="product in featuredProducts" :key="product.id"
            class="card overflow-hidden group">
            <!-- Image placeholder -->
            <div class="h-48 bg-ethiopian-weave flex items-center justify-center relative overflow-hidden">
              <div class="text-6xl group-hover:scale-110 transition-transform duration-300">☕</div>
              <div class="absolute top-3 left-3">
                <span class="badge-approved">{{ product.grade }}</span>
              </div>
            </div>
            <div class="p-5">
              <div class="flex items-start justify-between mb-2">
                <h3 class="font-display text-lg font-semibold text-earth-900">{{ product.name }}</h3>
                <span class="text-coffee-700 font-bold text-lg">${{ product.price }}<span class="text-xs text-earth-400">/kg</span></span>
              </div>
              <p class="text-sm text-earth-500 mb-1">📍 {{ product.origin }}</p>
              <p class="text-sm text-earth-600 leading-relaxed mb-4">{{ product.desc }}</p>
              <div class="flex gap-2">
                <RouterLink :to="`/products/${product.id}`" class="btn-primary text-sm px-4 py-2 flex-1 text-center">
                  {{ t('products.view_details') }}
                </RouterLink>
                <button class="btn-secondary text-sm px-4 py-2">{{ t('products.request_quote') }}</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="section bg-teff-weave">
      <div class="container-main">
        <div class="text-center mb-14">
          <p class="text-gold-400 font-semibold text-sm uppercase tracking-widest mb-3">Simple Process</p>
          <h2 class="section-title text-white mb-4">How BUNNA Works</h2>
          <div class="pattern-divider max-w-xs mx-auto opacity-30"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div v-for="(step, i) in steps" :key="i" class="text-center">
            <div class="w-16 h-16 rounded-full bg-gold-500 flex items-center justify-center mx-auto mb-5 shadow-warm-lg">
              <span class="font-display font-bold text-coffee-950 text-2xl">{{ i + 1 }}</span>
            </div>
            <h3 class="font-display text-xl text-white font-semibold mb-2">{{ step.title }}</h3>
            <p class="text-coffee-300 text-sm leading-relaxed">{{ step.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA BANNER -->
    <section class="py-20 bg-ethiopian-weave">
      <div class="container-main text-center">
        <div class="pattern-border mb-10 max-w-xs mx-auto"></div>
        <h2 class="section-title mb-4">Ready to Trade Ethiopian Coffee?</h2>
        <p class="section-subtitle mb-8 max-w-xl mx-auto">
          Join thousands of farmers, traders, customers and investors already on the platform.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <RouterLink to="/register" class="btn-primary px-10 py-4 text-base">
            Create Free Account
          </RouterLink>
          <RouterLink to="/contact" class="btn-secondary px-10 py-4 text-base">
            Talk to Us
          </RouterLink>
        </div>
        <p class="text-earth-400 text-sm mt-6 font-amharic">
          ቡናው ከእርሻ ይጀምራል · Coffee starts at the farm
        </p>
      </div>
    </section>
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const stats = [
  { value: '500+', label: 'Farmers' },
  { value: '50+',  label: 'Traders' },
  { value: '30+',  label: 'Countries' },
  { value: '1000+', label: 'Orders' },
]

const roles = [
  { icon: '🌱', label: 'Farmer' },
  { icon: '🤝', label: 'Trader' },
  { icon: '💼', label: 'Investor' },
  { icon: '☕', label: 'Customer' },
]

const roleCards = [
  {
    icon: '🌱', title: 'Farmers', bg: 'bg-forest-100',
    desc: 'List your coffee crops directly, set your own prices, and connect with buyers worldwide without middlemen.'
  },
  {
    icon: '🤝', title: 'Traders', bg: 'bg-coffee-100',
    desc: 'Browse available lots, negotiate bulk deals, manage your trade operations, and track all your orders.'
  },
  {
    icon: '💼', title: 'Investors', bg: 'bg-gold-100',
    desc: 'Discover investment opportunities in Ethiopian coffee farms, cooperatives, and export ventures.'
  },
  {
    icon: '☕', title: 'Customers', bg: 'bg-cream-200',
    desc: 'Order premium Ethiopian coffee directly. Browse grades, origins and roasts — delivered to your door.'
  },
]

const featuredProducts = [
  { id: 1, name: 'Yirgacheffe Grade 1', grade: 'Grade 1', price: '8.50', origin: 'Yirgacheffe, SNNPR', desc: 'Bright floral aroma with citrus and berry notes. Ethiopia\'s most celebrated single origin.' },
  { id: 2, name: 'Sidama Washed', grade: 'Grade 2', price: '7.20', origin: 'Sidama, Ethiopia', desc: 'Clean and balanced with stone fruit sweetness. Perfect for specialty roasters worldwide.' },
  { id: 3, name: 'Harrar Natural', grade: 'Grade 4', price: '6.80', origin: 'Harrar, Oromia', desc: 'Bold and wine-like with deep fruity notes. Ethiopia\'s oldest coffee growing region.' },
]

const steps = [
  { title: 'Register Your Role', desc: 'Sign up as a farmer, trader, investor, or customer and get your personalized dashboard.' },
  { title: 'List or Browse', desc: 'Farmers list their crops, traders browse lots, investors explore opportunities, customers shop.' },
  { title: 'Trade & Track', desc: 'Place orders, negotiate deals, manage shipments — all tracked in real-time on BUNNA.' },
]
</script>