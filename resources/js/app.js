import './bootstrap';
import '@mdi/font/css/materialdesignicons.css';
import 'vuetify/styles';
import '../css/app.css';
import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/inertia-vue3';
import {InertiaProgress} from '@inertiajs/progress';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy/dist/vue.m';

// Plugins
import buildVuetify from "@/Plugins/buildVuetify";
import buildI18n from "@/Plugins/buildI18n";

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Cookbook';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, app, props, plugin}) {
        return createApp({render: () => h(app, props)})
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(buildI18n())
            .use(buildVuetify())
            .mount(el);
    },
});

InertiaProgress.init({color: '#4B5563'});
