/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

import {createApp} from "vue";
import {createPinia} from 'pinia'

import ChatPopup from "./chat/forntend/ChatPopup.vue";

const pinia = createPinia()
const frontendChatApp = createApp({});
frontendChatApp.component('ChatPopup', ChatPopup);

frontendChatApp.use(pinia)
frontendChatApp.mount('#chat-popup');
