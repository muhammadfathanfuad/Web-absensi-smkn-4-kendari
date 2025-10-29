import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                //css
                "resources/scss/icons.scss",
                "resources/scss/style.scss",
                "node_modules/quill/dist/quill.snow.css",
                "node_modules/quill/dist/quill.bubble.css",
                "node_modules/flatpickr/dist/flatpickr.min.css",
                "node_modules/gridjs/dist/theme/mermaid.css",
                "node_modules/flatpickr/dist/themes/dark.css",
                "node_modules/gridjs/dist/theme/mermaid.min.css",

                //js
                "resources/js/app.js",
                "resources/js/config.js",
                "resources/js/pages/dashboard.js",
                "resources/js/pages/dashboard-murid.js",
                "resources/js/pages/form-quilljs.js",
                "resources/js/pages/form-fileupload.js",
                "resources/js/pages/form-flatepicker.js",
            ],
            refresh: true,
        }),
    ],
});
