<template>
    <div
        ref="messageRef"
        :class="[`frontendChat message messageDIV${chat.id} `, chat.sender_type === 'visitor' ? 'message-personal' : 'new' ]">
        <figure v-if="chat.sender_type !== 'visitor'" class="avatar">
            <img alt="" src="/fav-icon.png"/>
        </figure>
        <div class="ctext-wrap">
            <!-- Text Message -->
            <p v-if="checkMessageType('text')" class="mb-0 ctext-content">
                {{ chat?.content || "" }}
            </p>

            <!-- Image Message -->
            <div v-if="checkMessageType('file')" class="imageWrapper">
                <div v-for="(image, index) in chat?.file_url" :key="index" class="image_Box">
                    <img :src="image" alt="Image"
                         class="imageFile"/>
                    <a :href="image" target="_blank"><i aria-hidden="true" class="fa fa-cloud-download"></i></a>
                </div>
            </div>

            <!-- Mixed Message (Text + Image) -->
            <div v-if="checkMessageType('mix')" class="mixWrapper">
                <p class="mb-0 ctext-content">{{ chat?.content || "" }}</p>
                <div class="imageWrapper">
                    <div v-for="(image, index) in chat?.file_url" :key="index" class="image_Box">
                        <img :src="image" alt="Image"
                             class="imageFile"/>
                        <a :href="image" target="_blank"><i aria-hidden="true" class="fa fa-cloud-download"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="timestamp">{{ timeAgo }}</div>
    </div>
</template>

<script setup>
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import {onMounted, onUnmounted, ref, watch} from "vue";
import {usefrontendStore} from "../store/frontendStore";

// Extend dayjs with the relativeTime plugin
dayjs.extend(relativeTime);

const props = defineProps({
    chat: {
        type: Object,
        required: true,
    },
})

// Access Pinia store
const store = usefrontendStore();
const timeAgo = ref(dayjs(props.chat?.created_at).fromNow());

const messageRef = ref(null);

// Function to mark the message as seen
const markMessageAsSeen = () => {
    if (props.chat?.seen_status === 0 || props.chat?.seen_status === null) {
        if (props.chat?.sender_type !== 'visitor') {
            store.markMessageAsSeen(props.chat.id);
        }
    }
};

// Intersection Observer
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            markMessageAsSeen();
            // Stop observing once marked
            observer.unobserve(entry.target);
        }
    });
});


onMounted(() => {
    // Observe the message element
    if (messageRef.value) {
        observer.observe(messageRef.value);
    }

    // Update the relative time every second
    const interval = setInterval(() => {
        timeAgo.value = formatTime(props.chat);
    }, 1000);


    // Clean up the interval when the component is unmounted
    onUnmounted(() => {
        clearInterval(interval);
        // observer.unobserve(messageRef.value); // Cleanup observer
        if (messageRef.value) {
            observer.unobserve(messageRef.value);
        }
    });
});

const formatTime = (item) => {
    let time = item?.created_at || "";
    return dayjs(time).fromNow(); // Use fromNow() for relative time
};

const checkMessageType = (type) => {
    return props.chat?.message_type === type;
}

watch(
    () => props.chat,
    (newChat, oldChat) => {
        // Check if the message needs to be marked as seen
        if (messageRef.value) {
            observer.observe(messageRef.value);
        }
    },
    {immediate: true, deep: true} // Watch nested changes and trigger initially
);

</script>

<style scoped>
.frontendChat .imageFile {
    background: #383838;
    border-radius: 5px;
    padding: 2px;
}

.chat-conversation .conversation-list .ctext-wrap {
    margin-bottom: 5px;
}

.frontendChat .ctext-content {
    padding: 0px 5px;
    background: #ff5b00;
}

.mixWrapper {
    display: flex;
    flex-direction: column;
}

.right .mixWrapper {
    align-items: end;
}

.imageWrapper {
    display: flex;
    flex-wrap: wrap;
}

.right .imageWrapper {
    justify-content: end;
}

.imageWrapper img {
    width: 120px;
    margin: 5px;
    object-fit: contain;
}

p.ctext-content {
    width: fit-content;
}

.frontendChat .image_Box {
    width: 120px;
    height: 100px;
    display: flex;
    justify-content: center;
    margin: 5px;
    position: relative;
}

.image_Box a {
    position: absolute;
    right: 5px;
    top: 10px;
    padding: 0px 5px;
    background: #ff793f;
    border-radius: 5px;
}

.image_Box a:hover i {
    color: #000;
}

.message.new {
    position: relative;
}

.timestamp {
    min-width: 150px;
    font-size: 12px !important;
}

.message.message-personal .timestamp {
    position: absolute;
    right: 0;
}


</style>

