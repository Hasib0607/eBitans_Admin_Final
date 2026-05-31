import {defineStore} from 'pinia';
import {ref} from 'vue';
import axios from 'axios';
import Cookies from "js-cookie";
import toastr from "toastr";
import "toastr/build/toastr.min.css";

export const usefrontendStore = defineStore('frontend', () => {
    const lists = ref([]);
    const loading = ref(false);
    const page = ref(1); // Start index for pagination
    const lastPage = ref(false); // Indicates if all pages have been loaded

    const selectedUser = ref(null);

    const session_token = ref("");
    const messageSeenRequest = ref(null);

    const isEnableChat = ref(true);
    const sendBtnDisable = ref(false);

    const responseLoading = ref(false);
    const responseEndSession = ref(false);
    const endSessionTimeout = ref(null);

    const userType = ref("1");
    const userLang = ref("0");

    const endSessionMessageShow = ref(false);
    const endSessionMessage = ref("Thanks for chatting with us.");

    const setSession = (name, value, days = 1) => {
        Cookies.set(name, value, {expires: days}); // expires in 1 days
    };

    const getSession = (name) => {
        return Cookies.get(name) || "";
    };

    const destroySession = (name) => {
        Cookies.remove(name, {path: ''});
    };

    const loadSessionData = (data) => {
        const getSessionToken = getSession("chatSession") || "";

        if (getSessionToken !== "") {
            session_token.value = getSessionToken;
        } else {
            session_token.value = "";
        }

        // Check if session_token is not empty, then make API request
        if (session_token.value) {
            data.session_token = session_token.value;
        }

        if (data?.session_token !== "") {
            getUserinfo(data);
        }
    }

    // Get user info and create conversation if not exist
    const getUserinfo = (formData) => {
        try {
            let url = `${window.API_URL}/api/v1/get-visitor/conversation`;

            axios.post(url, formData).then((response) => {
                const data = response.data;

                selectedUser.value = {
                    conversation: data?.conversation || {},
                    visitor: data?.visitor || {},
                }

                userType.value = data?.conversation?.type;
                userLang.value = data?.conversation?.lang;
                isEnableChat.value = data?.isEnableChat;

                if (data?.visitor?.session_token) {
                    setSession('chatSession', data?.visitor?.session_token);
                    session_token.value = data?.visitor?.session_token;
                }

                const messages = data.messages || [];

                if (messages.length > 0) {
                    lists.value = [...lists.value, ...messages];
                    page.value += 1; // Update `page` based on received data length

                    reAssignEndSessionTime();
                }
                if (data?.last_page === data?.current_page) {
                    lastPage.value = true; // No more data to load
                }

            }).catch((err) => {
                // console.error('Error loading conversation:', err.response);
            });
        } catch (error) {
            // console.error('Error loading conversation:', error);
        }
    }

    const fetchMessageList = async (conversationID, reset = false) => {
        if (loading.value || (loading.value && !reset)) return;

        loading.value = true;

        try {
            let url = `${window.API_URL}/api/v1/get-conversations/message/${conversationID}`;

            if (reset) {
                page.value = 1;
                lists.value = []; // Clear the list when resetting
                lastPage.value = false;
            }

            const response = await axios.get(url, {
                params: {
                    page: page.value,
                    session_token: session_token.value || "",
                },
            });

            const data = response?.data?.data || [];

            if (data.length > 0) {
                lists.value = [...lists.value, ...data];
                page.value += 1; // Update `page` based on received data length
            }
            if (response?.data?.last_page === response?.data?.current_page) {
                lastPage.value = true; // No more data to load
            }
        } catch (error) {
            // console.error('Error loading Message:', error);
        } finally {
            loading.value = false; // Reset loading state
        }
    }

    const setNullMessageSeenRequest = () => {
        messageSeenRequest.value = null;
    }

    const messageSeenStatusChange = (data) => {
        const messageID = data?.messageID || "";

        if (messageID !== "") {
            const messageIndex = lists.value.findIndex((message) => message.id === messageID);

            if (messageIndex !== -1) {
                lists.value[messageIndex].seen_status = 1;
            }
        }
    }

    // Message mark as read
    const markMessageAsSeen = async (messageID) => {
        handleEndSessionMessageHidden();
        reAssignEndSessionTime();

        let url = `${window.API_URL}/api/v1/chat-massage/markAsRead`;
        const formData = {
            id: messageID,
            session_token: session_token.value || "",
        };

        await axios.put(url, formData).then(response => {
            if (response.data.status) {
                messageSeenRequest.value = {
                    messageID: response?.data?.data?.id || "",
                    conversationID: response?.data?.data?.conversation_id || "",
                }

                const messageID = response?.data?.data?.id || "";

                // Find and update the message's seen status
                if (messageID !== "") {
                    const messageIndex = lists.value.findIndex((message) => message.id === messageID);

                    if (messageIndex !== -1) {
                        // Update the message's seen_status directly
                        lists.value[messageIndex].seen_status = 1;
                    }
                }
            }
        }).catch((error) => {
            // console.error('Error creating conversation:', error);
        });
    }

    const reAssignEndSessionTime = () => {
        responseEndSession.value = false;
        const endSessionTime = 5 * (60 * 1000);
        startTimeout(endSessionTime);
    }

    const startTimeout = (timeOut) => {
        if (endSessionTimeout.value) {
            clearTimeout(endSessionTimeout.value);
        }

        endSessionTimeout.value = setTimeout(() => {
            responseEndSession.value = true;
        }, timeOut);
    }

    // Add message
    const addMessage = async (data) => {
        handleEndSessionMessageHidden();

        const conversation = data?.conversation || {};
        const message = data?.message || {};

        const response = data?.response || null;
        const timeOut = data?.timeOut != null ? Number(data.timeOut) : 0;
        const messageTimeOut = data?.responseTimeout != null ? Number(data.responseTimeout) : 0;
        const endSessionTime = Number(data?.endSessionTime || 0);

        if (endSessionTime > 0) {
            startTimeout(endSessionTime);
        }

        if (response) {
            setTimeout(() => {
                responseLoading.value = true;
            }, timeOut)

            setTimeout(() => {
                responseLoading.value = false;

                sendBtnDisable.value = false

                // Ensure messageLists.value is initialized properly as an array
                if (!Array.isArray(lists.value)) {
                    lists.value = [];
                }

                // Append new message to messageLists.value
                lists.value.unshift(response);
            }, messageTimeOut)
        }

        isEnableChat.value = data?.isEnableChat;
        updateConversation(conversation);

        // Ensure messageLists.value is initialized properly as an array
        if (!Array.isArray(lists.value)) {
            lists.value = [];
        }

        // Append new message to messageLists.value
        lists.value.unshift(message);
    }

    const updateConversation = (conversation) => {
        selectedUser.value.conversation = conversation;

        userType.value = conversation?.type;
        userLang.value = conversation?.lang;
    }

    const closeEndSession = () => {
        responseEndSession.value = false;
    }

    const handleEndSessionMessageShow = () => {
        endSessionMessageShow.value = true;
    }

    const handleEndSessionMessageHidden = () => {
        endSessionMessageShow.value = false;
    }

    const sessionEnded = async () => {
        let url = `${window.API_URL}/api/v1/chat-session/delete`;
        const formData = {
            conversation_id: selectedUser.value.conversation.id,
            session_token: session_token.value || "",
        };

        if (formData.conversation_id) {
            await axios.delete(url, {data: formData}).then(response => {
                if (response.data.status) {
                    endSessionMessage.value = response.data.message;
                    isEnableChat.value = response?.data.isEnableChat;

                    resetMessageList();
                    closeEndSession();
                    handleEndSessionMessageShow();
                }
            }).catch((error) => {
                // console.error('Error creating conversation:', error);
            });
        }

    }

    const resetMessageList = () => {
        lists.value = [];
        loading.value = false;
        page.value = 1;
        lastPage.value = false;
    }

    const userDataUpdate = async () => {
        let url = `${window.API_URL}/api/v1/conversation/user-data-update`;

        const userData = selectedUser.value;
        const conversationID = userData?.conversation?.id;

        const formData = {
            conversation_id: conversationID,
            type: userType.value,
            lang: userLang.value,
            session_token: session_token.value || "",
        };

        await axios.post(url, formData).then(response => {
            if (response.status) {
                toastr.success("Successfully changed!");
            }
        }).catch((error) => {
            // console.error('Error creating conversation:', error);
        });

    }

    return {
        lists,
        loading,
        page,
        lastPage,
        selectedUser,
        session_token,
        messageSeenRequest,
        responseLoading,
        responseEndSession,
        userType,
        userLang,
        endSessionMessageShow,
        endSessionMessage,
        isEnableChat,
        sendBtnDisable,
        setSession,
        getSession,
        loadSessionData,
        fetchMessageList,
        markMessageAsSeen,
        addMessage,
        messageSeenStatusChange,
        getUserinfo,
        setNullMessageSeenRequest,
        closeEndSession,
        sessionEnded,
        userDataUpdate,
        reAssignEndSessionTime,
    };
});
