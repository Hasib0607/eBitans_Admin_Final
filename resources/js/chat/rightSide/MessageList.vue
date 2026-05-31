<template>
    <div id="chat-conversation" class="chat-conversation p-3 p-lg-4">
        <ul id="users-conversation" ref="messageList" class="list-unstyled chat-conversation-list" @scroll="onScroll">
            <Message v-for="(item, index) in store.messageLists" :key="index" :item="item"/>
            <div class="flex justify-content-center">
                <li v-if="store.messageLoading" style="display: flex;justify-content: center;">
                    <p>Loading...</p>
                </li>
                <li v-if="store.messageLastPage && store.messageLists.length === 0"
                    style="display: flex;justify-content: center;">
                    <p>No message found.</p>
                </li>
            </div>
        </ul>
    </div>
</template>

<script setup>
import Message from "./Message.vue";
import {useConversationsStore} from "../store/conversations";
import {onMounted, nextTick, watch, computed} from 'vue';

const store = useConversationsStore();

const conversationID = computed(() => {
    return store?.selectedUser?.conversation?.id || '';
});

// Function to load more messages
const loadMoreMessages = () => {
    return store.fetchMessageList(conversationID.value); // Return the promise for scroll position adjustment
};

// Handle scroll event for infinite scroll and load more (adjusted for column-reverse)
const onScroll = async (event) => {
    const target = event.target;
    const scrollTop = target.scrollTop;
    const scrollHeight = target.scrollHeight;
    const clientHeight = target.clientHeight;

    // Since flex-direction is column-reverse, scrolling to the "top" is actually reaching the bottom of the list.
    const scrolledToTop = Math.abs(scrollTop) + Math.abs(clientHeight) >= Math.abs(scrollHeight) - 20;

    // Trigger loading more messages if you're scrolled near the "bottom" (which is visually the top in reverse layout)
    if (scrolledToTop && !store.messageLoading && !store.messageLastPage) {
        const oldScrollHeight = scrollHeight; // Store current scroll height before loading new messages

        await loadMoreMessages(); // Wait for messages to load

        await nextTick(); // Wait until the DOM is updated
    }
};

// Scroll to bottom of message list
const scrollToBottom = async () => {
    await nextTick(); // Ensure DOM is updated
    const messageList = document.getElementById('chat-conversation');
    if (messageList) {
        messageList.scrollTop = messageList.scrollHeight; // Scroll to the bottom (which is visually the top)
    }
};

onMounted(() => {
    scrollToBottom(); // Scroll to bottom on component mount
});

// Watch changes in message list and scroll behavior
watch([() => store.messageLists, () => store.messageLoading, () => store.messageLastPage], ([messageList, messageLoading, messageLastPage]) => {
    if (!messageLoading && !messageLastPage && messageList.length) {
        scrollToBottom(); // Scroll to bottom when new messages are added
    }
});


</script>

<style scoped>
#chat-conversation {
    padding-right: 0 !important;
    padding-left: 0 !important;
}

ul#users-conversation {
    overflow-y: scroll;
    height: calc(100% - 70px);
    padding: 0 10px;
}

/* Custom Scrollbar styles for WebKit browsers (Chrome, Safari, Edge) */
ul#users-conversation::-webkit-scrollbar {
    width: 5px;
}

ul#users-conversation::-webkit-scrollbar-track {
    background-color: #262626; /* Track background */
    border-radius: 10px;
}

ul#users-conversation::-webkit-scrollbar-thumb {
    background-color: #525252; /* Scrollbar color */
    border-radius: 10px;
}

ul#users-conversation::-webkit-scrollbar-thumb:hover {
    background-color: #262626; /* Scrollbar hover color */
}

ul#users-conversation {
    scrollbar-width: thin; /* Thin scrollbar */
    scrollbar-color: #525252; /* Scrollbar thumb and track colors */
}

.chat-conversation .chat-conversation-list {
    display: flex;
    flex-direction: column-reverse;
}

#users-conversation {
    overflow-x: hidden !important;
}
</style>
