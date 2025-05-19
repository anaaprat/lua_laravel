import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        // Asegúrate de que no se utilicen características de Node.js modernas
        target: 'es2015',
        // Evita la minificación que puede causar errores
        minify: false,
    },
    // Desactiva la optimización que puede causar problemas
    optimizeDeps: {
        exclude: ['axios']
    }
});