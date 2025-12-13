/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.{blade.php,vue,js}',
    './app/**/*.php',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto'],
      },
    },
  },
  plugins: [],
}
