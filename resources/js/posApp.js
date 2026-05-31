import App from "./App.vue";

window.Vue = require('vue');
import {createRouter, createWebHistory} from "vue-router"
import {routes} from './routes';
import Pos from './Pos.vue';
import axios from 'axios'
import VueAxios from 'vue-axios'
import {library} from '@fortawesome/fontawesome-svg-core'
import Vuex from 'vuex'
import {StreamBarcodeReader} from "vue-barcode-reader";
import {ImageBarcodeReader} from "vue-barcode-reader";


/* import font awesome icon component */
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'

/* import specific icons */
import {
    faUserSecret,
    faMagnifyingGlass,
    faQrcode,
    faBell,
    faPlus,
    faHandHoldingDollar,
    faCreditCard,
    faHand,
    faKeyboard
} from '@fortawesome/free-solid-svg-icons'

/* add icons to the library */
library.add(faUserSecret, faMagnifyingGlass, faQrcode, faBell, faPlus, faHandHoldingDollar, faCreditCard, faHand, faKeyboard)
const router = createRouter({
    history: createWebHistory(),
    routes,
})

const posApp = Vue.createApp(Pos)
    .component('font-awesome-icon', FontAwesomeIcon)
    .use(VueAxios, axios)
    .use(Vuex)
    .use(router)
    .mount("#app");

// const dashboard = Vue.createApp(App).component('font-awesome-icon', FontAwesomeIcon).use(VueAxios, axios).use(Vuex).use(router).mount("#appa");

