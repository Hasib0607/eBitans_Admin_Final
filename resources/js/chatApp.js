import {createApp} from "vue";
import {createPinia} from 'pinia'

import ChatBody from "./chat/ChatBody.vue";

const pinia = createPinia()
const chatApp = createApp({});
chatApp.component('ChatBody', ChatBody);

chatApp.use(pinia)
chatApp.mount('#chat-root');
