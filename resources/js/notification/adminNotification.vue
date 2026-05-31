<!-- ChatPopup.vue -->
<template class="relative">
    <li class="nav-item dropdown pe-2 d-flex align-items-center px-2">
        <a href="javascript:void(0);" class="nav-link text-body p-0 tooltip" id="dropdownMenuButton"
           data-bs-placement="bottom" title="Notification"
           @click="showNotificationList = !showNotificationList"
        >
            <div class="notification-wrapper">
                <i class="fa fa-bell notification-icon"
                   :class="{'active': store.totalNotification > 0}"
                ></i>
                <span class="notification-badge"
                      :class="{'d-none': store.totalNotification == null || store.totalNotification < 1}"
                      id="notificationCount">{{ store.totalNotification }}</span>
            </div>
            <!--            <img src="http://localhost:8000/img/notification.png" class="zoom" width="18px">-->
            <span class="tooltiptext tooltip-top">Notification</span>
        </a>

        <div class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4 notoficationUL"
             :class="{'show': showNotificationList}"
             aria-labelledby="dropdownMenuButton" v-html="store.notificationList">
        </div>
    </li>

    <div id="notificationWrapperDiv">
        <div class="topRight">
            <!--            <p>Hello Notification</p>-->
        </div>

        <div v-if="showPopup" class="notification-popup">
            <p>Do you want to enable push notifications?</p>
            <div class="d-flex">
                <button @click="dismissPopup">Deny</button>
                <button @click="requestPermission">Allow</button>
            </div>
        </div>
    </div>

</template>

<script setup>
import {defineProps, onBeforeUnmount, onMounted, ref} from 'vue';
import {useNotificationStore} from "./store";
import {Socket} from "../chat/Socket";

const props = defineProps({
    socketurl: {
        type: String,
        required: true,
    },
    userid: {
        type: Number,
        required: true,
    },
    storeid: {
        type: Number,
        required: true,
    },
    usertype: {
        type: Number,
        required: true,
    },
});


const socket = Socket(props.socketurl);

const store = useNotificationStore();
const showNotificationList = ref(false);
const showPopup = ref(false);


onMounted(() => {
    const permission = Notification.permission;
    if (permission === "default" && !localStorage.getItem("notification_prompted")) {
        showPopup.value = true;
    }

    listenForMessages();

    const userID = props.userid || "";
    const storeID = props.storeid || "";
    const isAdmin = props.usertype || 0;

    store.setStoreData(storeID, userID, isAdmin);
    store.fetchNotificationList(storeID, userID);

    socket.emit('joinStore', {storeID, userID, isAdmin});
})


function requestPermission() {
    if ("Notification" in window) {
        Notification.requestPermission().then(permission => {
            localStorage.setItem("notification_prompted", "true"); // Prevent asking again
            showPopup.value = false; // Close the popup
        }).catch(err => {
            // console.log(err)
        });
    }
}

function dismissPopup() {
    localStorage.setItem("notification_prompted", "true"); // Prevent asking again
    showPopup.value = false;
}

onBeforeUnmount(() => {
    socket.off('notification');
});

const listenForMessages = () => {
    // Listen for user notification
    socket.on('notification', (data) => {
        store.updateNotificationList(data);
    });
}


</script>

<style>
.notification-wrapper {
    position: relative;
    display: inline-block;
}

.notification-icon {
    font-size: 24px;
    color: #333;
    cursor: pointer;
}

.notification-icon.active {
    color: #ff5733 !important;
}

.notification-badge {
    position: absolute;
    top: -7px;
    right: -7px;
    background: red;
    color: white;
    font-size: 12px;
    font-weight: bold;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 20px;
    height: 20px;
    border-radius: 50%;
}

#notificationWrapperDiv {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 999;
}

.topRight {
    position: absolute;
    top: 10px;
    right: 5px;
    background: #0bbd48;
    width: 400px;
    color: black;
}

.notification-popup {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #fff;
    border: 2px solid #ccc;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    max-width: 400px;
    width: 90%;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    opacity: 0; /* Make it invisible initially */
    animation: slideUp 0.5s ease-out forwards; /* Slide up animation */
    animation-delay: 3s; /* Delay animation to start after 5 seconds */
}

.notification-popup p {
    font-size: 16px;
    color: #333;
    margin-bottom: 20px;
}

.notification-popup button {
    padding: 10px 20px;
    font-size: 14px;
    margin: 5px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
    width: 100px;
}

.allow-btn {
    background-color: #4CAF50;
    color: white;
}

.allow-btn:hover {
    background-color: #45a049;
}

.deny-btn {
    background-color: #f44336;
    color: white;
}

.deny-btn:hover {
    background-color: #e53935;
}

@keyframes slideUp {
    0% {
        opacity: 0;
        top: -25px; /* Slide from below */
    }
    100% {
        opacity: 1;
        top: 20px; /* Slide to original position */
    }
}

.dropdown .dropdown-menu.dropdown-menu-end:before {
    right: 35px !important;
    left: auto;
}

div.dropdown-menu.dropdown-menu-end.px-2.py-3.me-sm-n4 {
    width: 450px;
    top: 5px;
    max-height: 50vh;
    overflow-y: auto;
    overflow-x: hidden;
}

@media (max-width: 520px) {
    div.dropdown-menu.dropdown-menu-end.px-2.py-3.me-sm-n4 {
        width: 300px;
    }
}

.notification-item {
    margin-bottom: 6px;
}

.notification-item h6 {
    font-size: 15px;
    margin: 0;
}

.notification-item p {
    font-size: 14px;
}

.notification-item a {
    background: #e8f1fd;
    border-radius: 5px;
    padding: 8px;
}

.collapse.show {
    display: block !important;
}

.list-group {
    list-style: none !important;
}

.notoficationUL .accordion-button {
    padding: 7px 0;
    font-size: 15px;
    color: #344767;
}

.wrap-text {
    word-wrap: break-word; /* Ensures words break if too long */
    overflow-wrap: break-word;
    white-space: normal; /* Allows text to wrap */
    max-width: 100%; /* Ensures it doesn’t overflow */
}

.all_notification {
    padding-top: 5px;
    padding-left: 5px;
    font-size: 15px;
}

.border-top {
    border-top: 1px solid #b0b0b0;
    margin-top: 10px;
}

.notoficationUL.show {
    background: #fff !important;
}

.notoficationUL.show div#collapseuser-create {
    background: #fff !important;
}

.notoficationUL.show ul.list-group {
    background: #fff !important;
}

.notoficationUL.show li.notification-item {
    background: #fff !important;
}

</style>
