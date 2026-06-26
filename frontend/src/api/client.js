import axios from 'axios'

// Use environment variable if available, otherwise use production URL
const API_URL = import.meta.env.VITE_API_URL || 'https://bunna-coffee-platform.onrender.com/api'

const api = axios.create({
  baseURL: API_URL,
  withCredentials: true,
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  }
})

// Request interceptor – attach token
api.interceptors.request.use(config => {
  const token = localStorage.getItem('bunna_token')
  if (token) config.headers.Authorization = `Bearer ${token}`
  return config
})

// Response interceptor – handle 401
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