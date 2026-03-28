import 'bootstrap';
import './bootstrap';
import 'jquery';

import {createInertiaApp, Link} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {createApp, h} from 'vue';
import {ZiggyVue} from 'ziggy-js';
import vSelect from 'vue-select';
import NProgress from 'nprogress';
import toast from "@plugins/notifications.js";
import filters from '@plugins/filter.js';
import '@plugins/index.js';
import {VueTable} from '@components/global/DataTable.vue';
import datePickerPlugin from '@plugins/datePickerPlugin';
import { PerfectScrollbarPlugin } from 'vue3-perfect-scrollbar';
import 'vue3-perfect-scrollbar/style.css';
import { VueDraggableNext } from 'vue-draggable-next';
import Vue3Dropzone from '@jaxtheprime/vue3-dropzone';
import '@jaxtheprime/vue3-dropzone/dist/style.css';
import Dropzone from "@components/global/Dropzone.vue";

const appName = import.meta.env.VITE_APP_NAME || 'Trend Prints Masters';

createInertiaApp({
   title: (title) => `${title} - ${appName}`,
   resolve: (name) =>
      resolvePageComponent(
         `./Pages/${name}.vue`,
         import.meta.glob('./Pages/**/*.vue'),
      ),
   setup({el, App, props, plugin}) {
      return createApp({render: () => h(App, props)})
         .use(plugin)
         .use(toast)
         .use(filters)
         .use(NProgress)
         .use(ZiggyVue)
         .use(datePickerPlugin)
         .use(PerfectScrollbarPlugin)
         .component('Link', Link)
         .component('VueTable', VueTable)
         .component('v-select', vSelect)
         .component('draggable', VueDraggableNext)
         .component('Vue3Dropzone', Vue3Dropzone)
         .component('Dropzone', Dropzone)
         .mount(el);
   },
   progress: {
      delay: 250,
      color: '#4B5563',
      includeCSS: true,
      showSpinner: true,
   },
});
