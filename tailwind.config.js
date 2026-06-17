/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',
    './blocks/**/*.php',
    './blocks/**/*.json',
    './inc/**/*.php',
    './template-parts/**/*.php',
    './assets/src/js/**/*.js'
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          50: '#f5f7f2',
          100: '#e8ece1',
          200: '#d3dac8',
          300: '#b8c3a8',
          400: '#9dad8a',
          500: '#7c8d6c',
          600: '#6d7d5e',
          700: '#5c6a4f',
          800: '#4d5843',
          900: '#414a39'
        },
        slate: {
          950: '#0B1220'
        },
        site: '#F8FAFC',
        technical: '#1F2937',
        accent: '#F59E0B'
      },
      fontFamily: {
        sans: ['"Manrope"', 'system-ui', 'sans-serif'],
        display: ['Athelas', 'sans-serif']
      },
      boxShadow: {
        soft: '0 18px 50px -32px rgba(15, 23, 42, 0.32)'
      }
    }
  },
  plugins: []
};
