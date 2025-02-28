/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./public/**/*.php", ".//*.php"],
  theme: {
    extend: {
      width: {
        '40': '10rem',
        '48': '12rem',
    },
    height: {
        '40': '10rem',
        '48': '12rem',
    }
    },
  },
  plugins: [],
}

