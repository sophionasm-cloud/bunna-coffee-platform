import api from './client'

// ─── AUTH ───────────────────────────────────────────────
export const authApi = {
  register:  (data) => api.post('/register', data),
  login:     (data) => api.post('/login', data),
  logout:    ()     => api.post('/logout'),
  me:        ()     => api.get('/me'),
  updateProfile: (data) => api.put('/profile', data),
}

// ─── PRODUCTS ───────────────────────────────────────────
export const productsApi = {
  list:   (params) => api.get('/products', { params }),
  show:   (id)     => api.get(`/products/${id}`),
  create: (data)   => api.post('/products', data),
  update: (id, data) => api.put(`/products/${id}`, data),
  delete: (id)     => api.delete(`/products/${id}`),
}

// ─── ORDERS ─────────────────────────────────────────────
export const ordersApi = {
  list:         (params) => api.get('/orders', { params }),
  show:         (id)     => api.get(`/orders/${id}`),
  create:       (data)   => api.post('/orders', data),
  updateStatus: (id, status) => api.patch(`/orders/${id}/status`, { status }),
}

// ─── FARMER LISTINGS ────────────────────────────────────
export const listingsApi = {
  list:   (params) => api.get('/listings', { params }),
  show:   (id)     => api.get(`/listings/${id}`),
  create: (data)   => api.post('/listings', data, {
    headers: { 'Content-Type': 'multipart/form-data' }
  }),
  update: (id, d)  => api.post(`/listings/${id}?_method=PUT`, d, {
    headers: { 'Content-Type': 'multipart/form-data' }
  }),
  delete: (id)     => api.delete(`/listings/${id}`),
}

// ─── CONTACT ────────────────────────────────────────────
export const contactApi = {
  submit: (data) => api.post('/contact', data),
}

// ─── ADMIN ──────────────────────────────────────────────
export const adminApi = {
  stats:       ()         => api.get('/admin/stats'),
  users:       (params)   => api.get('/admin/users', { params }),
  updateRole:  (id, role) => api.patch(`/admin/users/${id}/role`, { role }),
  deleteUser:  (id)       => api.delete(`/admin/users/${id}`),
  allOrders:   (params)   => api.get('/admin/orders', { params }),
  allProducts: (params)   => api.get('/admin/products', { params }),
}

// ─── NOTIFICATIONS ──────────────────────────────────────
export const notificationsApi = {
  list:    () => api.get('/notifications'),
  markRead: (id) => api.patch(`/notifications/${id}/read`),
  markAllRead: () => api.patch('/notifications/read-all'),
}