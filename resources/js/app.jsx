import './bootstrap';
import '../css/app.css';

import { createRoot } from 'react-dom/client';
import { createInertiaApp, router } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'CPDS-DK-BS';

// Explicitly set CSRF token for Axios
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

const initLegacyUi = () => {
    if (typeof window.feather !== 'undefined') {
        window.feather.replace();
    }

    const $ = window.jQuery || window.$;
    if ($ && $.fn && $.fn.tooltip) {
        $('[data-bs-toggle="tooltip"]').tooltip();
    }
};

router.on('finish', () => {
    window.requestAnimationFrame(initLegacyUi);
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.jsx`,
            import.meta.glob('./Pages/**/*.jsx')
        ),
    setup({ el, App, props }) {
        const root = createRoot(el);
        root.render(<App {...props} />);
        window.requestAnimationFrame(initLegacyUi);
    },
    progress: {
        color: '#4B5563',
    },
});
