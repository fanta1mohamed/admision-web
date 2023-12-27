import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        })
    ],
    css: {
        preprocessorOptions: {
          less: {
            javascriptEnabled: true,
            lessOptions: {
                modifyVars: {
                  // Aquí puedes personalizar las variables de Less según tus preferencias
                  'primary-color': 'red', // Ejemplo: cambia el color principal
                  'link-color': '#1DA57A',    // Ejemplo: cambia el color de los enlaces
                  'border-radius-base': '12px' // Ejemplo: cambia el radio de los bordes
                },
                javascriptEnabled: true, // Deja esto en true para habilitar JavaScript en Less
              },
          },
        }
    }
});
