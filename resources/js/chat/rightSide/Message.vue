<template>
    <li id="chat-list-1" ref="messageRef" :class="[`chat-list messageDIV${item.id} `, getChatSide(item)]">
        <div class="conversation-list">
            <div class="user-chat-content">
                <div class="ctext-wrap">
                    <!-- Text Message -->
                    <p v-if="checkMessageType('text')" class="mb-0 ctext-content">
                        {{ item?.content || "" }}
                    </p>

                    <!-- Image Message -->
                    <div v-if="checkMessageType('file')" class="imageWrapper">
                        <div v-for="(image, index) in item?.file_url" v-if="Array.isArray(item?.file_url)" :key="index"
                             class="image_Box">
                            <img :src="image" alt="Image"
                                 class="imageFile"/>
                            <a :href="image" target="_blank"><i class="bx bx-download"></i></a>
                        </div>
                    </div>

                    <!-- Mixed Message (Text + Image) -->
                    <div v-if="checkMessageType('mix')" class="mixWrapper">
                        <p class="mb-0 ctext-content">{{ item?.content || "" }}</p>
                        <div class="imageWrapper">
                            <div v-for="(image, index) in item?.file_url" v-if="Array.isArray(item?.file_url)"
                                 :key="index" class="image_Box">
                                <img :src="image" alt="Image"
                                     class="imageFile"/>
                                <a :href="image" target="_blank"><i class="bx bx-download"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="conversation-name"><small class="text-muted time">{{ timeAgo }}</small>
                    <span :class="['check-message-icon', item?.seen_status === 1 ? 'text-success' : 'text-gray-500']">
                        <i class="bx bx-check"></i>
                        <i class="bx bx-check" style="margin-left: -7px;"></i>
                    </span>
                </div>
            </div>
        </div>
    </li>
</template>

<script setup>
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import {onMounted, onUnmounted, ref, watch} from "vue";
import {useConversationsStore} from "../store/conversations";

// Extend dayjs with the relativeTime plugin
dayjs.extend(relativeTime);

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
})

// Access Pinia store
const store = useConversationsStore();
const timeAgo = ref(dayjs(props.item?.created_at).fromNow());

const messageRef = ref(null);
let observer = null;

// Function to mark the message as seen
const markMessageAsSeen = () => {
    const selectedConversationID = store.selectedUser?.conversation?.id || "";
    const messageConversationID = props.item.conversation_id;
    if (messageConversationID == selectedConversationID) {
        if (props.item?.seen_status === 0 || props.item?.seen_status === null) {
            if (props.item?.sender_type === 'visitor') {
                store.markMessageAsSeen(props.item.id);
            }
        }
    }
};


onMounted(() => {
    // Intersection Observer
    observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                markMessageAsSeen();
                // Stop observing once marked
                observer.unobserve(entry.target);
            }
        });
    });

    // Observe the message element
    if (messageRef.value && messageRef.value instanceof Element) {
        observer.observe(messageRef.value);
    }

    // Update the relative time every second
    const interval = setInterval(() => {
        timeAgo.value = formatTime(props.item);
    }, 1000);

    // Clean up the interval when the component is unmounted
    onUnmounted(() => {
        clearInterval(interval);

        if (observer) {
            observer.disconnect(); // Disconnect the observer to clean up properly
        }
    });

});

const getChatSide = (item) => {
    let sender_type = item?.sender_type || "";
    if (sender_type === 'agent' || sender_type === 'bot') {
        return 'right';
    } else {
        return 'left';
    }
}

const formatTime = (item) => {
    let time = item?.created_at || "";
    return dayjs(time).fromNow(); // Use fromNow() for relative time
};


const checkMessageType = (type) => {
    return props.item?.message_type === type;
}

watch(
    () => props.item,
    (newChat, oldChat) => {
        // Re-observe message element when item changes
        if (messageRef.value && messageRef.value instanceof Element) {
            observer.observe(messageRef.value);
        }
    },
    {immediate: true, deep: true} // Watch nested changes and trigger initially
);

</script>

<style scoped>
.imageFile {
    background: #383838;
    border-radius: 5px;
    padding: 2px;
}

.chat-conversation .conversation-list .ctext-wrap {
    margin-bottom: 5px;
}

.ctext-content {
    padding: 3px 10px;
    background: #212121;
    border-radius: 5px;
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

.image_Box {
    width: 120px;
    height: 120px;
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


</style>

