/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./**/*.php",
    "./src/**/*.{html,js}",
  ],
  darkMode: "class", // ou 'media' se quiser ativar pelo sistema
  theme: {
    extend: {
      colors: {
        primary: "#004a99",
        secondary: "#00b4d8",
      },
      fontFamily: {
        sans: ["Inter", "ui-sans-serif", "system-ui"],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
};
