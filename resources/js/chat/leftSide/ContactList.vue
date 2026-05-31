<template>
    <div class="modal-body p-4">
        <div class="input-group mb-4">
            <input id="searchContactModal"
                   v-model="searchQuery"
                   aria-describedby="contactSearchbtn-addon"
                   aria-label="Example text with button addon"
                   class="form-control bg-light border-0 pe-0"
                   placeholder="Search here.."
                   type="text"
                   @keyup="searchContacts">
            <button id="contactSearchbtn-addon" class="btn btn-light" type="button">
                <i class='bx bx-search align-middle'></i>
            </button>
        </div>

        <div class="d-flex align-items-center px-1">
            <div class="flex-grow-1">
                <h4 class="font-size-11 text-muted text-uppercase">Contacts</h4>
            </div>
        </div>
        <div ref="contactList" class="contact-modal-list mx-n4 px-1">
            <div id="contactList" class="mt-3" @scroll="onScroll">
                <ul class="list-unstyled contact-list">
                    <li v-for="(item, index) in store.lists" :key="index" data-bs-dismiss="modal"
                        @click="createConversation(item)">
                        <div>
                            <h5 class="font-size-14 m-0">{{ displayContactInfo(item) }}</h5>
                        </div>
                    </li>
                    <div class="flex justify-content-center">
                        <li v-if="store.loading" style="display: flex;justify-content: center;">
                            <p>Loading...</p>
                        </li>
                        <li v-if="store.lastPage && store.lists.length === 0"
                            style="display: flex;justify-content: center;">
                            <p>No contacts found.</p>
                        </li>
                    </div>
                </ul>
            </div>
        </div>
    </div>
</template>


<script setup>
import {useContactStore} from '../store/contactStore';
import {useConversationsStore} from '../store/conversations';
import {ref, onMounted, watch} from 'vue';

const store = useContactStore();
const searchQuery = ref(""); // Track the search query

// Function to fetch contacts based on the search query
const searchContacts = () => {
    store.search = searchQuery.value; // Update the store's search value
    store.fetchContacts(true); // Fetch the contacts based on the search, reset pagination
}

const createConversation = (item) => {
    const conversationStore = useConversationsStore();
    conversationStore.createConversation(item);
}

// Function to load more contacts
const loadMoreContacts = () => {
    if (!store.loading) {
        store.fetchContacts(); // Load more contacts if not already loading
    }
}

// Handle scroll event for infinite scroll and load more
const onScroll = (event) => {
    const target = event.target;
    const scrollTop = target.scrollTop;
    const scrollHeight = target.scrollHeight;
    const clientHeight = target.clientHeight;

    if (scrollTop + clientHeight >= scrollHeight - 20 && !store.loading && !store.lastPage) {
        loadMoreContacts();
    }
}

// Function to display contact information (name, email, phone)
const displayContactInfo = (item) => {
    if (item.name) {
        return item.name.trim(); // Trim whitespace and return name if not empty
    } else if (item.email) {
        return item.email.trim(); // Trim whitespace and return email if not empty
    } else if (item.phone) {
        return item.phone.trim(); // Trim whitespace and return phone if not empty
    } else {
        return 'Unknown Contact';
    }
}

// Fetch contacts when the component is mounted
onMounted(() => {
    store.fetchContacts(); // Initial load
});

// Watch for changes in the search query and fetch contacts
watch(searchQuery, () => {
    searchContacts(); // Search and reset pagination
});

</script>

<style scoped>
#contactList {
    max-height: 100%;
    height: 200px;
    overflow-y: auto;
}

/* Custom Scrollbar styles for WebKit browsers (Chrome, Safari, Edge) */
#contactList::-webkit-scrollbar {
    width: 5px;
}

#contactList::-webkit-scrollbar-track {
    background-color: #262626; /* Track background */
    border-radius: 10px;
}

#contactList::-webkit-scrollbar-thumb {
    background-color: #525252; /* Scrollbar color */
    border-radius: 10px;
}

#contactList::-webkit-scrollbar-thumb:hover {
    background-color: #262626; /* Scrollbar hover color */
}

/* Custom Scrollbar styles for Firefox */
#contactList {
    scrollbar-width: thin; /* Thin scrollbar */
    scrollbar-color: #525252; /* Scrollbar thumb and track colors */
}
</style>
