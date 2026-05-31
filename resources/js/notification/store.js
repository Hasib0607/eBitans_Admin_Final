import {defineStore} from 'pinia';
import {ref} from 'vue';
import axios from 'axios';
import Cookies from "js-cookie";
import toastr from "toastr";
import "toastr/build/toastr.min.css";

export const useNotificationStore = defineStore('notification', () => {
    const onlineStoreList = ref([]);
    const notificationList = ref(null);
    const totalNotification = ref(null);
    const storeID = ref(null);
    const userID = ref(null);
    const isAdmin = ref(0);

    // Update online store list
    const updateOnlineStoreList = (data) => {
        onlineStoreList.value = data;
    };

    const setStoreData = (store_id, user_id, isAdminValue) => {
        storeID.value = store_id;
        userID.value = user_id;
        isAdmin.value = isAdminValue;
    }

    const fetchNotificationList = async (storeID, userID) => {
        try {
            let url = `${window.API_URL}/api/v2/get-store-notification/${userID}/${storeID}`;
            const response = await axios.get(url);
            const data = response?.data?.data || "";

            if (data !== "") {
                notificationList.value = data?.html;
                totalNotification.value = data?.totalNotification || null;
            }
        } catch (error) {
            // console.error('Error loading Message:', error);
        }
    }


    // Update online store list
    const updateNotificationList = (data) => {
        fetchNotificationList(storeID.value, userID.value);

        const notificationUrl = data?.message?.url;
        const user_type = data?.message?.user_type;
        const store_id = data?.message?.store_id;
        const user_id = data?.message?.user_id;

        let notification_user_type = 0;
        if (user_type != "" && (user_type == "superadmin" || user_type == "superstaff")) {
            notification_user_type = 1;
        }

        if (notification_user_type == isAdmin.value) {
            if (
                ((store_id != "" && store_id == storeID.value) || store_id == "") &&
                ((user_id != "" && user_id == userID.value) || user_id == "")
            ) {
                // Show the notification
                const notification = new Notification("Notification", {
                    body: data?.message?.message,
                    icon: "/logo-white.png",
                });

                // Open the URL when the notification is clicked
                notification.onclick = function () {
                    window.open(notificationUrl);
                };
            }
        }

    };

    return {
        onlineStoreList,
        notificationList,
        totalNotification,
        setStoreData,
        fetchNotificationList,
        updateOnlineStoreList,
        updateNotificationList,
    };
});
