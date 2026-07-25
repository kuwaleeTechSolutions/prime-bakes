/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './app/Livewire/**/*.php',
  ],
  darkMode: 'class',
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      colors: {
        // Neutral surfaces / borders — used for shell, cards, tables
        surface: {
          1: '#ffffff',
          2: '#f7f7f6',
          3: '#f0efec',
        },
        border: {
          DEFAULT: '#e5e3de',
          accent: '#0f9d8a',
        },
        text: {
          primary: '#1f1e1c',
          secondary: '#6b6a64',
          muted: '#9c9a92',
          accent: '#0f9d8a',
        },
        // Brand accent — swap these two to re-theme the whole app
        primary: {
          50: '#eafbf7',
          100: '#cdf3e9',
          400: '#28b79b',
          500: '#0f9d8a',
          600: '#0b8172',
          700: '#0a675c',
        },
        secondary: {
          50: '#f4f1fb',
          100: '#e4dcf6',
          400: '#8b6dd9',
          500: '#7451c9',
          600: '#5f3fae',
        },
        // Status colors used across sale/purchase/payroll status pills
        status: {
          paid: '#0f9d8a',
          partial: '#d98c2b',
          unpaid: '#d9483f',
          pending: '#9c9a92',
        },
      },
      borderRadius: {
        card: '0.75rem',
      },
    },
  },
  plugins: [],
};
