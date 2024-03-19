import './bootstrap';
import '../css/app.css';
import 'element-plus/dist/index.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

import { Link } from '@inertiajs/vue3';

const routerLink = {
    name: 'routerLink',
    props: ['item'],
    render() {
        return h(this.item.href ? Link : 'a', {}, this.$slots)
    },
    watch: {
        '$page.url'() {
            this.onRouteChange()
        },
    },
    inject: ['onRouteChange'],
}

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { fas } from '@fortawesome/free-solid-svg-icons'

import ElementPlus from 'element-plus'
import ptBr from 'element-plus/es/locale/lang/pt-br'

library.add(fas)

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(ElementPlus, {
                locale: ptBr,
            })
            .component('router-link', routerLink)
            .component('font-awesome-icon', FontAwesomeIcon)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
