import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api',
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
      localStorage.removeItem('bunna_token')
      window.location.href = '/login'
    }
    return Promise.reject(err)
  }
)

export default api