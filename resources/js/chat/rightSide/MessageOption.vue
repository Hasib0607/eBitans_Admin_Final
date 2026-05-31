<template>
    <div class="position-relative">
        <div class="chat-input-section p-3 p-lg-4">
            <!-- File Preview Section -->
            <div v-if="selectedFiles.length > 0" id="messageSendImagePreview" class="mt-3"
                 style="position: absolute; left: 0; width: 100%; bottom: 92px; padding: 10px; background: #333333;">
                <h6>Selected Files:</h6>
                <div class="file-preview-list">
                    <div v-for="(file, index) in selectedFiles" :key="index" class="file-preview-item">
                        <!-- If the file is an image, show the preview -->
                        <div v-if="isImage(file)" class="image-preview">
                            <img :src="previewImage(file)" alt="Image preview" class="img-thumbnail" width="100">
                        </div>
                        <div class="file-info">
                            <button class="btn btn-danger btn-sm" type="button" @click="removeFile(index)">
                                <i class="bx bx-x"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <form id="chatinput-form" enctype="multipart/form-data" @submit.prevent="sendMessage">
                <div class="row g-0 align-items-center">
                    <div class="file_Upload"></div>
                    <div class="col-auto">
                        <div class="chat-input-links me-md-2">
                            <div class="links-list-item" @click.stop="toggleFileOption">
                                <button aria-controls="chatinputmorecollapse_old" aria-expanded="false"
                                        class="btn btn-link text-decoration-none btn-lg waves-effect"
                                        data-bs-target="#chatinputmorecollapse-old" data-bs-toggle="collapse"
                                        type="button">
                                    <i class="bx bx-dots-horizontal-rounded align-middle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="position-relative">
                            <div class="chat-input-feedback">
                                Please Enter a Message
                            </div>
                            <input id="chat-input" v-model="messageText" autocomplete="off" class="form-control form-control-lg chat-input"
                                   placeholder="Type your message..."
                                   type="text" @input="onInputChange">
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="chat-input-links ms-2 gap-md-1">
                            <div class="links-list-item">
                                <button class="btn btn-primary btn-lg chat-send waves-effect waves-light"
                                        type="submit">
                                    <i id="submit-btn" class="bx bxs-send align-middle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div id="chatinputmorecollapse"
                 :class="['chat-input-collapse chat-input-collapse1 collapse', { 'show': isFileCartOpen }]">
                <div class="card mb-0">
                    <div class="card-body py-3">
                        <div class="swiper chatinput-links">
                            <div class="swiper-wrapper">
                                <!-- this code for file upload -->
                                <div class="swiper-slide">
                                    <div class="text-center px-2 position-relative">
                                        <div>
                                            <input id="attachedfile-input" accept=".zip,.rar,.7zip,.pdf"
                                                   class="d-none" multiple type="file" @change="handleFileChange">
                                            <label class="avatar-sm mx-auto stretched-link" for="attachedfile-input">
                                                <span
                                                    class="avatar-title font-size-18 bg-primary-subtle text-primary rounded-circle">
                                                    <i class="bx bx-paperclip"></i>
                                                </span>
                                            </label>
                                        </div>
                                        <h5 class="font-size-11 text-uppercase mt-3 mb-0 text-body text-truncate">
                                            Attached</h5>
                                    </div>
                                </div>

                                <!-- this code for image upload -->
                                <div class="swiper-slide">
                                    <div class="text-center px-2 position-relative">
                                        <div>
                                            <input id="galleryfile-input" accept="image/png, image/gif, image/jpeg"
                                                   class="d-none" multiple type="file" @change="handleFileChange">
                                            <label class="avatar-sm mx-auto stretched-link" for="galleryfile-input">
                                                <span
                                                    class="avatar-title font-size-18 bg-primary-subtle text-primary rounded-circle">
                                                    <i class="bx bx-images"></i>
                                                </span>
                                            </label>
                                        </div>
                                        <h5 class="font-size-11 text-uppercase text-truncate mt-3 mb-0">
                                            Gallery</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {defineProps, ref} from 'vue';
import axios from 'axios';
import {useConversationsStore} from "../store/conversations";

const props = defineProps({
    socketurl: {
        type: String,
        required: true,
    },
    onInputChange: {
        type: Function,
        required: true,
    },
});

// Access Pinia store
const store = useConversationsStore();

// Text message input
const messageText = ref('');

// Selected files for attachments and images
const selectedFiles = ref([]);

// State for toggling file option visibility
const isFileCartOpen = ref(false);

// Function to toggle the file upload options
const toggleFileOption = () => {
    document.getElementById('galleryfile-input').click();
    // isFileCartOpen.value = !isFileCartOpen.value;
};

// Function to handle file selection
const handleFileChange = (event) => {
    selectedFiles.value = Array.from(event.target.files); // Convert FileList to Array
    // toggleFileOption();
};

// Helper to check if the file is an image
const isImage = (file) => {
    return file.type.startsWith('image/');
};

// Helper to create a preview URL for images
const previewImage = (file) => {
    return URL.createObjectURL(file); // Generate a preview URL
};

// Function to remove a file from the selected list
const removeFile = (index) => {
    selectedFiles.value.splice(index, 1); // Remove the file at the given index
};


// Function to send the message and files via Axios POST
const sendMessage = async () => {
    if (messageText.value || selectedFiles.value.length > 0) {
        const conversationID = store.selectedUser?.conversation?.id;
        const agentID = store.selectedUser?.conversation?.agent_id;
        const sender_type = "agent";

        const formData = new FormData();
        formData.append('conversation_id', conversationID);
        formData.append('agent_id', agentID);
        formData.append('sender_type', sender_type);
        formData.append('message', messageText.value);

        selectedFiles.value.forEach(file => {
            formData.append('images[]', file);
        });

        try {
            const response = await axios.post(`${window.API_URL}/auth/chat-message/send`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            if (response.data.status) {
                const responseData = response.data?.data || [];
                store.addMessage(responseData);
                messageText.value = ''; // Clear the text input after sending
                selectedFiles.value = []; // Clear the file inputs after sending
            }
        } catch (error) {
            // console.error('Error sending message:', error);
        }
    }
};

</script>

<style>
#messageSendImagePreview .file-preview-item {
    position: relative;
    margin-right: 5px;
}

#messageSendImagePreview .file-info {
    position: absolute;
    right: 0;
    top: 0;
}

#messageSendImagePreview .file-preview-list {
    display: flex;
    flex-wrap: wrap;
}
</style>
