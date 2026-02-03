/** @type {import('tailwindcss').Config} */
const colors = require("tailwindcss/colors");

module.exports = {
  important: true,
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./resources/**/*.jsx",
    "./packages/bcs/salt/resources/**/*.jsx",
  ],
  theme: {
    extend: {
      colors: {
        ...colors,
        gray: {
          50: "#888693",
          80: "#CFCFD4"
        },
        purple: {
          primary: "#000000",
          dark: "#110E27"
        },
        green: {
          primary: "#24FF00"
        },
        orange: {
          primary: "#F65900"
        },
        blue: {
          primary: "#227C9D"
        },
        error: {
          DEFAULT: "#f5222d"
        }
      },
      fontFamily: {
        oswald: "Oswald",
        "advent-pro": "Advent Pro",
        poppins: "Poppins",
        inter: "Inter",
        roboto: "Roboto",
        quicksand: "Quicksand"
      },
      screens: {
        xs: "480px",
        sm: "576px",
        md: "768px",
        lg: "992px",
        xl: "1200px",
        xxl: "1600px"
      }
    }
  },
  plugins: []
};
