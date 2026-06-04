<template>
    <div class="container-fluid mt-2 rounded-t-2xl">
        <div class="row">
            <div class="col-md-1">
                <div class="bg-white mb-3 px-4 py-3 rounded-4">
                    <div class="d-flex flex-row align-items-center justify-content-between">
                        <h3 class="catname text-center">All Category</h3>
                    </div>
                    <hr>
                    <div class="row catscroll">
                        <div
                            class="col-md-12 col-sm-6 col-xs-6 card card-body my-2 text-center bg-main border-0 cursor-pointer">
                            <p class="mb-0" style="font-size:13px" @click="store.allproduct()">All Product</p>
                        </div>
                        <div v-for="post in store.posts"
                             class=" col-md-12 col-sm-6 col-xs-6 card card-body my-2 text-center bg-main border-0 cursor-pointer">
                            <img v-bind:src="'/assets/images/icon/' + post.icon"
                                 @click="store.searchproduct(post.id)"/>
                            <p class="mb-0" style="font-size:13px" @click="store.searchproduct(post.id)">{{ post.name }}
                                ({{ post.product }})</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 ps-0 pe-0">
                <div class="row mx-1 pt-2 rounded-4 productrow" style="height:90vh;overflow-y:auto">
                    <div class="col-md-2 col-sm-6 col-xs-6 mb-3" v-for="product in store.products"
                         style="padding-left:0.5rem !important;padding-right: 0.5rem !important;max-height: 400px;">

                        <div class="modal fade mt-5" v-bind:id="`exampleModals`+product.id" tabindex="-1"
                             aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index:9999999999999999999"
                             autofocus>
                            <div class="modal-dialog modal-md">

                                <div class="modal-content">
                                    <div class="modal-header" style="margin-top:18px">
                                        <h5 class="modal-title" id="exampleModalLabel">Choose Veriant</h5>
                                        <button type="button" @click="store.hidemodal(`exampleModals`+product.id)"
                                                class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                                style="background-color:red;"></button>
                                    </div>
                                    <div class="modal-body" style="padding-left:0px;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <ul style="list-style-type:none;text-align:center"
                                                    v-for="vr in product.veriant">
                                                    <li class="variantShowBtn">
                                                        <button class="cursor-pointer btn btn-outline-danger"
                                                                v-if="vr.color && vr.size"
                                                                @click="store.addvericart(vr.id,`exampleModals`+product.id)">
                                                            Color: <span
                                                            :style="{ background: vr.color,color:vr.color}">{{
                                                                vr.color
                                                            }}</span>
                                                            , Size : {{ vr.size }}
                                                        </button>
                                                        <button class="cursor-pointer btn btn-outline-danger"
                                                                v-else-if="vr.color"
                                                                @click="store.addvericart(vr.id,`exampleModals`+product.id)">
                                                            Color: <span
                                                            :style="{ background: vr.color,color:vr.color}">{{
                                                                vr.color
                                                            }}</span>
                                                        </button>
                                                        <button class="cursor-pointer btn btn-outline-danger"
                                                                v-else-if="vr.size"
                                                                @click="store.addvericart(vr.id,`exampleModals`+product.id)">
                                                            Size : {{ vr.size }}
                                                        </button>
                                                        <button class="cursor-pointer btn btn-outline-danger"
                                                                v-else-if="vr.volume && vr.unit"
                                                                @click="store.addvericart(vr.id,`exampleModals`+product.id)">
                                                            Volume: {{ vr.volume }} {{ vr.unit }}
                                                        </button>
                                                    </li>
                                                </ul>
                                                <div v-if="product.veriant.some(vr => vr.volume && vr.unit)"
                                                     style="text-align: center">
                                                    <div
                                                        style="display: flex; flex-direction: column; align-items: center; gap: 8px; width: 100%;margin-left: 10px;">
                                                        <!-- Input + Span -->
                                                        <div class="d-flex gap-2" style="width: 50%;">
                                                            <div
                                                                style="display: flex; align-items: center; border: 1px solid #ccc; border-radius: 5px; overflow: hidden; flex: 1;width: 50%">
                                                                <input
                                                                    v-model="store.customeVolume"
                                                                    type="text"
                                                                    placeholder="0"

                                                                    style="flex: 1; padding: 10px; border: none; outline: none;width:30%"
                                                                >
                                                                <span
                                                                    style="padding: 10px; background-color: #f9f9f9; color: #555; border-left: 1px solid #ccc;">{{
                                                                        product.veriant.find(vr => vr.volume && vr.unit)?.unit || 'N/A'
                                                                    }}</span>
                                                            </div>
                                                            <div
                                                                style="display: flex; align-items: center; border: 1px solid #ccc; border-radius: 5px; overflow: hidden; flex: 1;width: 50%">
                                                                <input
                                                                    v-model="store.customePrice"
                                                                    type="text"
                                                                    placeholder="Unit price"
                                                                    style="flex: 1; padding: 10px; border: none; outline: none;width:30%"
                                                                >
                                                                <span
                                                                    style="padding: 10px; background-color: #f9f9f9; color: #555; border-left: 1px solid #ccc;">TK</span>
                                                            </div>
                                                        </div>

                                                        <!-- Button -->
                                                        <button
                                                            class="cursor-pointer btn btn-outline-danger"
                                                            style="flex: 1; max-width: 50%; padding: 10px; border-radius: 5px;"
                                                            @click="store.addCustomeVericart(product.veriant.find(vr => vr.volume && vr.unit)?.id, `exampleModals${product.id}`)">
                                                            Add Volume
                                                        </button>
                                                        <p v-if="customeError !== '' " class="text-danger mt-2">{{
                                                                store?.customeError
                                                            }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer" id="editorderbtn">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-body text-center bg-white" style="height: 327px;">
                            <img v-bind:src="'/assets/images/product/' + product.image"
                                 height="150"/>
                            <h6 class="mt-1">{{ truncateText(product.name, 70) }}</h6>
                            <h6>৳ {{ product.regular_price }}</h6>

                            <button v-if="product.vr==1" data-bs-toggle="modal"
                                    @click="store.openmodal(`exampleModals`+product.id)"
                                    v-bind:data-bs-target="`#exampleModals`+product.id"
                                    class="btn btn-outline-danger mt-2 w-100 mx-auto">Veriant
                            </button>
                            <button v-else @click="store.addtocart(product.id)"
                                    class="btn btn-outline-danger mt-2 w-100 mx-auto">Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-2 mt-2 rounded-4">
                    <div class="d-flex justify-content-between align-items-center px-2 py-2">
                        <p class="mb-0">New Order Bill</p>
                        <p class="mb-0">Sunday, 28 October, 2022</p>
                    </div>
                    <hr>
                    <div class="overflow-auto cartrow" style="height:50vh">
                        <div v-for="item in store.items"
                             class="d-flex align-items-center justify-content-between mx-2 my-3 rounded-3 overflow-hidden"
                             style="background-color:#E8F3EE">
                            <img v-bind:src="'/assets/images/product/' + item.image" width="50"
                                 class="ms-1"/>
                            <div class="text-start px-2">
                                <p class="mb-0">{{ item.name }}</p>
                                <p class="mb-0">৳{{ formatMoney(item.line_total ?? item.sale_price ?? item.price) }}</p>
                            </div>
                            <div></div>
                            <div class="text-center  me-2">
                                <div class="d-flex pt-2">
                                    <div class="rounded-4 value-button" id="decrease"
                                         v-on:click="store.decreaseValue(item.id)" value="Decrease Value">-
                                    </div>
                                    <input type="number" class="rounded-1 numberss" id="number"
                                           v-model="item.quantity"/>
                                    <div class="rounded-4 value-button" id="increase"
                                         @click="store.increaseValue(item.id)" value="Increase Value">+
                                    </div>
                                </div>
                                <a href="javascript:;" class="text-center py-2 cursor-help fs-6 text-danger my-2"
                                   style="text-decoration:none" @click="store.removeItem(item.id)">Remove</a>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <CartSummary :subtotal="store.subtotal" :discount="store.discount" :total="store.total"
                                     :tax="store.tax"/>
                    </div>
                    <div class="">
                        <Payment/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import {store} from '../store'
import {nextTick, onMounted} from 'vue'
import Category from './Category.vue';
import CategoryItem from './CategoryItem.vue';
// import Product from './Product.vue';
import Order from './Order.vue';
import ProductItem from './ProductItem.vue';
import Cart from './Cart.vue';
import CartItem from './CartItem.vue';
import CartSummary from './CartSummary.vue';
import Payment from './Payment.vue';

const truncateText = (text, length = 50) => {
    if (text.length > length) {
        return text.substring(0, length) + '...';
    }
    return text;
}

const formatMoney = (value) => {
    const amount = Number(value || 0);

    return Number.isInteger(amount) ? amount : amount.toFixed(2);
}

store.createdss();

</script>
<style scoped>
.modal-header {
    margin-top: 36px !important;
}

.productrow::-webkit-scrollbar {
    display: none;
}

.card.card-body.text-center.bg-white {
    justify-content: space-between;
    height: 100% !important;
}

.truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Hide scrollbar for IE, Edge and Firefox */
.productrow {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
}

.cartrow::-webkit-scrollbar {
    display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.cartrow {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
}

.value-button {
    display: inline-block;
    border: 1px solid #FFBB2A;
    margin: 0px;
    width: 22px;
    height: 26px;
    text-align: center;
    vertical-align: middle;
    padding: 0px 0;
    background: #FFBB2A;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

.value-button:hover {
    cursor: pointer;
}

.btn {
    width: 200px;
}

form #decrease {
    margin-right: -4px;
    border-radius: 8px 0 0 8px;
}

form #increase {
    margin-left: -4px;
    border-radius: 0 8px 8px 0;
}

form #input-wrap {
    margin: 0px;
    padding: 0px;
}

input#number {
    text-align: center;
    border: none;
    border-top: 1px solid #fff;
    border-bottom: 1px solid #fff;
    margin: 0px;
    width: 40px;
    height: 26px;
}

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.fade:not(.show) {
    opacity: 1 !important;
}

.bg-main {
    background-color: #E8F3EE;
}

.catname {
    font-size: 14px;
}

.catscroll::-webkit-scrollbar {
    display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.catscroll {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
}

.catscroll {
    max-height: 80vh;
    overflow-y: auto;
}

@media only screen and (max-width: 991px) {
    .catscroll {
        max-height: 25vh;
        overflow-y: hidden;
        overflow-x: scroll;
        display: grid;
    }

    .catscroll .card {
        width: 125px !important;
        grid-row: 2;
        margin-left: 5px;
        margin-right: 5px;
    }

    .col-sm-6 {
        flex: 0 0 auto;
        width: 50% !important;
    }
}

.cursor-pointer {
    cursor: pointer;
}

.variantShowBtn button {
    width: 250px;
}
</style>
