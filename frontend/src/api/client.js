import axios from 'axios'

const API_URL = import.meta.env.VITE_API_URL || 'https://bunna-coffee-platform.onrender.com/api'

const api = axios.create({
  baseURL: API_URL,
  withCredentials: true,
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  }
})

// Request interceptor – attach token and fix multipart headers
api.interceptors.request.use(config => {
  const token = localStorage.getItem('bunna_token')
  if (token) config.headers.Authorization = `Bearer ${token}`
  
  // ✅ Let browser set Content-Type automatically for FormData
  if (config.data instanceof FormData) {
    delete config.headers['Content-Type']
  }

  return config
})

// Response interceptor – only logout on auth routes
api.interceptors.response.use(
  res => res,
  err => {
    if (err.response?.status === 401) {
      const url = err.config.url
      const isAuthRoute = url.includes('/me') || url.includes('/logout')
      if (isAuthRoute) {
        localStorage.removeItem('bunna_token')
        window.location.href = '/login'
      }
    }
    return Promise.reject(err)
  }
)

export default api