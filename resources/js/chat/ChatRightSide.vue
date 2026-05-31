<template>
    <div :class="['user-chat w-100 overflow-hidden', store.leftSideOpen ? 'notVisibleRightSide' : 'visibleRightSide']">
        <div class="user-chat-overlay"></div>

        <div v-if="!isEmpty(selectedUser)" class="chat-content d-lg-flex">
            <!-- start chat conversation section -->
            <div class="w-100 overflow-hidden position-relative">
                <!-- conversation user -->
                <div id="users-chat" class="position-relative">
                    <div class="p-3 p-lg-3 user-chat-topbar">
                        <div class="row align-items-center">
                            <ChatHead :typingUser="typingUser"/>

                            <div v-if="selectedUser?.conversation" class="col-sm-2 col-2">
                                <ul class="list-inline user-chat-nav text-end mb-0">
                                    <li class="list-inline-item d-none d-lg-inline-block me-2 ms-0">
                                        <button class="btn nav-btn user-profile-show" type="button"
                                                @click.stop="toggleUserProfile">
                                            <i class='bx bxs-info-circle'></i>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- end chat user head -->

                    <!-- start chat conversation -->
                    <MessageList/>
                    <!-- end chat conversation end -->
                </div>

                <!-- start chat input section -->
                <MessageOption :onInputChange="onInputChange" :socketurl="socketurl"/>
                <!-- end chat input section -->
            </div>
            <!-- end chat conversation section -->

            <!-- start User profile detail sidebar -->
            <SelectedUser :isUserProfileOpen="isUserProfileOpen" @close-profile="toggleUserProfile"/>

            <!-- end User profile detail sidebar -->
        </div>
        <div v-else class="w-full" style="display: flex; justify-content: center; align-items: center; height: 100%; }">
            <h2 class="flex justify-center items-center">Select a conversation</h2>
        </div>

        <!-- end user chat content -->
    </div>
</template>

<script setup>
import MessageOption from "./rightSide/MessageOption.vue"
import SelectedUser from "./rightSide/SelectedUser.vue"
import ChatHead from "./rightSide/ChatHead.vue"
import MessageList from "./rightSide/MessageList.vue";
import {useConversationsStore} from './store/conversations';
import {computed, defineProps, onBeforeUnmount, onMounted, ref} from "vue";
import {isEmpty} from "lodash";
import {Socket} from "./Socket";

const props = defineProps({
    socketurl: {
        type: String,
        required: true,
    },
    typingUser: {
        type: [Boolean, null], // Accepts Boolean or null
        required: true,
    },
    setTyping: {
        type: Function,
        required: true,
    },
});

const typing = ref(false);
// const typingUser = ref(null);

const store = useConversationsStore();
const socket = Socket(props.socketurl);

// Use `computed` to make `selectedUser` reactive
const selectedUser = computed(() => {
    return store?.selectedUser || {};
});

// Create a reactive variable to track the cart's visibility
const isUserProfileOpen = ref(false);

// Function to toggle the cart's visibility
const toggleUserProfile = () => {
    isUserProfileOpen.value = !isUserProfileOpen.value;
};

onMounted(() => {
    props.setTyping();
    loadMoreMessages();
})

onBeforeUnmount(() => {
    socket.off('userTyping');
    socket.off('userStoppedTyping');
});

const loadMoreMessages = () => {
    // Listen for other users typing in the current conversation
    socket.on('userTyping', (data) => {
        const {userID, session_token, conversationID: typingConversationID} = data;

        // Check if the typing event is for the current conversation
        if (typingConversationID === store?.selectedUser?.conversation?.id) {
            // Update the typing user status
            const typingUser = store?.selectedUser?.visitor?.session_token === session_token; // Replace with function to get user info by userID or session_token
            props.setTyping(typingUser);
        }
    });

    // Listen for other users stopping typing in the current conversation
    socket.on('userStoppedTyping', (data) => {
        const {userID, session_token, conversationID: typingConversationID} = data;

        // Check if the stop typing event is for the current conversation
        if (typingConversationID === store?.selectedUser?.conversation?.id) {
            props.setTyping();
        }
    });
};


let typingTimeout; // Declare typingTimeout in a wider scope

const onInputChange = () => {
    if (!typing.value) {
        typing.value = true;
        socket.emit('typing', {
            userID: selectedUser?.agent?.id,
            session_token: "",
            conversationID: store?.selectedUser?.conversation?.id // Pass the conversation ID
        });
    }

    // Emit stop typing event if no typing after 2 seconds
    clearTimeout(typingTimeout); // Clear any previous timeout
    typingTimeout = setTimeout(() => {
        typing.value = false;
        socket.emit('stoptyping', {
            userID: selectedUser?.agent?.id,
            session_token: "",
            conversationID: store?.selectedUser?.conversation?.id // Pass the conversation ID
        });
    }, 5000); // 5 seconds after the last keypress
};


</script>
<style scoped>
.chat-content.d-lg-flex {
    height: 100%;
}

.user-profile-sidebar.d-block {
    height: 100%;
}

.userinfoDiv {
    height: 63% !important;
}

</style>
