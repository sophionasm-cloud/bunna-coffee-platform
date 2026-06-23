import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

// Public Layouts & Views
const PublicLayout   = () => import('@/components/layout/PublicLayout.vue')
const DashboardLayout = () => import('@/components/layout/DashboardLayout.vue')

// Public pages
const HomePage       = () => import('@/views/public/HomePage.vue')
const ProductsPage   = () => import('@/views/public/ProductsPage.vue')
const ProductDetail  = () => import('@/views/public/ProductDetail.vue')
const ServicesPage   = () => import('@/views/public/ServicesPage.vue')
const AboutPage      = () => import('@/views/public/AboutPage.vue')
const ContactPage    = () => import('@/views/public/ContactPage.vue')
const MarketPage     = () => import('@/views/public/MarketPage.vue')

// Auth
const LoginPage      = () => import('@/views/auth/LoginPage.vue')
const RegisterPage   = () => import('@/views/auth/RegisterPage.vue')

// Dashboard (shared)
const DashboardHome  = () => import('@/views/dashboard/DashboardHome.vue')
const ProfilePage    = () => import('@/views/dashboard/ProfilePage.vue')
const OrdersPage     = () => import('@/views/dashboard/OrdersPage.vue')
const OrderDetail    = () => import('@/views/dashboard/OrderDetail.vue')
const NotificationsPage = () => import('@/views/dashboard/NotificationsPage.vue')

// Admin
const AdminUsers     = () => import('@/views/admin/AdminUsers.vue')
const AdminProducts  = () => import('@/views/admin/AdminProducts.vue')
const AdminOrders    = () => import('@/views/admin/AdminOrders.vue')
const AdminAnalytics = () => import('@/views/admin/AdminAnalytics.vue')

// Farmer
const FarmerListings = () => import('@/views/farmer/FarmerListings.vue')
const FarmerAddListing = () => import('@/views/farmer/FarmerAddListing.vue')

// Trader
const TraderMarket   = () => import('@/views/trader/TraderMarket.vue')
const TraderOrders   = () => import('@/views/trader/TraderOrders.vue')

// Investor
const InvestorOpportunities = () => import('@/views/investor/InvestorOpportunities.vue')
const InvestorPortfolio     = () => import('@/views/investor/InvestorPortfolio.vue')

const routes = [
  {
    path: '/',
    component: PublicLayout,
    children: [
      { path: '',          name: 'home',       component: HomePage },
      { path: 'products',  name: 'products',   component: ProductsPage },
      { path: 'products/:id', name: 'product-detail', component: ProductDetail },
      { path: 'services',  name: 'services',   component: ServicesPage },
      { path: 'about',     name: 'about',      component: AboutPage },
      { path: 'contact',   name: 'contact',    component: ContactPage },
      { path: 'market',    name: 'market',     component: MarketPage },
      { path: 'login',     name: 'login',      component: LoginPage,    meta: { guestOnly: true } },
      { path: 'register',  name: 'register',   component: RegisterPage, meta: { guestOnly: true } },
    ]
  },
  {
    path: '/dashboard',
    component: DashboardLayout,
    meta: { requiresAuth: true },
    children: [
      { path: '',              name: 'dashboard',      component: DashboardHome },
      { path: 'profile',       name: 'profile',        component: ProfilePage },
      { path: 'orders',        name: 'orders',         component: OrdersPage },
      { path: 'orders/:id',    name: 'order-detail',   component: OrderDetail },
      { path: 'notifications', name: 'notifications',  component: NotificationsPage },

      // Farmer routes
      { path: 'listings',      name: 'farmer-listings', component: FarmerListings,   meta: { roles: ['farmer', 'admin'] } },
      { path: 'listings/add',  name: 'farmer-add',      component: FarmerAddListing, meta: { roles: ['farmer', 'admin'] } },

      // Trader routes
      { path: 'market',        name: 'trader-market',  component: TraderMarket,   meta: { roles: ['trader', 'admin'] } },
      { path: 'trade-orders',  name: 'trader-orders',  component: TraderOrders,   meta: { roles: ['trader', 'admin'] } },

      // Investor routes
      { path: 'opportunities', name: 'investor-opportunities', component: InvestorOpportunities, meta: { roles: ['investor', 'admin'] } },
      { path: 'portfolio',     name: 'investor-portfolio',     component: InvestorPortfolio,     meta: { roles: ['investor', 'admin'] } },

      // Admin routes
      { path: 'admin/users',     name: 'admin-users',     component: AdminUsers,     meta: { roles: ['admin'] } },
      { path: 'admin/products',  name: 'admin-products',  component: AdminProducts,  meta: { roles: ['admin'] } },
      { path: 'admin/orders',    name: 'admin-orders',    component: AdminOrders,    meta: { roles: ['admin'] } },
      { path: 'admin/analytics', name: 'admin-analytics', component: AdminAnalytics, meta: { roles: ['admin'] } },
    ]
  },
  { path: '/:pathMatch(.*)*', redirect: '/' }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior: () => ({ top: 0 })
})

router.beforeEach(async (to, from, next) => {
  const auth = useAuthStore()

  if (to.meta.requiresAuth && !auth.isLoggedIn) {
    return next({ name: 'login', query: { redirect: to.fullPath } })
  }

  if (to.meta.guestOnly && auth.isLoggedIn) {
    return next({ name: 'dashboard' })
  }

  if (to.meta.roles && !to.meta.roles.includes(auth.user?.role)) {
    return next({ name: 'dashboard' })
  }

  next()
})

export default router