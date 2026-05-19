/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./**/*.php",
    "./src/**/*.{html,js}",
  ],

  darkMode: "class",

  theme: {
    extend: {

      colors: {
        primary: "#004a99",
        secondary: "#00b4d8",
      },

      fontFamily: {
        sans: ["Inter", "ui-sans-serif", "system-ui"],
      },

      /* ======================
      KEYFRAMES
      ====================== */

      keyframes: {

        fadeUp: {
          "0%": {
            opacity: "0",
            transform: "translateY(30px)"
          },
          "100%": {
            opacity: "1",
            transform: "translateY(0)"
          }
        },

        fade: {
          "0%": { opacity: "0" },
          "100%": { opacity: "1" }
        },

        blurNumber: {
          "0%": {
            filter: "blur(8px)",
            opacity: "0"
          },
          "100%": {
            filter: "blur(0)",
            opacity: "1"
          }
        },

        marquee: {
          "0%": {
            transform: "translateX(0)"
          },
          "100%": {
            transform: "translateX(-50%)"
          }
        },

        pulseSoft: {
          "0%,100%": { opacity: ".6" },
          "50%": { opacity: "1" }
        }

      },

      /* ======================
      ANIMATIONS
      ====================== */

      animation: {

        fadeUp: "fadeUp .8s ease-out forwards",

        fade: "fade .6s ease-out",

        blurNumber: "blurNumber .5s ease-out",

        marqueeSlow: "marquee 40s linear infinite",

        marquee: "marquee 25s linear infinite",

        pulseSoft: "pulseSoft 2s ease-in-out infinite"

      }

    },
  },

  plugins: [
    require('@tailwindcss/forms'),
  ],

};