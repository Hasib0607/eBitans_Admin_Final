<template>
    <!-- start chat-leftsidebar -->
    <LeftSide :socketurl="socketurl"/>
    <!-- end chat-leftsidebar -->

    <!-- Start User chat -->
    <RightSide :setTyping="setTyping" :socketurl="socketurl" :typingUser="typingUser"/>
    <!-- End User chat -->
</template>

<script setup>
import LeftSide from "./ChatLeftSide.vue";
import RightSide from "./ChatRightSide.vue";
import {computed, defineProps, onBeforeUnmount, onMounted, ref, watch} from "vue";
import {Socket} from "./Socket";
import {useConversationsStore} from "./store/conversations";
import {createSupportRealtimeStream} from "./SupportRealtime";

const props = defineProps({
    socketurl: {
        type: String,
        required: true,
    },
    userid: {
        type: String,
        required: true,
    }
});

const loggedUser = ref(null);
const typingUser = ref(null);
const realtimeCursor = ref(0);
const realtimeStream = ref(null);

const setTyping = (value = null) => {
    typingUser.value = value;
}

const store = useConversationsStore();
const socket = Socket(props.socketurl);

onMounted(() => {
    setTyping();
    loggedUser.value = props.userid || null;
    listenForTyping();
});

onBeforeUnmount(() => {
    closeRealtimeStream();
    socket.off('userTyping');
    socket.off('userStoppedTyping');
});

const selectedConversationId = computed(() => store.selectedUser?.conversation?.id || "");

const closeRealtimeStream = () => {
    if (realtimeStream.value) {
        realtimeStream.value.close();
        realtimeStream.value = null;
    }
};

const openRealtimeStream = (conversationId) => {
    if (!conversationId) {
        closeRealtimeStream();
        return;
    }

    closeRealtimeStream();
    realtimeCursor.value = 0;

    const url = `${window.API_URL}/auth/chat-realtime/stream?conversation_id=${conversationId}&after_id=${realtimeCursor.value}`;
    realtimeStream.value = createSupportRealtimeStream(url, {
        onReady: (data) => {
            realtimeCursor.value = Number(data?.next_cursor || realtimeCursor.value || 0);
        },
        onPing: (data) => {
            realtimeCursor.value = Number(data?.next_cursor || realtimeCursor.value || 0);
        },
        onMessage: (data) => {
            realtimeCursor.value = Number(data?.id || realtimeCursor.value || 0);
            const payload = data?.data || {};
            const message = payload?.message || {};

            if (message?.sender_type === "visitor") {
                setTyping();
                store.addMessage(payload);
            }
        },
        onMessageSeen: (data) => {
            realtimeCursor.value = Number(data?.id || realtimeCursor.value || 0);
            store.messageSeenStatusChange(data);
        },
    });
};

const listenForTyping = () => {
    socket.on('userTyping', (data) => {
        const typingConversationID = data?.conversationID || null;
        if (typingConversationID === selectedConversationId.value) {
            typingUser.value = true;
        }
    });

    socket.on('userStoppedTyping', (data) => {
        const typingConversationID = data?.conversationID || null;
        if (typingConversationID === selectedConversationId.value) {
            typingUser.value = null;
        }
    });
}

watch(selectedConversationId, (conversationId) => {
    openRealtimeStream(conversationId);
});

</script>
