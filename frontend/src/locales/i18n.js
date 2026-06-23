import { createI18n } from 'vue-i18n'

const messages = {
  en: {
    nav: {
      home: 'Home', products: 'Products', services: 'Services',
      about: 'About', contact: 'Contact', market: 'Market',
      login: 'Sign In', register: 'Register', dashboard: 'Dashboard',
      logout: 'Sign Out',
    },
    hero: {
      title: 'Ethiopian Coffee',
      subtitle: 'From Farm to the World',
      description: 'Connecting Ethiopian coffee farmers, traders, customers, and international investors on one trusted platform.',
      cta_primary: 'Explore Our Coffee',
      cta_secondary: 'Join the Platform',
    },
    roles: {
      farmer: 'Farmer', trader: 'Trader', investor: 'Investor',
      customer: 'Customer', admin: 'Admin',
    },
    products: {
      title: 'Our Coffee',
      description: 'Premium Ethiopian coffee grades for every taste and market',
      add_to_cart: 'Add to Cart',
      request_quote: 'Request Quote',
      view_details: 'View Details',
      grade: 'Grade',
      origin: 'Origin',
      weight: 'Weight (kg)',
      price: 'Price',
      in_stock: 'In Stock',
      out_of_stock: 'Out of Stock',
    },
    orders: {
      title: 'My Orders',
      place_order: 'Place Order',
      track_order: 'Track Order',
      status: {
        pending: 'Pending',
        approved: 'Approved',
        processing: 'Processing',
        shipped: 'Shipped',
        delivered: 'Delivered',
        cancelled: 'Cancelled',
      }
    },
    auth: {
      login_title: 'Welcome Back',
      login_sub: 'Sign in to your BUNNA account',
      register_title: 'Join BUNNA',
      register_sub: 'Create your account to get started',
      email: 'Email Address',
      password: 'Password',
      name: 'Full Name',
      role: 'I am a',
      confirm_password: 'Confirm Password',
      login_btn: 'Sign In',
      register_btn: 'Create Account',
      forgot: 'Forgot password?',
      no_account: "Don't have an account?",
      have_account: 'Already have an account?',
    },
    contact: {
      title: 'Get in Touch',
      name: 'Full Name',
      email: 'Email',
      subject: 'Subject',
      message: 'Message',
      send: 'Send Message',
      success: 'Thank you! We will get back to you within 24-48 hours.',
    },
    dashboard: {
      welcome: 'Welcome back',
      stats: {
        total_orders: 'Total Orders',
        pending: 'Pending',
        completed: 'Completed',
        revenue: 'Revenue',
      }
    },
    common: {
      save: 'Save', cancel: 'Cancel', delete: 'Delete',
      edit: 'Edit', view: 'View', loading: 'Loading...',
      search: 'Search', filter: 'Filter', reset: 'Reset',
      yes: 'Yes', no: 'No', confirm: 'Confirm',
    }
  },

  am: {
    nav: {
      home: 'መነሻ', products: 'ምርቶች', services: 'አገልግሎቶች',
      about: 'ስለ እኛ', contact: 'ያግኙን', market: 'ገበያ',
      login: 'ግባ', register: 'ተመዝገብ', dashboard: 'ዳሽቦርድ',
      logout: 'ውጣ',
    },
    hero: {
      title: 'የኢትዮጵያ ቡና',
      subtitle: 'ከእርሻ እስከ ዓለም',
      description: 'የኢትዮጵያ ቡና አምራቾችን፣ ነጋዴዎችን፣ ደንበኞችን እና ዓለም አቀፍ ባለሀብቶችን በአንድ የተመሰከረ መድረክ ላይ ያገናኛል።',
      cta_primary: 'ቡናችንን ያስሱ',
      cta_secondary: 'ይቀላቀሉ',
    },
    roles: {
      farmer: 'አርሶ አደር', trader: 'ነጋዴ', investor: 'ባለሀብት',
      customer: 'ደንበኛ', admin: 'አስተዳዳሪ',
    },
    products: {
      title: 'ቡናችን',
      description: 'ለእያንዳንዱ ጣዕም እና ገበያ የሚሆን ምርጥ የኢትዮጵያ ቡና ደረጃዎች',
      add_to_cart: 'ወደ ጋሪ ጨምር',
      request_quote: 'ዋጋ ጠይቅ',
      view_details: 'ዝርዝር ይመልከቱ',
      grade: 'ደረጃ',
      origin: 'መነሻ',
      weight: 'ክብደት (ኪ.ግ)',
      price: 'ዋጋ',
      in_stock: 'አለ',
      out_of_stock: 'የለም',
    },
    orders: {
      title: 'ትዕዛዞቼ',
      place_order: 'ትዕዛዝ ስጥ',
      track_order: 'ትዕዛዝ ክትትል',
      status: {
        pending: 'በመጠባበቅ ላይ',
        approved: 'ጸድቋል',
        processing: 'በሂደት ላይ',
        shipped: 'ተላከ',
        delivered: 'ደረሰ',
        cancelled: 'ተሰርዟል',
      }
    },
    auth: {
      login_title: 'እንኳን ደህና ተመለሱ',
      login_sub: 'ወደ BUNNA መለያዎ ይግቡ',
      register_title: 'BUNNA ይቀላቀሉ',
      register_sub: 'ለመጀመር መለያ ይፍጠሩ',
      email: 'ኢሜይል አድራሻ',
      password: 'የይለፍ ቃል',
      name: 'ሙሉ ስም',
      role: 'እኔ ነኝ',
      confirm_password: 'የይለፍ ቃሉን አረጋግጥ',
      login_btn: 'ግባ',
      register_btn: 'መለያ ፍጠር',
      forgot: 'የይለፍ ቃል ረሱ?',
      no_account: 'መለያ የለዎትም?',
      have_account: 'መለያ አለዎት?',
    },
    contact: {
      title: 'ያግኙን',
      name: 'ሙሉ ስም',
      email: 'ኢሜይል',
      subject: 'ርዕሰ ጉዳይ',
      message: 'መልዕክት',
      send: 'መልዕክት ላክ',
      success: 'አመሰግናለሁ! ከ24-48 ሰዓታት ውስጥ እንዳናደርስዎ።',
    },
    dashboard: {
      welcome: 'እንኳን ደህና ተመለሱ',
      stats: {
        total_orders: 'ጠቅላላ ትዕዛዞች',
        pending: 'በጥበቃ ላይ',
        completed: 'ተጠናቋል',
        revenue: 'ገቢ',
      }
    },
    common: {
      save: 'አስቀምጥ', cancel: 'ሰርዝ', delete: 'ሰርዝ',
      edit: 'አርም', view: 'ይመልከቱ', loading: 'በመጫን ላይ...',
      search: 'ፈልግ', filter: 'አጣራ', reset: 'ዳግም አስጀምር',
      yes: 'አዎ', no: 'አይ', confirm: 'አረጋግጥ',
    }
  }
}

export default createI18n({
  legacy: false,
  locale: localStorage.getItem('bunna_locale') || 'en',
  fallbackLocale: 'en',
  messages,
})