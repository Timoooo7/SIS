import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    server: {
        host: "0.0.0.0", // Make it accessible from all IPs
        port: 8001, // Specify the port
        hmr: {
            host: "localhost",
        },
    },
    plugins: [
        laravel({
            input: ["resources/js/app.js", "resources/css/app.css"],
            refresh: true,
        }),
    ],
    build: {
        outDir: "../public_html/build", // Ensure this path is correct
        emptyOutDir: true, // Clears the output directory before building
    },
});
