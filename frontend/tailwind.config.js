/** @type {import('tailwindcss').Config} */
export default {
  content: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  theme: {
    extend: {
      colors: {
        // Ethiopian Coffee & Heritage Palette
        coffee: {
          50:  '#fdf6f0',
          100: '#fae8d8',
          200: '#f5cdb0',
          300: '#edab80',
          400: '#e3814e',
          500: '#d4602b',
          600: '#b84920',
          700: '#98381c',
          800: '#7c2e1d',
          900: '#65271b',
          950: '#3a120c',
        },
        earth: {
          50:  '#f9f6f1',
          100: '#f0e8db',
          200: '#deccb5',
          300: '#c9aa87',
          400: '#b48860',
          500: '#9e6e45',
          600: '#8a5a39',
          700: '#714830',
          800: '#5d3c2b',
          900: '#4e3326',
          950: '#2a1912',
        },
        forest: {
          50:  '#f2f8f0',
          100: '#e0efdb',
          200: '#c3dfba',
          300: '#98c88f',
          400: '#6dae63',
          500: '#4d9144',
          600: '#3b7434',
          700: '#305c2b',
          800: '#284a25',
          900: '#223d1f',
          950: '#0f2110',
        },
        gold: {
          50:  '#fefdf0',
          100: '#fdf9d4',
          200: '#faf2a4',
          300: '#f5e668',
          400: '#edd534',
          500: '#dbbf14',
          600: '#bd990d',
          700: '#96730e',
          800: '#7c5c13',
          900: '#694d16',
          950: '#3d2a08',
        },
        cream: {
          50:  '#fefcf7',
          100: '#fdf7ea',
          200: '#f9ecd0',
          300: '#f4dba8',
          400: '#edc47a',
          500: '#e4a94d',
          DEFAULT: '#fdf7ea',
        },
        ethiopia: {
          green:  '#078930',
          yellow: '#FCDD09',
          red:    '#DA121A',
          blue:   '#0F47AF',
        }
      },
      fontFamily: {
        display: ['"Playfair Display"', 'Georgia', 'serif'],
        body:    ['"Inter"', 'system-ui', 'sans-serif'],
        amharic: ['"Noto Serif Ethiopic"', 'serif'],
      },
      backgroundImage: {
        'ethiopian-pattern': "url('/src/assets/pattern.svg')",
        'coffee-gradient': 'linear-gradient(135deg, #3a120c 0%, #7c2e1d 40%, #9e6e45 100%)',
        'hero-gradient': 'linear-gradient(180deg, rgba(58,18,12,0.85) 0%, rgba(58,18,12,0.4) 100%)',
      },
      boxShadow: {
        'warm': '0 4px 24px rgba(180, 73, 32, 0.15)',
        'warm-lg': '0 8px 40px rgba(180, 73, 32, 0.25)',
      },
      animation: {
        'fade-up': 'fadeUp 0.6s ease-out forwards',
        'fade-in': 'fadeIn 0.4s ease-out forwards',
      },
      keyframes: {
        fadeUp: {
          '0%':   { opacity: '0', transform: 'translateY(20px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        fadeIn: {
          '0%':   { opacity: '0' },
          '100%': { opacity: '1' },
        }
      }
    },
  },
  plugins: [],
}