import { defineConfig } from "vite";
import tailwindcss from "@tailwindcss/vite";
import { fileURLToPath } from "url";
import dotenv from "dotenv";
import path from "path";

dotenv.config();

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

export default defineConfig(({ command }) => {
//   const isBuild = command === "build";

  return {
    base:"./",

    resolve: {
      alias: {
        "@": path.resolve(__dirname, "./assets/src"),
      },
    },

    plugins: [tailwindcss()],

    server: {
      origin: "http://localhost:5173", 
      open: process.env.SITE_URL || "http://localhost/wp", 
      host: "localhost",
      port: 5173,
      strictPort: true,
      cors: true,

      hmr: {
        host: "localhost",
      },
    },

    build: {
      assetsDir: "assets",
      outDir: "assets/dist",
      emptyOutDir: true,
      manifest: true,

      rollupOptions: {
        input: path.resolve(__dirname, "assets/src/js/main.js"),
      },
    },
  };
});

