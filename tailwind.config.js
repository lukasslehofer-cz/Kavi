/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        // Kavi brand colors
        primary: {
          50: '#fff1f4',
          100: '#ffe3e9',
          200: '#ffc7d4',
          300: '#ffa0b8',
          400: '#ff6990',
          500: '#FF476D', // Main brand pink
          600: '#e6305a',
          700: '#c61f47',
          800: '#a0193a',
          900: '#7d1530',
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
          800: '#222222', // Main text color
          900: '#1a1a1a',
        },
        bluegray: {
          50: '#f8f9fb',
          100: '#f1f3f7',
          200: '#E1E6EF', // Main background for blocks
          300: '#c5cdd9',
          400: '#a3aebf',
          500: '#8290a5',
          600: '#67748a',
          700: '#515c6f',
          800: '#3f4754',
          900: '#2f3642',
        },
      },
      fontFamily: {
        sans: ['Roboto', 'system-ui', 'sans-serif'],
        display: ['Roboto', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}


