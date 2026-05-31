import {defineStore} from 'pinia';
import {ref} from 'vue';
import axios from 'axios';
import Swal from "sweetalert2";

export const useConversationsStore = defineStore('conversations', () => {
    const lists = ref([]);
    const loading = ref(false);
    const page = ref(1); // Start index for pagination
    const lastPage = ref(false); // Indicates if all pages have been loaded

    const messageLists = ref([]);
    const messageLoading = ref(false);
    const messagePage = ref(1); // Start index for pagination
    const messageLastPage = ref(false); // Indicates if all pages have been loaded

    const search = ref(''); // Search query

    const selectedUser = ref(null);

    const leftSideOpen = ref(true);

    // Function to fetch conversations
    const fetchConversations = async (reset = false) => {
        if (loading.value || (lastPage.value && !reset)) return;

        loading.value = true;

        try {
            let url = `${window.API_URL}/auth/get-chat-conversations`;

            if (reset) {
                page.value = 1;
                lists.value = []; // Clear the list when resetting
                lastPage.value = false;
            }

            const response = await axios.get(url, {
                params: {
                    page: page.value,
                    search: search.value,
                },
            });

            const data = response.data.data || [];

            if (data.length > 0) {
                lists.value = [...lists.value, ...data];
                page.value += 1; // Update `page` based on received data length
            }
            if (response.data.last_page === response.data.current_page) {
                lastPage.value = true; // No more data to load
            }
        } catch (error) {
            // console.error('Error loading conversations:', error);
        } finally {
            loading.value = false; // Reset loading state
        }
    };

    const fetchMessageList = async (conversationID, reset = false) => {
        if (messageLoading.value || (messageLastPage.value && !reset)) return;

        messageLoading.value = true;

        try {
            let url = `${window.API_URL}/auth/get-conversations/message/${conversationID}`;

            if (reset) {
                messagePage.value = 1;
                messageLists.value = []; // Clear the list when resetting
                messageLastPage.value = false;
            }

            const response = await axios.get(url, {
                params: {
                    page: messagePage.value,
                },
            });

            const data = response?.data?.data || [];

            if (data.length > 0) {
                messageLists.value = [...messageLists.value, ...data];
                messagePage.value += 1; // Update `page` based on received data length
            }
            if (response?.data?.last_page === response?.data?.current_page) {
                messageLastPage.value = true; // No more data to load
            }
        } catch (error) {
            // console.error('Error loading Message:', error);
        } finally {
            messageLoading.value = false; // Reset loading state
        }
    }

    // Function to create conversation
    const createConversation = async (item) => {
        // Start creating conversation with the selected user
        loading.value = true;

        try {
            let url = `${window.API_URL}/auth/create/chat-conversation`;

            const formData = {
                user_id: item.id,
                visitor_name: item.name,
                visitor_email: item.email,
                visitor_phone: item.phone,
                image: item.image,
                creator_type: "agent",
            };

            const response = await axios.post(url, formData);

            if (response.data.status) {
                const visitor_id = response.data.data.visitor_id;
                let url = `${window.API_URL}/auth/get/chat-conversations/${visitor_id}`;

                axios.get(url).then((res) => {
                    if (res.data.status) {
                        const data = res?.data?.data[0] || []
                        addConversation(data);
                    }
                }).catch((e) => {

                });
            } else {
                Swal.fire({
                    title: 'Warning',
                    text: response.data.message,
                    icon: 'warning',
                });
            }
        } catch (error) {
            // console.error('Error creating conversation:', error);
        } finally {
            loading.value = false; // Reset loading state
        }
    };

    // Function to manually add conversation to the list
    function addConversation(item) {
        const conversation_id = item.conversation.id;
        const index = lists.value.findIndex((el) => el.conversation.id === conversation_id);

        if (index !== -1) {
            const [existingItem] = lists.value.splice(index, 1);
            lists.value.unshift(existingItem);
        } else {
            lists.value.unshift(item);
        }

        addSelectedUser(item);
    }

    // Update conversation
    const updateConversation = (conversation) => {
        const conversation_id = conversation.id;
        const index = lists.value.findIndex((el) => el.conversation.id === conversation_id);

        if (index !== -1) {
            // Conversation found, update and move to the top
            const existingItem = lists.value.splice(index, 1)[0]; // Remove from current position
            existingItem.conversation = conversation; // Update conversation data
            lists.value.unshift(existingItem); // Add at the beginning
        } else {
            // Conversation not found, add it to the top
            lists.value.unshift({conversation}); // Wrap in an object if needed
        }

        // selectedUser.value.conversation = conversation;
    };

    // Add selected user
    function addSelectedUser(item) {
        selectedUser.value = item;
        let conversationID = item?.conversation?.id || "";
        fetchMessageList(conversationID, true);
    }

    // Message mark as read
    const markMessageAsSeen = async (messageID) => {
        let url = `${window.API_URL}/auth/chat-massage/markAsRead`;
        const formData = {
            id: messageID,
        };

        await axios.put(url, formData).then(response => {
            if (response.data.status) {
                const updatedMessage = response.data.data;

                const conversationID = updatedMessage.conversation_id || "";
                if (conversationID !== "") {
                    const index = lists.value.findIndex((el) => el.conversation.id === conversationID);

                    if (index !== -1) {
                        // Conversation found, update and move to the top
                        const existingItem = lists.value.splice(index, 1)[0];
                        existingItem.conversation.seen_status = 1; // Update conversation data
                        lists.value.unshift(existingItem); // Add at the beginning
                    }
                }

                const messageIndex = messageLists.value.findIndex(message => message.id === messageID);

                if (messageIndex !== -1) {
                    // Update the specific message in the list
                    messageLists.value[messageIndex].seen_status = 1;
                }
            }
        }).catch((error) => {
            // console.error('Error creating conversation:', error);
        });
    }

    // Message seen status change
    const messageSeenStatusChange = (data) => {
        const conversationID = data?.conversationID || "";
        const messageID = data?.messageID || "";

        // Find and update the conversation's seen status
        if (conversationID !== "") {
            const conversationIndex = lists.value.findIndex((el) => el.conversation.id === conversationID);

            if (conversationIndex !== -1) {
                // Update the conversation's seen_status directly
                lists.value[conversationIndex].conversation.seen_status = 1;
            }
        }

        // Find and update the message's seen status
        if (messageID !== "") {
            const messageIndex = messageLists.value.findIndex((message) => message.id === messageID);

            if (messageIndex !== -1) {
                // Update the message's seen_status directly
                messageLists.value[messageIndex].seen_status = 1;
            }
        }
    }

    // Update user online status
    const updateVisitorStatus = (onlineUsers) => {
        // Check if lists.value exists before attempting to map
        if (!lists.value) return;

        lists.value = lists.value.map(item => {
            // Check if the user is online by userID or session_token
            const isOnline = onlineUsers.some(user =>
                user.userID === item.visitor.user_id || user.session_token === item.visitor.session_token
            );

            // Return a new object with the original item and the online status
            return {
                ...item, // Keep the original item structure
                visitor: {
                    ...item.visitor, // Retain the original visitor data
                    isOnline: isOnline, // Add the online status
                }
            };
        });
    };


    // Add message
    const addMessage = async (data) => {
        const conversation = data?.conversation || {};
        const message = data?.message || {};
        updateConversation(conversation);

        // Ensure messageLists.value is initialized properly as an array
        if (!Array.isArray(messageLists.value)) {
            messageLists.value = [];
        }

        // Normalize file_url to always be an array
        if (message.file_url && !Array.isArray(message.file_url)) {
            message.file_url = [message.file_url]; // Convert string to array
        }

        // Append new message to messageLists.value
        messageLists.value.unshift(message);
    }

    return {
        lists,
        loading,
        page,
        lastPage,
        messageLists,
        messageLoading,
        messagePage,
        messageLastPage,
        search,
        selectedUser,
        leftSideOpen,
        addConversation,
        fetchConversations,
        fetchMessageList,
        createConversation,
        addSelectedUser,
        markMessageAsSeen,
        addMessage,
        messageSeenStatusChange,
        updateVisitorStatus,
    };
});
