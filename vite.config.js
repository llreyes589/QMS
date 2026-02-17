import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        tailwindcss(),
        vue(),
    ],
    resolve: {
        alias: {
            vue: "vue/dist/vue.esm-bundler.js",
        },
    },
    // server: {
    //     host: "0.0.0.0", // Allow connections from outside the container
    //     hmr: {
    //         host: "localhost", // The host the browser uses to connect
    //     },
    //     watch: {
    //         usePolling: true, // Use polling if files aren't updating (common in WSL/Docker)
    //     },
    // },
    // build: {
    //     manifest: true,
    // },
});
