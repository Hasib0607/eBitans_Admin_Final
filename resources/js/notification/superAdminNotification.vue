<!-- ChatPopup.vue -->
<template class="relative">
    <div class="container-fluid navbars"
         style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
        <div class="row">
            <div class="col-md-12">
                <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                    <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                        <li class="breadcrumb-item active">
                            <a href="/notification">
                                <img src="/img/cubes.png"> <br> Notification
                            </a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-4" id="toplist">
        <div class="row">
            <div class="col-md-6">
                <h4>All Notification</h4>
            </div>
            <div class="col-md-6">
                <ul>
                    <li style="padding:0px;border:0px;"><a href="/notification/create"
                                                           class="btn btn-primary"
                                                           style="display:block;border-radius:0px !important">Create
                        New</a></li>
                </ul>
            </div>
        </div>
        <div class="row mt-5 productlist">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-7">
                                <form class="row" method="post" action="/changenotification/status">
                                    <div class="col-md-4" style="padding-right:1px;">
                                        <input type="hidden" name="text2" id="selectids">
                                        <select class='form-control' name="action" id="action">
                                            <option value="select">Select Option</option>
                                            <option value="delete">Delete</option>
                                        </select>
                                    </div>
                                    <div class="col" style="padding-left:0px;">
                                        <button type="submit" class="btn btn-primary">Apply</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-3"></div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <input type="text" class="form-control"
                                           aria-label="Dollar amount (with dot and two decimal places)"
                                           id="taskfilter">
                                    <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i
                                        class="fa fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <!--                        <div class="alert alert-success"></div>-->

                        <div class="table-responsive">
                            <table class="table" id="taskfilterresult" width="100%">
                                <thead>
                                <tr>
                                    <th width="4%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                    <th width="10%">Message</th>
                                    <th width="200">Body</th>
                                    <th width="20%">Link</th>
                                    <th width="15%">User Type</th>
                                    <th width="11%">Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                    <td><input type="checkbox" name="selectedid"
                                               value="" id="id" class="checkSingle">
                                    </td>
                                    <td>message</td>
                                    <td>body</td>
                                    <td>link</td>
                                    <td>user_type</td>
                                    <td>
                                        <a href=""><img
                                            src="/img/edit.png" width="20px" height="20px"></a>
                                        &nbsp;&nbsp;
                                        <a href=""
                                           onclick="return confirm('Are you sure you want to delete this item?');"><img
                                            src="/img/delete.png" width="25px"
                                            height="25px"></a>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {defineProps, onBeforeUnmount, onMounted, ref} from 'vue';
import {useNotificationStore} from "./store";
import {Socket} from "../chat/Socket";

const props = defineProps({
    socketurl: {
        type: String,
        required: true,
    },
    userid: {
        type: Number,
        required: true,
    }
});


const socket = Socket(props.socketurl);

const store = useNotificationStore();
const visitoName = ref("");


onMounted(() => {
    listenForMessages();

    // const userID = props.userid || "";
    // const storeID = props.storeid || "";
    // const isAdmin = props.usertype || 0;

    // socket.emit('joinStore', {storeID, userID, isAdmin});
})

onBeforeUnmount(() => {
    socket.off('onlineStore');
});

const listenForMessages = () => {
    // Listen for online store list
    socket.on('onlineStore', (data) => {
        store.updateOnlineStoreList(data);
    });
}

</script>

<style scoped>
</style>
