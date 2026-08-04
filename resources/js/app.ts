import '../css/app.css';
import './bootstrap';

import { createApp, DefineComponent, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { i18n, isRtl } from './i18n';

const appName = import.meta.env.VITE_APP_NAME || 'بيخالي - Poyekhali';

// اضبط اتجاه الصفحة (rtl/ltr) ولغة <html> قبل ما فيو يركّب أي حاجة،
// عشان منمنعش وميض (flash) لاتجاه غلط أول ما الصفحة تفتح
const initialLocale = (i18n.global.locale as any).value;
document.documentElement.setAttribute('lang', initialLocale);
document.documentElement.setAttribute('dir', isRtl(initialLocale) ? 'rtl' : 'ltr');

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n)
            .mount(el);
    },
    progress: {
        color: '#B8863B',
    },
});
