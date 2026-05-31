<template>
    <li class="cursor-pointer" @click="selectConversation(item)">
        <div
            :class="['flex-grow-1 overflow-hidden chatItem', isActiveChat(item) ? 'activeChat' : '']">
            <div class="d-flex align-items-center py-2 px-3">
                <div
                    :class="['flex-shrink-0 chat-user-img user-own-img align-self-center me-3 ms-0 ', item?.visitor?.isOnline ? 'online' : '']">
                    <img :class="['rounded-circle avatar-sm ', isFavicon(item) ? 'ebitans_avatar' : '']"
                         :src="getImage(item)"
                         alt="">
                    <span class="user-status"></span>
                </div>
                <div
                    :class="['flex-grow-1 overflow-hidden text-truncate', messageSeenStatus(item)]">
                    <h6 class="mb-0 font-size-18 d-flex align-items-center">
                        <a class="text-truncate flex-grow-1 user-profile-show text-reset p-0" href="#">
                            {{ displayContactInfo(item) }}
                        </a>
                        <span class="font-size-12">{{ timeAgo }}</span>
                    </h6>
                    <p class="text-truncate text-muted mb-0">
                        <small>{{ item?.conversation?.last_message }}</small>
                    </p>
                </div>
            </div>
        </div>
    </li>
</template>

<script setup>
import {useConversationsStore} from "../store/conversations";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import {defineProps, onBeforeUnmount, onMounted, onUnmounted, ref} from "vue";
import {Socket} from "../Socket";

// Extend dayjs with the relativeTime plugin
dayjs.extend(relativeTime);

// Access Pinia store
const store = useConversationsStore();

// Props passed to the component
const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
    socketurl: {
        type: String,
        required: true,
    }
});

const timeAgo = ref(dayjs(props.item?.conversation?.updated_at).fromNow());

const socket = Socket(props.socketurl);

onMounted(() => {
    // Update the relative time every second
    const interval = setInterval(() => {
        timeAgo.value = formatTime(props.item);
    }, 1000);

    listenForMessages();

    // Clean up the interval when the component is unmounted
    onUnmounted(() => {
        clearInterval(interval);
    });

});

onBeforeUnmount(() => {
    // Clean up the socket listener when the component is destroyed
    socket.off('onlineUser');
});

// Display contact info based on visitor data
const displayContactInfo = (item) => {
    const visitor = item?.visitor || {};
    if (visitor?.name) {
        return visitor?.name.trim();
    } else if (visitor?.email) {
        return visitor?.email.trim();
    } else if (visitor?.phone) {
        return visitor?.phone.trim();
    } else {
        return "Unknown name";
    }
};


const isFavicon = (item) => {
    const visitor = item?.visitor || {};
    if (visitor?.image) {
        const url = visitor?.image.trim();
        return url.includes("/fav-icon.png");
    } else {
        return false;
    }
}

const getImage = (item) => {
    const visitor = item?.visitor || {};

    if (visitor?.image) {
        return visitor?.image.trim(); // Trim whitespace and return name if not empty
    } else {
        return '/fav-icon.png';
    }
}

const messageSeenStatus = (item) => {
    if (item?.conversation?.seen_status === 0 && item?.conversation?.sender_type === "visitor") {
        return 'unseenConversation';
    }
    return '';
}

// Format time using dayjs and relativeTime plugin
const formatTime = (item) => {
    let time = item?.conversation?.updated_at || "";
    return dayjs(time).fromNow(); // Use fromNow() for relative time
};

// Function to select the conversation
const selectConversation = (item) => {
    store.leftSideOpen = false;
    store.addSelectedUser(item); // Update the selectedUser state in the store
};

// Function to check if the current item is the active conversation
const isActiveChat = (item) => {
    // Check if the current item's conversation ID matches the selectedUser's conversation ID
    return item?.conversation?.id === store.selectedUser?.conversation?.id || false;
};

const listenForMessages = () => {
    // Listen online user
    socket.on('onlineUser', (data) => {
        store.updateVisitorStatus(data);
    });
}
</script>

<style scoped>
.unseenConversation > h6, .unseenConversation > p > small {
    font-weight: bold;
    color: #f1593a;
}
</style>

