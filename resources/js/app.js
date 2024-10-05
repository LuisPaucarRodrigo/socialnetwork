import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { initFlowbite } from 'flowbite';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        document.addEventListener('keypress', (event) => {
            const { tagName, type } = event.target;

            if (tagName === 'INPUT' && type !== 'textarea' && event.key === 'Enter') {
                event.preventDefault();
            }
        });

         // Crear una instancia de la aplicación Vue
         const app = createApp({
            render: () => h(App, props),
            mounted() {
                // Inicializar Flowbite aquí
                initFlowbite();
            }
        });

        app.use(plugin);
        app.use(ZiggyVue, Ziggy);
        app.mount(el);
    },
    progress: {
        color: '#497DFF',
    },
});
