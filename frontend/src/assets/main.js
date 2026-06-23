@tailwind base;
@tailwind components;
@tailwind utilities;

@layer base {
  html {
    scroll-behavior: smooth;
  }

  body {
    @apply bg-cream text-earth-900 font-body antialiased;
  }

  h1, h2, h3, h4 {
    @apply font-display;
  }
}

@layer components {
  /* === BUTTONS === */
  .btn-primary {
    @apply inline-flex items-center gap-2 px-6 py-3 bg-coffee-700 text-cream-100
           font-body font-semibold rounded-sm tracking-wide
           hover:bg-coffee-800 active:bg-coffee-950
           transition-all duration-200 shadow-warm hover:shadow-warm-lg
           focus:outline-none focus:ring-2 focus:ring-coffee-500 focus:ring-offset-2;
  }

  .btn-secondary {
    @apply inline-flex items-center gap-2 px-6 py-3 border-2 border-coffee-700 text-coffee-700
           font-body font-semibold rounded-sm tracking-wide
           hover:bg-coffee-700 hover:text-white
           transition-all duration-200
           focus:outline-none focus:ring-2 focus:ring-coffee-500 focus:ring-offset-2;
  }

  .btn-gold {
    @apply inline-flex items-center gap-2 px-6 py-3 bg-gold-500 text-earth-950
           font-body font-semibold rounded-sm tracking-wide
           hover:bg-gold-400 active:bg-gold-600
           transition-all duration-200 shadow-warm
           focus:outline-none focus:ring-2 focus:ring-gold-400 focus:ring-offset-2;
  }

  .btn-outline-white {
    @apply inline-flex items-center gap-2 px-6 py-3 border-2 border-white text-white
           font-body font-semibold rounded-sm tracking-wide
           hover:bg-white hover:text-coffee-900
           transition-all duration-200
           focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2;
  }

  /* === CARDS === */
  .card {
    @apply bg-white rounded-sm border border-earth-100 shadow-sm
           hover:shadow-warm transition-shadow duration-300;
  }

  .card-feature {
    @apply bg-white rounded-sm border-l-4 border-coffee-600 p-6 shadow-sm
           hover:shadow-warm transition-all duration-300;
  }

  /* === INPUTS === */
  .input-field {
    @apply w-full px-4 py-3 border border-earth-200 rounded-sm bg-white
           text-earth-900 font-body placeholder-earth-400
           focus:outline-none focus:border-coffee-500 focus:ring-1 focus:ring-coffee-500
           transition-colors duration-200;
  }

  .input-label {
    @apply block text-sm font-semibold text-earth-700 mb-1.5 tracking-wide uppercase;
  }

  .select-field {
    @apply w-full px-4 py-3 border border-earth-200 rounded-sm bg-white
           text-earth-900 font-body
           focus:outline-none focus:border-coffee-500 focus:ring-1 focus:ring-coffee-500
           transition-colors duration-200 cursor-pointer;
  }

  /* === BADGES === */
  .badge-pending   { @apply inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gold-100 text-gold-800; }
  .badge-approved  { @apply inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-forest-100 text-forest-800; }
  .badge-shipped   { @apply inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800; }
  .badge-delivered { @apply inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-earth-100 text-earth-800; }
  .badge-farmer    { @apply inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-forest-100 text-forest-800; }
  .badge-trader    { @apply inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-coffee-100 text-coffee-800; }
  .badge-investor  { @apply inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gold-100 text-gold-800; }
  .badge-admin     { @apply inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-earth-200 text-earth-900; }

  /* === SECTION === */
  .section {
    @apply py-16 md:py-24;
  }

  .section-title {
    @apply font-display text-3xl md:text-4xl lg:text-5xl text-earth-900 leading-tight;
  }

  .section-subtitle {
    @apply font-body text-lg text-earth-600 leading-relaxed;
  }

  .container-main {
    @apply max-w-7xl mx-auto px-4 sm:px-6 lg:px-8;
  }

  /* === ETHIOPIAN PATTERN DIVIDER === */
  .pattern-divider {
    @apply w-full h-px bg-gradient-to-r from-transparent via-coffee-300 to-transparent my-8;
  }

  .pattern-border {
    background: repeating-linear-gradient(
      90deg,
      #da121a 0px,   #da121a 8px,
      #FCDD09 8px,   #FCDD09 16px,
      #078930 16px,  #078930 24px
    );
    height: 3px;
    width: 100%;
  }

  /* === SIDEBAR NAV === */
  .nav-link {
    @apply flex items-center gap-3 px-4 py-3 rounded-sm text-earth-600 font-medium
           hover:bg-coffee-50 hover:text-coffee-800 transition-all duration-200;
  }

  .nav-link.active {
    @apply bg-coffee-700 text-white shadow-warm;
  }

  /* === TABLE === */
  .table-header {
    @apply px-4 py-3 text-left text-xs font-semibold text-earth-500 uppercase tracking-wider bg-earth-50;
  }

  .table-cell {
    @apply px-4 py-3 text-sm text-earth-800 border-b border-earth-100;
  }

  /* === STAT CARD === */
  .stat-card {
    @apply bg-white p-6 rounded-sm border border-earth-100 shadow-sm;
  }
}

/* Ethiopian ornamental SVG pattern background utility */
.bg-ethiopian-weave {
  background-color: #fdf7ea;
  background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%239e6e45' fill-opacity='0.07' fill-rule='evenodd'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C/g%3E%3C/svg%3E");
}

.bg-teff-weave {
  background-color: #3a120c;
  background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23e4a94d' fill-opacity='0.08' fill-rule='evenodd'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C/g%3E%3C/svg%3E");
}