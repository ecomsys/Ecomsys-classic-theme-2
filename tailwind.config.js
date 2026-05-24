/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./**/*.php",
    "./assets/src/**/*.{js,php,html,css}",
    "./components/**/*.{js,php,html,css}",
  ],
  
  safelist: ["hidden"],
  
  theme: {
    // Брейкпоинты
    screens: {
      xs: "320px",        // 320 
      sm: "640px",        // 640 
      md: "768px",        // 768
      lg: "1024px",       // 1024
      xl: "1280px",       // 1280
      "2xl": "1536px",    // 1536
    },
    
    extend: {
      // Если нужны кастомные шрифты
      fontFamily: {
        sans: ["Open Sans", "sans-serif"],
        inter: ["Inter", "sans-serif"],
        unbounded: ["Unbounded", "sans-serif"],
        roboto: ["Roboto", "sans-serif"],
        montserrat: ["Montserrat", "sans-serif"],
      },     
     
    },
  },
  
  plugins: [],
};