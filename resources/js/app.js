import './bootstrap';
import '@mdi/font/css/materialdesignicons.css';
import 'vuetify/styles';
import '../css/app.css';
import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/inertia-vue3';
import {InertiaProgress} from '@inertiajs/progress';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy/dist/vue.m';
import {createI18n} from "vue-i18n";
import messages from './messages.json';
// Vuetify
import {createVuetify} from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import {aliases, mdi} from "vuetify/iconsets/mdi";
import colors from "@/Theme/colors";

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

const i18n = createI18n({
    legacy: false,
    locale: 'en',
    messages: messages
});

const cookbook = {
    dark: false,
    colors,
}

const vuetify = createVuetify({
    components,
    directives,

    icons: {
        defaultSet: 'mdi',
        aliases,
        sets: {
            mdi,
        }
    },

    theme: {
        defaultTheme: 'cookbook',
        themes: {
            cookbook,
        }
    }
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, app, props, plugin}) {
        return createApp({render: () => h(app, props)})
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(i18n)
            .use(vuetify)
            .mount(el);
    },
});

InertiaProgress.init({color: '#4B5563'});
