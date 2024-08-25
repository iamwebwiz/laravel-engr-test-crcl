import vue from "@vitejs/plugin-vue";
import {defineConfig} from "vite";
import {resolve} from "node:path";

export default defineConfig({
    plugins: [vue()],
    test: {
        globals: true,
        setupFiles: ['./testSetupFile.js'],
        environment: 'happy-dom'
    },
    resolve: {
        alias: {
            "@": resolve(__dirname, "resources/js"),
        }
    }
});
