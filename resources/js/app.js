import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

import '../css/custom-theme.css';
import Antd from 'ant-design-vue';



const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Admision 2023';

const baseUrl = 'http://admision-web.test'; 

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        props.baseUrl = baseUrl;

        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(Antd)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
        '@primary-color': '#2d2880',
    },
});