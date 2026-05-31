<template>
    <div class="col-sm-10 col-10">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0 d-block d-lg-none me-3" @click="back()">
                <a class="user-chat-remove font-size-18 p-1 backBtn"
                   href="javascript: void(0);"><i
                    class="bx bx-chevron-left align-middle"></i></a>
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <div class="d-flex align-items-center">
                    <div
                        :class="['flex-shrink-0 chat-user-img user-own-img align-self-center me-3 ms-0 ', getOnlineClassStatus(store.selectedUser)]">
                        <img v-if="getImage(store.selectedUser)"
                             :class="['rounded-circle avatar-sm ', isFavicon(store.selectedUser) ? 'ebitans_avatar' : '']"
                             :src="getImage(store.selectedUser)"
                             alt="Profile">
                        <span class="user-status"></span>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <h6 class="text-truncate mb-0 font-size-18"><a
                            v-if="displayContactInfo(store.selectedUser)"
                            class="user-profile-show text-reset" href="#">{{
                                displayContactInfo(store.selectedUser)
                            }}</a>
                            <a v-else class="user-profile-show text-reset" href="#">Select a conversation</a>
                        </h6>

                        <p class="text-truncate text-muted mb-0">
                            <small v-if="getOnlineStatus(store.selectedUser)">{{
                                    getOnlineStatus(store.selectedUser)
                                }}</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {useConversationsStore} from '../store/conversations';
import {isEmpty} from "lodash";
import {defineProps} from "vue";

const props = defineProps({
    typingUser: {
        type: [Boolean, null], // Accepts Boolean or null
        required: true,
    },
});


const store = useConversationsStore();

const displayContactInfo = (item) => {
    const visitor = item?.visitor || {};
    if (visitor?.name) {
        return visitor?.name.trim(); // Trim whitespace and return name if not empty
    } else if (visitor?.email) {
        return visitor?.email.trim(); // Trim whitespace and return email if not empty
    } else if (visitor?.phone) {
        return visitor?.phone.trim(); // Trim whitespace and return phone if not empty
    } else {
        return '';
    }
}

const back = () => {
    store.leftSideOpen = true;
}

const isFavicon = (item) => {
    const visitor = item?.visitor || {};
    if (visitor?.image) {
        const url = visitor?.image.trim();
        return url.includes("/fav-icon.png");
    } else {
        return false;
    }
}

const getImage = (item) => {
    const visitor = item?.visitor || {};
    if (visitor?.image) {
        return visitor?.image.trim(); // Trim whitespace and return name if not empty
    } else {
        return '/fav-icon.png';
    }
}

const getOnlineStatus = (item) => {
    const visitor = item?.visitor || {};
    if (visitor?.isOnline) {
        if (props.typingUser) {
            return "typing...";
        }
        return "online";
    } else {
        return 'Offline';
    }
}

const getOnlineClassStatus = (item) => {
    const visitor = item?.visitor || {};
    if (visitor?.isOnline) {
        return "online";
    } else {
        return 'Offline';
    }
}


</script>
<style scoped>
.backBtn:hover {
    color: #fff;
}
</style>
