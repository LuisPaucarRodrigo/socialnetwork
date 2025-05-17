import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { initFlowbite } from 'flowbite';
import permission from '@/Directives/permission';
import { role_id, permissions } from '@/Store/auth';

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

        // Guardar role_id y permisos globalmente
        window.appAuth = appAuth; // Para mantener compatibilidad con tu middleware


        const app = createApp({
            render: () => h(App, props),
            mounted() {
                initFlowbite();
            }
        });

        app.use(plugin);
        app.use(ZiggyVue, Ziggy);
        app.directive('permission', permission.single)
        app.directive('permission-or', permission.or)
        app.directive('permission-and', permission.and)
        app.mount(el);
    },
    progress: {
        color: '#497DFF',
    },
});
