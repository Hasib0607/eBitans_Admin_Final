/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

import {createApp} from "vue";
import {createPinia} from 'pinia'

import adminNotification from "./notification/adminNotification.vue";

const pinia = createPinia()
const socketNotification = createApp({});
socketNotification.component('admin-notification', adminNotification);

socketNotification.use(pinia)
socketNotification.mount('#socket-notification');


// Super admin notification
import superAdminNotification from "./notification/superAdminNotification.vue";

const adminSocketNotification = createApp({});
adminSocketNotification.component('super-admin-notification', superAdminNotification);

adminSocketNotification.use(pinia)
adminSocketNotification.mount('#admin-socket-notification');
