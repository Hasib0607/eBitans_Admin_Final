<template>
    <div :class="['user-profile-sidebar', { 'd-block': isUserProfileOpen }]">

        <div class="p-3 border-bottom">
            <div class="user-profile-img">
                <img v-if="getImage(selectedUser)" :src="getImage(selectedUser)" alt=""
                     class="profile-img rounded"/>
                <div class="overlay-content rounded">
                    <div class="user-chat-nav p-2">
                        <div class="d-flex w-100">
                            <div class="flex-grow-1">
                                <button
                                    class="btn nav-btn text-white user-profile-show d-none d-lg-block"
                                    type="button" @click="closeProfile">
                                    <i class="bx bx-x"></i>
                                </button>
                                <button
                                    class="btn nav-btn text-white user-profile-show d-block d-lg-none"
                                    type="button" @click="closeProfile">
                                    <i class="bx bx-left-arrow-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-auto p-3">
                        <h5 v-if="displayContactInfo(selectedUser)" class="user-name mb-1 text-truncate">
                            {{ displayContactInfo(selectedUser) }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <!-- End profile user -->

        <!-- Start user-profile-desc -->
        <div class="p-4 user-profile-desc userinfoDiv" data-simplebar>
            <div class="pb-2">
                <!--                <h5 class="font-size-11 text-uppercase mb-2">Info :</h5>-->

                <div class="d-flex align-items-end">
                    <div class="flex-grow-1">
                        <h5 class="font-size-14 mb-1">Name: <span
                            class="text-muted font-size-14">{{ displayContactInfo(selectedUser) }}</span></h5>
                    </div>
                </div>

                <div class="d-flex align-items-end mt-2">
                    <div class="flex-grow-1">
                        <h5 class="font-size-14 mb-1">Email: <span
                            class="text-muted font-size-14">{{ selectedUser.visitor?.email || '' }}</span></h5>
                    </div>
                </div>

                <div class="d-flex align-items-end mt-2">
                    <div class="flex-grow-1">
                        <h5 class="font-size-14 mb-1">Phone: <span
                            class="text-muted font-size-14">{{ selectedUser.visitor?.phone || '' }}</span></h5>
                    </div>
                </div>

                <div v-if="isOurCustomer(selectedUser?.conversation?.client?.user_type)"
                     class="d-flex align-items-end mt-2">
                    <div class="flex-grow-1">
                        <h5 class="font-size-14 mb-1">Client Type: <span
                            class="text-muted font-size-14">{{
                                selectedUser?.conversation?.client?.user_type || ''
                            }}</span></h5>
                    </div>
                </div>

                <div v-if="isOurCustomer(selectedUser?.conversation?.client?.user_type)"
                     class="d-flex align-items-end mt-2">
                    <div class="flex-grow-1">
                        <h5 class="font-size-14 mb-1">Store name: <span
                            class="text-muted font-size-14">{{
                                selectedUser?.conversation?.client?.store_name || ''
                            }}</span></h5>
                    </div>
                </div>

                <div v-if="isOurCustomer(selectedUser?.conversation?.client?.user_type)"
                     class="d-flex align-items-end mt-2">
                    <div class="flex-grow-1">
                        <h5 class="font-size-14 mb-1">Website: <span
                            class="text-muted font-size-14">{{
                                selectedUser?.conversation?.client?.website_url || ''
                            }}</span></h5>
                    </div>
                </div>

                <div v-if="isOurCustomer(selectedUser?.conversation?.client?.user_type)"
                     class="d-flex align-items-end mt-2">
                    <div class="flex-grow-1">
                        <h5 class="font-size-14 mb-1">Plan name: <span
                            class="text-muted font-size-14">{{
                                selectedUser?.conversation?.client?.plan_name || ''
                            }}</span></h5>
                    </div>
                </div>

                <div v-if="isOurCustomer(selectedUser?.conversation?.client?.user_type)"
                     class="d-flex align-items-end mt-2">
                    <div class="flex-grow-1">
                        <h5 class="font-size-14 mb-1">Plan price: <span
                            class="text-muted font-size-14">{{ selectedUser?.conversation?.client?.symbol || '' }}
                        {{ selectedUser?.conversation?.client?.plan_price || '' }}</span></h5>
                    </div>
                </div>

                <div v-if="isOurCustomer(selectedUser?.conversation?.client?.user_type)"
                     class="d-flex align-items-end mt-2">
                    <div class="flex-grow-1">
                        <h5 class="font-size-14 mb-1">Plan purchase date: <span
                            class="text-muted font-size-14">{{
                                selectedUser?.conversation?.client?.plan_purchase_date || ''
                            }}</span></h5>
                    </div>
                </div>

                <div v-if="isOurCustomer(selectedUser?.conversation?.client?.user_type)"
                     class="d-flex align-items-end mt-2">
                    <div class="flex-grow-1">
                        <h5 class="font-size-14 mb-1">Plan expiry date: <span
                            class="text-muted font-size-14">{{
                                selectedUser?.conversation?.client?.plan_expiry_date || ''
                            }}</span></h5>
                    </div>
                </div>

                <div v-if="isOurCustomer(selectedUser?.conversation?.client?.user_type)"
                     class="d-flex align-items-end mt-2">
                    <div class="flex-grow-1">
                        <h5 class="font-size-14 mb-1">Upcoming plan month: <span
                            class="text-muted font-size-14">{{
                                selectedUser?.conversation?.client?.upcoming_plan_month || ''
                            }}</span></h5>
                    </div>
                </div>

                <div v-if="isOurCustomer(selectedUser?.conversation?.client?.user_type)"
                     class="d-flex align-items-end mt-2">
                    <div class="flex-grow-1">
                        <h5 class="font-size-14 mb-1">Upcoming plan expiry date: <span
                            class="text-muted font-size-14">{{
                                selectedUser?.conversation?.client?.upcoming_plan_expiry_date || ''
                            }}</span></h5>
                    </div>
                </div>


                <h5 v-if="selectedUser?.conversation?.media?.length > 0" class="font-size-11 text-uppercase mb-2 mt-4">
                    Media :</h5>
                <div class="ctext-wrap">
                    <div class="imageWrapper">
                        <div v-for="(image, index) in selectedUser?.conversation?.media"
                             v-if="Array.isArray(selectedUser?.conversation?.media)" :key="index"
                             class="image_Box">
                            <img :src="image" alt="Image"
                                 class="imageFile"/>
                            <a :href="image" target="_blank"><i class="bx bx-download"></i></a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- end user-profile-desc -->
    </div>
</template>

<script setup>
import {useConversationsStore} from '../store/conversations';
import {computed} from "vue";

const store = useConversationsStore();

// Use `computed` to make `selectedUser` reactive
const selectedUser = computed(() => {
    return store?.selectedUser || {};
});

const props = defineProps({
    isUserProfileOpen: {
        type: Boolean,
        required: true,
    },
});

// Emit an event to the parent to close the profile sidebar
const emit = defineEmits(['close-profile']);
const closeProfile = () => {
    emit('close-profile');
};

// Helper functions for displaying user information
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

const getImage = (item) => {
    const visitor = item?.visitor || {};
    if (visitor?.image) {
        return visitor?.image.trim(); // Trim whitespace and return image if not empty
    } else {
        return 'https://ebitans.com/static/media/logo-dark.602bcd5a22dae84824fe.png'; // Fallback to a default avatar if no image
    }
}

const isOurCustomer = (type) => {
    if (type == "admin" || type == "staff" || type == "affiliate" || type == "dropshipper") {
        return true;
    }
    return false;
}

</script>

<style scoped>
.imageFile {
    background: #383838;
    border-radius: 5px;
    padding: 2px;
}

.ctext-wrap {
    margin-bottom: 5px;
}

.ctext-content {
    padding: 3px 10px;
    background: #212121;
    border-radius: 5px;
}

.imageWrapper {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.right .imageWrapper {
    justify-content: end;
}

.imageWrapper img {
    width: 90px;
    margin: 5px;
    object-fit: contain;
}

p.ctext-content {
    width: fit-content;
}

.image_Box {
    width: 90px;
    height: 90px;
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

.p-4.user-profile-desc.simplebar-scrollable-y {
    height: 63% !important;
}

.userinfoDiv {
    height: 63% !important;
}

</style>
