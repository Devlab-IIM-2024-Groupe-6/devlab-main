/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {},
    colors: {
      darkblue: "#0A3C6E",
      lightblue: "#0171B8"
    }
  },
  plugins: [
    require('daisyui'),
  ],
  daisyui: {
    themes: ["nord"], // Add the Nord theme here
  },
}
