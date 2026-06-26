/** @type {import('tailwindcss').Config} */
export default {
  content: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  theme: {
    extend: {
      colors: {
        // Warm Coffee Shop Theme
        coffee: {
          50: '#f5ede3',
          100: '#e8dccc',
          200: '#d4c5a9',
          300: '#c49a6c',
          400: '#8b5e3c',
          500: '#6B3A2A',    // Main coffee color
          600: '#5c2e20',
          700: '#4a2518',
          800: '#3a1d12',
          900: '#2d1510',
        },
        cream: {
          50: '#fdf8f2',
          100: '#f5ede3',
          200: '#e8dccc',
          300: '#d4c5a9',
        },
        warm: {
          beige: '#d4c5a9',
          gold: '#c49a6c',
          brown: '#8b5e3c',
          dark: '#5c3a24',
        },
        // Keep Ethiopian colors for accents
        ethiopia: {
          green: '#078930',
          yellow: '#FCDD09',
          red: '#DA121A',
        }
      },
      fontFamily: {
        display: ['"Playfair Display"', 'Georgia', 'serif'],
        body: ['"Inter"', 'system-ui', 'sans-serif'],
      },
      backgroundImage: {
        'warm-gradient': 'linear-gradient(180deg, #f5ede3 0%, #fdf8f2 100%)',
        'coffee-gradient': 'linear-gradient(135deg, #6B3A2A 0%, #4a2518 100%)',
      },
    },
  },
  plugins: [],
}