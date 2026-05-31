<template>
    <div class="modal fade mt-5" id="checkoutmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
         style="z-index:9999999999999999999">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" style="margin-top:18px">
                    <h5 class="modal-title" id="exampleModalLabel">Checkout</h5>
                    <button type="button" @click="store.hidecheckout()" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close" style="background-color:red;"></button>
                </div>
                <div v-if="store?.orderError !== '' " class="modal-header" style="margin:0px !important;">
                    <p class="text-danger" style="margin: 0 auto !important;">
                        {{ store?.orderError }}</p>
                </div>
                <div class="modal-body" style="padding-left:10px;">
                    <div class="row d-flex justify-content-end">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>SL.</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in store.items">
                                        <td>{{ item.id }}</td>
                                        <td><img
                                            v-bind:src="'https://admin.ebitans.com/assets/images/product/' + item.image"
                                            width="50" class="ms-1"/></td>
                                        <td>{{ item.name }}</td>
                                        <td>{{ item.price }}</td>
                                        <td>{{ item.quantity }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h1>Payment Method</h1>
                            <div class="d-flex align-items-center justify-content-center" style="height:200px">
                                <div
                                    class="online mx-1 rounded-2 bg-dark text-light d-flex justify-content-center align-items-center text-center cursor-pointer"
                                    id="online" @click="store.online()" style="height:100px;width:100px">
                                    Online
                                </div>
                                <div
                                    class="cod mx-1 rounded-2 bg-danger text-light d-flex justify-content-center align-items-center text-center cursor-pointer"
                                    id="cod" @click="store.cod()" style="height:100px;width:100px">
                                    Cash On Delivery
                                </div>
                            </div>
                            <div v-if="store.paymentmethod=='online'" class="mt-2">
                                <input type="text" :value="store.transactionid" placeholder="Transaction Id"
                                       @keyup="store.transactionids" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="fw-bold mb-0">Subtotal</p>
                                <p class="mb-0">৳{{ store.subtotal }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="fw-bold mb-0">Tax</p>
                                <p class="mb-0">৳{{ store.tax }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="fw-bold mb-0">Discount</p>
                                <!-- <input type="number" :value="store.discount" name="discount" class="form-control" style="width:100px"> -->
                                <p class="mb-0">৳{{ store.discount }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="fw-bold mb-0">Extra Discount</p>
                                <input type="number" :value="store.extradiscount"
                                       name="discount"
                                       @keyup="store.extradiscountss" class="form-control" min="0" style="width:100px">
                                <!-- <p class="mb-0">৳{{store.discount}}</p> -->
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-1">
                                <p class="fw-bold mb-0">Paid</p>
                                <input type="number" min="0" :value="store.paid" name="paid"
                                       class="form-control"
                                       @keyup="store.paidss" style="width:100px">
                                <!-- <p class="mb-0">৳{{store.discount}}</p> -->
                            </div>
                            <hr>
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="fw-bold mb-0">Total</p>
                                <p class="mb-0 text-success fw-bold">৳{{ store.total }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="fw-bold mb-0">Extra Discount</p>
                                <p class="mb-0 text-success fw-bold">৳{{ store.extradiscount }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="fw-bold mb-0">Grand Total</p>
                                <p class="mb-0 text-success fw-bold">৳{{ store.grandTotal }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="fw-bold mb-0">Paid</p>
                                <p class="mb-0 text-success fw-bold">৳{{ store.paid }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="fw-bold mb-0">Due</p>
                                <p class="mb-0 text-success fw-bold">৳{{ store.due }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" id="editorderbtn">
                    <!-- <button type="button" class="btn btn-danger"  @click="store.deleteholdorder(store.holdorderid)">Delete</button> -->
                    <button type="button" class="btn btn-primary orderAndPrintBtn"
                            @click="store.placeorderWithPrint(store.holdorderid)">
                        Place
                        Order And Print
                    </button>
                    <button type="button" class="btn btn-primary" @click="store.placeorder(store.holdorderid)">Place
                        Order
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- invoice print -->
    <div class="modal fade mt-5" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true"
         style="z-index:9999999999999999999">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header" style="margin-top:18px">
                    <h5 class="modal-title" id="exampleModalLabel">Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close" style="background-color:red;" @click="closeInvoice()"></button>
                </div>
                <div class="modal-body" style="padding-left:10px;">
                    <div class="row d-flex justify-content-end">
                        <div class="col-md-12">
                            <div class="receipt printDiv" id="printDiv">
                                <div class="logo">
                                    <img :src="store?.invoice?.logo" alt="Store Logo">
                                </div>
                                <div class="title">
                                    <h3>SALES RECEIPT</h3>
                                </div>

                                <table class="receipt-table">
                                    <thead>
                                    <tr>
                                        <th class="fontSize">Qty</th>
                                        <th class="fontSize" style="padding: 0 10px;">Item Description</th>
                                        <th class="fontSize">Price</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(item, index) in store?.invoice?.products" :key="index">
                                        <td>{{ item?.quantity }}x</td>
                                        <td>{{ item?.name }}</td>
                                        <td>৳{{ item?.price }}</td>
                                    </tr>
                                    </tbody>
                                </table>

                                <div class="col-12 mt-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="fw-bold mb-0">Subtotal</p>
                                        <p class="mb-0">৳{{ store?.invoice?.order?.subtotal }}</p>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="fw-bold mb-0">Tax</p>
                                        <p class="mb-0">৳{{ store?.invoice?.order?.tax }}</p>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="fw-bold mb-0">Discount</p>
                                        <p class="mb-0">৳{{ store?.invoice?.order?.discount }}</p>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="fw-bold mb-0">Extra Discount</p>
                                        <p class="mb-0">৳{{ store?.invoice?.order?.extradiscount }}</p>
                                    </div>
                                    <hr>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="fw-bold mb-0">Total</p>
                                        <p class="mb-0 fw-bold">৳{{ store?.invoice?.order?.total }}</p>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="fw-bold mb-0">Paid</p>
                                        <p class="mb-0 fw-bold">৳{{ store?.invoice?.order?.paid }}</p>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="fw-bold mb-0">Due</p>
                                        <p class="mb-0 fw-bold">৳{{ store?.invoice?.order?.due }}</p>
                                    </div>
                                </div>
                                <div class="footer">
                                    <p>THANK YOU</p>
                                    <p>Receipt {{ store?.invoice?.order?.reference_no }} | Date:
                                        {{ getDate(store?.invoice?.order?.updated_at) }} | Time:
                                        {{ getTime(store?.invoice?.order?.updated_at) }} | Cashier:
                                        {{ store?.invoice?.cashier }}</p>
                                </div>
                            </div>

                            <div class="flex justify-content-center align-items-center mt-3"
                                 style="text-align: center;">
                                <button class="printBtn" @click="printInvoice()">Print</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border-top border-dark py-3 px-2 h-30">
        <div v-if="store.customer && store.total > 0" class="d-flex justify-content-between py-4">
            <button class="btn btn-primary" style="width:48%" @click="store.placeorderss()">Place Order</button>
            <button class="btn btn-danger" style="width:48%" @click="store.holdorder()">Hold Order</button>
        </div>
        <div v-else-if="store.total" class="d-flex justify-content-between py-4">
            <button class="btn btn-secondary" style="width:48%" @click="store.showaddcustomer()">Place Order</button>
            <button class="btn btn-danger" style="width:48%" @click="store.holdorder()">Hold Order</button>
        </div>
        <div v-else class="d-flex justify-content-between py-4">
            <button class="btn btn-secondary" style="width:48%">Place Order</button>
            <button class="btn btn-secondary" style="width:48%">Hold Order</button>
        </div>
    </div>
</template>
<script setup>
import {store} from '../store';
import dayjs from "dayjs";
import {onUnmounted} from "vue";

const getDate = (date) => {
    return dayjs(date).format('DD/MM/YYYY')
}

const getTime = (date) => {
    return dayjs(date).format('hh:mm:ss A')
}

const printInvoice = () => {
    const printContents = document.getElementById("printDiv").innerHTML;
    const originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    window.print();

    document.body.innerHTML = originalContents;

    document.getElementById('invoiceModal').setAttribute("style", "display:none");
    location.reload();
}

const closeInvoice = () => {
    document.getElementById('invoiceModal').setAttribute("style", "display:none");
}

</script>
<style scoped>
.fade:not(.show) {
    opacity: 1 !important;
}

.modal-header {
    margin-top: 36px !important;
}

.orderAndPrintBtn {
    background-color: #0f59c6;
}

.receipt {
    width: 80mm;
    font-family: Arial, sans-serif;
    font-size: 15px;
    margin: 0 auto;
}

.logo {
    text-align: center;
    margin-bottom: 10px;
}

.logo img {
    width: 100px;
}

.title h3 {
    text-align: center;
    margin: 5px 0;
    font-size: 14px;
    text-transform: uppercase;
}

.receipt-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
}

.receipt-table th, .receipt-table td {
    border-bottom: 1px dotted #000;
    padding: 4px 0;
    text-align: left;
}

.receipt-table th {
    font-weight: bold;
    text-transform: uppercase;
}

.receipt-table td {
    text-align: right;
}

.payment-details p {
    text-align: right;
    margin: 0;
}

.footer {
    text-align: center;
    margin-top: 10px;
}

.fontSize {
    font-size: 12px;
}

.barcode {
    text-align: center;
    margin-top: 10px;
}

.cursor-pointer {
    cursor: pointer;
}

@media print {
    body {
        width: 80mm; /* Set the document width to 80mm */
        margin: 0;
        padding: 0;
    }

    #printDiv {
        width: 100%; /* Make sure the printDiv takes up the full width */
    }

    /* Hide elements you don't want to print, like buttons */
    .printBtn {
        display: none;
    }

    body * {
        display: none !important;
    }

    /* Show only the printDiv */
    #printDiv, #printDiv * {
        display: block !important;
        visibility: visible !important;
    }
}
</style>
