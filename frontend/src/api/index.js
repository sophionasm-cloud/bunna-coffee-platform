import api from './client'

export const authApi = {
  register:      (data) => api.post('/register', data),
  login:         (data) => api.post('/login', data),
  logout:        ()     => api.post('/logout'),
  me:            ()     => api.get('/me'),
  updateProfile: (data) => api.put('/profile', data),
}

export const productsApi = {
  list:   (params)     => api.get('/products', { params }),
  show:   (id)         => api.get(`/products/${id}`),
  create: (data)       => api.post('/admin/products', data),
  update: (id, data)   => api.put(`/admin/products/${id}`, data),
  delete: (id)         => api.delete(`/admin/products/${id}`),
}

export const ordersApi = {
  list:         (params)        => api.get('/orders', { params }),
  show:         (id)            => api.get(`/orders/${id}`),
  create:       (data)          => api.post('/orders', data),
  updateStatus: (id, status)    => api.patch(`/orders/${id}/status`, { status }),
}

export const listingsApi = {
  list:   (params) => api.get('/listings', { params }),
  myList: ()       => api.get('/my-listings'), // ✅ NEW
  show:   (id)     => api.get(`/listings/${id}`),
  create: (data)   => api.post('/listings', data),
  update: (id, d)  => api.post(`/listings/${id}?_method=PUT`, d),
  delete: (id)     => api.delete(`/listings/${id}`),
}

export const contactApi = {
  submit: (data) => api.post('/contact', data),
}

export const adminApi = {
  stats:       ()           => api.get('/admin/stats'),
  users:       (params)     => api.get('/admin/users', { params }),
  updateRole:  (id, role)   => api.patch(`/admin/users/${id}/role`, { role }),
  deleteUser:  (id)         => api.delete(`/admin/users/${id}`),
  toggleUser:  (id)         => api.patch(`/admin/users/${id}/toggle`),
  allOrders:   (params)     => api.get('/admin/orders', { params }),
  allProducts: (params)     => api.get('/admin/products', { params }),
  allListings: (params)     => api.get('/listings', { params }),
  approveL:    (id)         => api.patch(`/admin/listings/${id}/approve`),
  rejectL:     (id)         => api.patch(`/admin/listings/${id}/reject`),
}

export const notificationsApi = {
  list:        () => api.get('/notifications'),
  markRead:    (id) => api.patch(`/notifications/${id}/read`),
  markAllRead: () => api.patch('/notifications/read-all'),
}