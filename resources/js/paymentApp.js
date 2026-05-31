import {createApp} from 'vue';

const app = createApp({});

import payments from './payments/pages/Payments.vue';
import addons from './payments/pages/Addons.vue';
import packages from './payments/pages/Packages.vue';
import packageView from './payments/pages/PackagesView.vue';

app.component('payments', payments);
app.component('addons', addons);
app.component('packages', packages);
app.component('package-view', packageView);
app.mount('#vue-root');
