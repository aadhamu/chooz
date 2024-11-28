/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.php",              // Root-level PHP files
    "./components/*.php"
  ],
  theme: {
    extend: {
      fontFamily: {
        primary: ['Poppins', 'sans-serif'],
        secondary: ['Inter', 'sans-serif'],
      }
    },
  },
  plugins: [],
}

