/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  safelist: [
    'pt-8',
    'pt-12',
    'pt-16',
    'pb-20',
    'pb-28',
    'lg:pt-12',
    'lg:pt-16',
    'lg:pb-28',
  ],
  theme: {
    extend: {
      colors: {
        // Quiet Luxury brand colors
        primary: {
          50: '#fef7f6',
          100: '#fceeed',
          200: '#f9d5d2',
          300: '#f3aea8',
          400: '#e07a71',
          500: '#CA4136', // Main terracotta red
          600: '#b53a30',
          700: '#972f27',
          800: '#7d2820',
          900: '#68251f',
        },
        olive: {
          50: '#f6f7f4',
          100: '#eaebe4',
          200: '#d5d8ca',
          300: '#b8bda8',
          400: '#969c82',
          500: '#636747', // Main olive green
          600: '#565a3e',
          700: '#464934',
          800: '#3a3c2c',
          900: '#323427',
        },
        warm: {
          50: '#fafaf8',
          100: '#f5f5f2',
          200: '#E5E6DF', // Light warm background
          300: '#BCBEB1', // Warm neutral
          400: '#9a9c8f',
          500: '#76716C', // Warm gray
          600: '#5f5b57',
          700: '#4d4a47',
          800: '#403e3b',
          900: '#363433',
        },
        dark: {
          50: '#f5f5f5',
          100: '#e0e0e0',
          200: '#bdbdbd',
          300: '#9e9e9e',
          400: '#757575',
          500: '#616161',
          600: '#424242',
          700: '#2d2d2d',
          800: '#1a1a1a',
          900: '#000000', // Pure black
        },
      },
      fontFamily: {
        sans: ['Roboto', 'system-ui', 'sans-serif'],
        display: ['TexGyreHeros', 'Helvetica Neue', 'Arial', 'sans-serif'],
      },
      keyframes: {
        float: {
          '0%, 100%': { transform: 'translateY(0px)' },
          '50%': { transform: 'translateY(-20px)' },
        },
        'fade-in': {
          '0%': { opacity: '0', transform: 'translateY(20px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        gradient: {
          '0%, 100%': { backgroundPosition: '0% 50%' },
          '50%': { backgroundPosition: '100% 50%' },
        },
      },
      animation: {
        float: 'float 6s ease-in-out infinite',
        'fade-in': 'fade-in 1s ease-out',
        gradient: 'gradient 3s ease infinite',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}


