<template>
  <div>
    <h1 class="text-2xl font-display font-bold text-coffee-800 mb-2">Add New Listing</h1>
    <p class="text-earth-500 mb-8">List your coffee for buyers to discover</p>

    <div class="card p-6 max-w-2xl">
      <form @submit.prevent="submitListing" class="space-y-4" enctype="multipart/form-data">
        <div>
          <label class="input-label">Title *</label>
          <input v-model="form.title" type="text" class="input-field" required placeholder="e.g., Organic Yirgacheffe" />
        </div>

        <div>
          <label class="input-label">Origin *</label>
          <input v-model="form.origin" type="text" class="input-field" required placeholder="e.g., Yirgacheffe, SNNPR" />
        </div>

        <div>
          <label class="input-label">Description</label>
          <textarea v-model="form.description" class="input-field" rows="4" placeholder="Describe your coffee..."></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="input-label">Quantity (kg) *</label>
            <input v-model="form.quantity_kg" type="number" class="input-field" required min="1" placeholder="100" />
          </div>
          <div>
            <label class="input-label">Price per kg ($) *</label>
            <input v-model="form.asking_price_per_kg" type="number" class="input-field" required min="0" step="0.01" placeholder="8.50" />
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="input-label">Grade</label>
            <select v-model="form.grade" class="select-field">
              <option value="">Select Grade</option>
              <option value="Grade 1">Grade 1</option>
              <option value="Grade 2">Grade 2</option>
              <option value="Grade 3">Grade 3</option>
              <option value="Grade 4">Grade 4</option>
            </select>
          </div>
          <div>
            <label class="input-label">Process</label>
            <select v-model="form.process" class="select-field">
              <option value="">Select Process</option>
              <option value="Washed">Washed</option>
              <option value="Natural">Natural</option>
              <option value="Honey">Honey</option>
              <option value="Anaerobic Natural">Anaerobic Natural</option>
            </select>
          </div>
        </div>

        <!-- Image Upload -->
        <div>
          <label class="input-label">Product Image</label>
          <div class="mt-2">
            <!-- Image Preview -->
            <div v-if="imagePreview" class="mb-3">
              <img :src="imagePreview" class="h-40 w-40 object-cover rounded-sm border border-earth-200" />
              <button @click="removeImage" type="button" class="text-xs text-red-600 mt-1 hover:text-red-800">Remove Image</button>
            </div>
            
            <!-- Upload Button -->
            <div v-else class="flex items-center justify-center w-full">
              <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-earth-300 rounded-sm cursor-pointer hover:border-coffee-500 transition-all">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                  <svg class="w-8 h-8 text-earth-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  <p class="text-sm text-earth-500">Click to upload photo</p>
                  <p class="text-xs text-earth-400">PNG, JPG, JPEG (max 2MB)</p>
                </div>
                <input ref="fileInput" type="file" class="hidden" accept="image/*" @change="handleFileUpload" />
              </label>
            </div>
          </div>
        </div>

        <div class="flex gap-4 pt-4">
          <button type="submit" class="btn-primary px-8" :disabled="submitting">
            {{ submitting ? 'Submitting...' : 'Create Listing' }}
          </button>
          <RouterLink to="/dashboard/listings" class="btn-secondary px-8">
            Cancel
          </RouterLink>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { listingsApi } from '@/api'

const router = useRouter()
const submitting = ref(false)
const fileInput = ref(null)
const imagePreview = ref(null)
const selectedFile = ref(null)

const form = ref({
  title: '',
  origin: '',
  description: '',
  quantity_kg: '',
  asking_price_per_kg: '',
  grade: '',
  process: ''
})

const handleFileUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    selectedFile.value = file
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const removeImage = () => {
  selectedFile.value = null
  imagePreview.value = null
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

const submitListing = async () => {
  submitting.value = true
  try {
    const formData = new FormData()
    formData.append('title', form.value.title)
    formData.append('origin', form.value.origin)
    formData.append('description', form.value.description || '')
    formData.append('quantity_kg', form.value.quantity_kg)
    formData.append('asking_price_per_kg', form.value.asking_price_per_kg)
    formData.append('grade', form.value.grade || '')
    formData.append('process', form.value.process || '')
    
    if (selectedFile.value) {
      formData.append('image', selectedFile.value)
    }

    await listingsApi.create(formData)
    alert('✅ Listing created successfully! Waiting for admin approval.')
    router.push('/dashboard/listings')
  } catch (error) {
    console.error('Error creating listing:', error)
    const message = error.response?.data?.message || 'Failed to create listing. Please try again.'
    alert('❌ ' + message)
  } finally {
    submitting.value = false
  }
}
</script>