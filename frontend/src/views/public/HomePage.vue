<template>
  <div class="bg-ethiopian-weave">
    <div class="container-main py-16 md:py-24">
      <div class="max-w-3xl mx-auto text-center">
        <h1 class="text-5xl font-display font-bold text-coffee-800 mb-6">
          {{ $t('welcome') }}
        </h1>
        <p class="text-xl text-earth-600 mb-8">
          {{ $t('subtitle') }}
        </p>
        <div class="flex flex-wrap justify-center gap-4">
          <router-link to="/products" class="btn-primary">
            {{ $t('products') }}
          </router-link>
          <router-link to="/register" class="btn-secondary">
            {{ $t('getStarted') }}
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup></script>