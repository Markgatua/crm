import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createPinia } from 'pinia';


// PrimeVue imports
import PrimeVue from 'primevue/config';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import MultiSelect from 'primevue/multiselect';
import DatePicker from 'primevue/datepicker';


// PrimeVue CSS
import 'primeicons/primeicons.css';

const appName = import.meta.env.VITE_APP_NAME || 'XCRM';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const pinia = createPinia();
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue)
            .use(pinia)
            .component('DataTable', DataTable)
            .component('Column', Column)
            .component('InputText', InputText)
            .component('Button', Button)
            .component('MultiSelect', MultiSelect)
            .component('DatePicker', DatePicker)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
