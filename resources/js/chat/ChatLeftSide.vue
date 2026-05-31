<template>
    <div :class="['chat-leftsidebar ', store.leftSideOpen ? 'visibleLeftSide' : 'notVisibleLeftSide']">
        <div class="tab-content">

            <!-- Start chats tab-pane -->
            <div id="pills-chat" aria-labelledby="pills-chat-tab" class="tab-pane show active"
                 role="tabpanel">
                <!-- Start chats content -->
                <div>
                    <div class="px-4 pt-4">
                        <div class="d-flex align-items-start">
                            <div class="flex-grow-1">
                                <h4 class="mb-4">Chats</h4>
                            </div>
                        </div>
                        <form>
                            <div class="input-group mb-3">
                                <input id="serachChatUser" v-model="searchQuery" aria-describedby="searchbtn-addon"
                                       aria-label="Example text with button addon" autocomplete="off"
                                       class="form-control bg-light border-0 pe-0"
                                       placeholder="Search here.."
                                       type="text" @keyup="searchConversation">
                                <button id="searchbtn-addon" class="btn btn-light" type="button"><i
                                    class='bx bx-search align-middle'></i></button>
                            </div>
                        </form>

                    </div> <!-- .p-4 -->


                    <div class="chat-room-list" data-simplebar>
                        <!-- Start chat-message-list -->
                        <div class="d-flex align-items-center px-4 mb-2">
                            <div class="flex-grow-1">
                                <h4 class="mb-0 font-size-11 text-muted text-uppercase">Direct Messages</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div data-bs-placement="bottom" data-bs-toggle="tooltip"
                                     data-bs-trigger="hover"
                                     title="New Message">

                                    <!-- Button trigger modal -->
                                    <button class="btn btn-soft-primary btn-sm"
                                            data-bs-target="#contactModal"
                                            data-bs-toggle="modal" type="button">
                                        <i class="bx bx-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="conversationList" @scroll="onScroll">
                            <ul class="list-unstyled chat-list chat-user-list">
                                <ConversationItem v-for="(item, index) in store.lists" :key="index" :item="item"
                                                  :socketurl="socketurl"/>
                                <div class="flex justify-content-center">
                                    <li v-if="store.loading" style="display: flex;justify-content: center;">
                                        <p>Loading...</p>
                                    </li>
                                    <li v-if="store.lastPage && store.lists.length === 0"
                                        style="display: flex;justify-content: center;">
                                        <p>No conversation found.</p>
                                    </li>
                                </div>
                            </ul>
                        </div>
                        <!-- End chat-message-list -->
                    </div>

                </div>
                <!-- Start chats content -->
            </div>
            <!-- End chats tab-pane -->

        </div>
        <!-- end tab content -->

        <!-- contactModal -->
        <div id="contactModal" aria-hidden="true" class="modal fade contactModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-header-colored shadow-lg border-0">
                    <div class="modal-header">
                        <h5 class="modal-title text-white font-size-16">Contacts</h5>
                        <button aria-label="Close" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                type="button"></button>
                    </div>
                    <ContactList/>

                </div>
            </div>
        </div>
        <!-- contactModal -->

    </div>
</template>

<script setup>
import ContactList from "./leftSide/ContactList.vue";
import {useConversationsStore} from "./store/conversations";
import ConversationItem from "./leftSide/ConversationItem.vue";
import {ref, onMounted, watch, defineProps} from 'vue';

const props = defineProps({
    socketurl: {
        type: String,
        required: true,
    }
});

const store = useConversationsStore();
const searchQuery = ref(""); // Track the search query

// Function to fetch contacts based on the search query
const searchConversation = () => {
    store.search = searchQuery.value; // Update the store's search value
    store.fetchConversations(true); // Fetch the contacts based on the search, reset pagination
}

// Function to load more contacts
const loadMoreConversations = () => {
    store.fetchConversations(); // Load more contacts
}

// Handle scroll event for infinite scroll and load more
const onScroll = (event) => {
    const target = event.target;
    const scrollTop = target.scrollTop;
    const scrollHeight = target.scrollHeight;
    const clientHeight = target.clientHeight;

    if (scrollTop + clientHeight >= scrollHeight - 20) {
        loadMoreConversations();
    }
}

onMounted(() => {
    store.fetchConversations();
});


// Watch for changes in the search query and fetch contacts
watch(searchQuery, () => {
    searchConversation(); // Search and reset pagination
});

// Watch for changes in the store to control load more visibility and loader visibility
// watch(() => [store.lists, store.loading, store.lastPage], ([lists, loading, lastPage]) => {
//     showLoadMore.value = !lastPage; // Show load more if there are contacts to load
// });

</script>

<style scoped>
#conversationList {
    max-height: 100%;
    height: calc(100vh - 330px);
    overflow-y: auto;
}

/* Custom Scrollbar styles for WebKit browsers (Chrome, Safari, Edge) */
#conversationList::-webkit-scrollbar {
    width: 5px;
}

#conversationList::-webkit-scrollbar-track {
    background-color: #262626; /* Track background */
    border-radius: 10px;
}

#conversationList::-webkit-scrollbar-thumb {
    background-color: #525252; /* Scrollbar color */
    border-radius: 10px;
}

#conversationList::-webkit-scrollbar-thumb:hover {
    background-color: #262626; /* Scrollbar hover color */
}

/* Custom Scrollbar styles for Firefox */
#conversationList {
    scrollbar-width: thin; /* Thin scrollbar */
    scrollbar-color: #525252; /* Scrollbar thumb and track colors */
}
</style>
