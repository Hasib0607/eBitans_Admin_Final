<template>
    <div id="addcustomermodal" aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade mt-5"
         style="z-index:9999999999999999999" tabindex="-1">
        <div class="modal-dialog modal-md">

            <div class="modal-content">
                <div class="modal-header" style="margin-top:18px">
                    <h5 id="exampleModalLabel" class="modal-title">Add Customer</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" style="background-color:red;"
                            type="button" @click="store.hidemodalss()"></button>
                </div>
                <div class="modal-body" style="padding-left:0px;">
                    <div class="row">
                        <div class="col-md-9 mx-auto">
                            <div class="input-group">
                                <span class="input-group-text ttext1">Phone</span>
                                <input :value="store.customers.phone" aria-label="Phone" class="form-control"
                                       type="tel" @keyup="store.queryForKeywords">
                            </div>
                            <div class="input-group mt-3">
                                <span class="input-group-text ttext1">Email</span>
                                <input :value="store.customers.email" aria-label="Email" class="form-control"
                                       type="email" @keyup="store.cusemail">
                            </div>
                            <div class="input-group mt-3">
                                <span class="input-group-text ttext1">Name</span>
                                <input :value="store.customers.name" @keyup="store.cusname" aria-label="First name"
                                       class="form-control" type="text">
                            </div>
                            <div class="input-group mt-3">
                                <span class="input-group-text ttext1">Address</span>
                                <input :value="store.customers.address" @keyup="store.cusaddress" aria-label="Address"
                                       class="form-control" type="text">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="editorderbtn" class="modal-footer">
                    <button class="btn btn-danger" type="button" @click="store.cancelmodal()">Cancel</button>
                    <button class="btn btn-primary" type="button" @click="store.savecustomer()">Save</button>
                    <button class="btn btn-primary" type="button" style="background-color: #0b5ed7"
                            @click="store.savecustomerAndOrder()">Save And Order
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="showholdordermodel" aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade mt-5"
         style="z-index:9999999999999999999" tabindex="-1">
        <div class="modal-dialog modal-xl">

            <div class="modal-content">
                <div class="modal-header" style="margin-top:18px">
                    <h5 id="exampleModalLabel" class="modal-title">Hold Order</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" style="background-color:red;"
                            type="button" @click="store.hideholdorder()"></button>
                </div>
                <div class="modal-body" style="padding-left:0px;">
                    <div class="row">
                        <div class="col-md-3">
                            <ul v-for="order in store.holdorders" class="holdorderlist">
                                <!-- <li @click="store.showholdorders(order.id)"> -->
                                <button class="btn btn-primary" @click="store.showholdorders(order.id)"> Order No.
                                    {{ order.reference_no }}
                                </button>
                                <!-- </li> -->
                            </ul>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-md-8">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>SL.</th>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                </tr>
                                </thead>
                                <tbody v-if="store.showproductholdorder">
                                <tr v-for="oitm in store.holdorderproduct">
                                    <td>{{ oitm.id }}</td>
                                    <td>{{ oitm.product_name }}</td>
                                    <td>{{ oitm.quantity }}</td>
                                </tr>
                                </tbody>
                                <tbody v-else>
                                No Product
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div v-if="store.showproductholdorder" id="editorderbtn" class="modal-footer">
                    <button class="btn btn-danger" type="button" @click="store.deleteholdorder(store.holdorderid)">
                        Delete
                    </button>
                    <button class="btn btn-primary" type="button" @click="store.editholdorder(store.holdorderid)">Edit
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="showkeyboardmodel" aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade mt-5"
         style="z-index:9999999999999999999" tabindex="-1">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header" style="margin-top:18px">
                    <h5 id="exampleModalLabel" class="modal-title">Keyboard Shortcut</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" style="background-color:red;"
                            type="button" @click="store.hidekeyboard()"></button>
                </div>
                <div class="modal-body" style="padding-left:0px;">
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <table class="table table-striped">
                                <tbody>
                                <tr>
                                    <th>Customer Add</th>
                                    <td>ALT + T</td>
                                </tr>
                                <tr>
                                    <th>Hold Order List</th>
                                    <td>ALT + H</td>
                                </tr>
                                <tr>
                                    <th>Keyboard Shortcut</th>
                                    <td>ALT + K</td>
                                </tr>
                                <tr>
                                    <th>Place Order</th>
                                    <td>ALT + P</td>
                                </tr>
                                <tr>
                                    <th>Order Hold</th>
                                    <td>ALT + Q</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div v-if="store.showproductholdorder" id="editorderbtn" class="modal-footer">
                    <button class="btn btn-danger" type="button" @click="store.deleteholdorder(store.holdorderid)">
                        Delete
                    </button>
                    <button class="btn btn-primary" type="button" @click="store.editholdorder(store.holdorderid)">Edit
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="topheader" class="container-fluid bg-white mt-4 rounded-t-2xl topheader" style="z-index:99999999999999">
        <div class="row header__title">
            <div class="col-md-2">
                <img id="imgnone" class="img-fluid p-3 " src="https://admin.ebitans.com/logo.png" width="170">
            </div>
            <div id="mt3" class="col-md-6 mobilenav1">
                <div class="row">
                    <div class="col-12">
                        <div class="input-group">
                            <input :value="store.searchbyname" aria-describedby="basic-addon2"
                                   aria-label="Recipient's username"
                                   class="form-control  border-0 bg-success text-dark bg-opacity-10"
                                   placeholder="Search Category or Item" type="text"
                                   @keyup="store.searchbyproductname">

                            <span id="basic-addon2" class="input-group-text rounded-3 border-0 mx-1"
                                  style="background-color:#FFBB2A;color:#fff;cursor:pointer"><font-awesome-icon
                                icon="fa-solid fa-magnifying-glass"/></span>
                            <span id="basic-addon2" class="input-group-text rounded-3 border-0"
                                  style="background-color:#FFBB2A;color:#fff;cursor:pointer" @click="store.qusearch()"><font-awesome-icon
                                icon="fa-solid fa-qrcode"/></span>
                        </div>
                    </div>
                    <div class="col-12 mx-auto">
                        <div class="input-group mobiledown1">
                            <div class="d-flex">
                                <span id="basic-addon2" class="input-group-text rounded-3 border-0"
                                      style="background-color:#FFBB2A;color:#fff;cursor:pointer"><font-awesome-icon
                                    icon="fa-solid fa-bell"/></span>
                                <span id="basic-addon2" class="input-group-text rounded-3 border-0"
                                      style="background-color:#FFBB2A;color:#fff;cursor:pointer"
                                      @click="store.showholdorder()"><font-awesome-icon
                                    icon="fa-solid fa-hand"/></span>
                                <span id="basic-addon2" class="input-group-text rounded-3 border-0"
                                      style="background-color:#FFBB2A;color:#fff;cursor:pointer"
                                      @click="store.showkeyboard()">
                                    <font-awesome-icon icon="fa-solid fa-keyboard"/>
                                </span>
                            </div>
                            <span id="basic-addon2" class="input-group-text rounded-3 border-0"
                                  style="background-color:#f1593a;color:#fff;cursor:pointer"><font-awesome-icon
                                icon="fa-solid fa-plus"/>&nbsp;<a href="/products/create"
                                                                  style="text-decoration:none;color:#fff">Add New Item</a></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 justify-content-center align-items-center desktopnav1">
                <div class="input-group">
                    <input :value="store.searchbyname" aria-describedby="basic-addon2"
                           aria-label="Recipient's username"
                           class="form-control  border-0 bg-success text-dark bg-opacity-10"
                           placeholder="Search Category or Item" type="text"
                           @keyup="store.searchbyproductname">

                    <!-- <input type="text" v-else class="form-control  border-0 bg-success text-dark bg-opacity-10" :value="store.searchbybarcode" @keyup="store.searchbyproductbarcode" placeholder="Scan Barcode" aria-label="Recipient's username" aria-describedby="basic-addon2" autofocus> -->
                    <span id="basic-addon2" class="input-group-text rounded-3 border-0 mx-1"
                          style="background-color:#FFBB2A;color:#fff;cursor:pointer"><font-awesome-icon
                        icon="fa-solid fa-magnifying-glass"/></span>

                    <span id="basic-addon2" class="input-group-text rounded-3 mx-1 border-0"
                          style="background-color:#FFBB2A;color:#fff;cursor:pointer"
                          @click="store.qusearch()"><font-awesome-icon
                        icon="fa-solid fa-qrcode"/></span>
                    <span id="basic-addon2" class="input-group-text rounded-3 mx-1 border-0"
                          style="background-color:#FFBB2A;color:#fff;cursor:pointer"><font-awesome-icon
                        icon="fa-solid fa-bell"/></span>
                    <span id="basic-addon2" class="input-group-text rounded-3 mx-1 border-0"
                          style="background-color:#FFBB2A;color:#fff;cursor:pointer" @click="store.showholdorder()"><font-awesome-icon
                        icon="fa-solid fa-hand"/></span>
                    <span id="basic-addon2" class="input-group-text rounded-3 mx-1 border-0"
                          style="background-color:#FFBB2A;color:#fff;cursor:pointer" @click="store.showkeyboard()">
                    <font-awesome-icon icon="fa-solid fa-keyboard"/>
                </span>
                    <span id="basic-addon2" class="input-group-text rounded-3 mx-1 border-0"
                          style="background-color:#f1593a;color:#fff;cursor:pointer"><font-awesome-icon
                        icon="fa-solid fa-plus"/>&nbsp;<a href="/products/create"
                                                          style="text-decoration:none;color:#fff">Add New Item</a></span>
                </div>
            </div>
            <div class="col-md-1"></div>
            <div class="c0l-md-12 mobilediv22">
                <div class="row">
                    <div class="col-6">
                        <div class="col-md-2 d-flex align-items-center">
                            <h4 class="mx-3">Order #{{ store.orderid }}</h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div v-if="store.customer"
                             class="col-md-1 d-flex align-items-center bg-success text-dark bg-opacity-10"
                             style="border-radius:0.5rem" @click="store.showaddcustomer()">
                            <img src="https://img.icons8.com/office/46/000000/test-account.png"
                                 width="50" @click="store.showaddcustomer()"/>
                            <h6 class="px-2">{{ store.customers.phone }}</h6>
                        </div>
                        <div v-else class="col-md-1 d-flex align-items-center bg-success text-dark bg-opacity-10"
                             style="border-radius:0.5rem" @click="store.showaddcustomer()">
                            <img src="https://img.icons8.com/office/46/000000/test-account.png"
                                 @click="store.showaddcustomer()"/>
                            <button class="px-2" style="border:0px; background-color: transparent;">Add Customer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 justify-content-center align-items-center desktopdiv22">
                <h4 class="mx-3">Order #{{ store.orderid }}</h4>
            </div>
            <div v-if="store.customer"
                 class="col-md-1 justify-content-center align-items-center bg-success text-dark bg-opacity-10 desktopdiv22"
                 @click="store.showaddcustomer()">
                <img src="https://img.icons8.com/office/46/000000/test-account.png" width="50"
                     @click="store.showaddcustomer()"/>
                <h6 class="px-2">{{ store.customers.phone }}</h6>
            </div>
            <div v-else
                 class="col-md-1 justify-content-center align-items-center bg-success text-dark bg-opacity-10 desktopdiv22"
                 @click="store.showaddcustomer()">
                <img src="https://img.icons8.com/office/46/000000/test-account.png" @click="store.showaddcustomer()"/>
                <button class="px-2" style="border:0px; background-color: transparent;">Add Customer</button>
            </div>
        </div>
        <!-- <ImageBarcodeReader @decode="onDecode" @error="onError"></ImageBarcodeReader> -->
    </div>
    <StreamBarcodeReader v-if="store.scantemp" @decode="store.onDecode" @loaded="onLoaded"></StreamBarcodeReader>
</template>
<script setup>
import {store} from '../store'
import {StreamBarcodeReader} from "vue-barcode-reader";
import {ImageBarcodeReader} from "vue-barcode-reader";

store.createdss();
store.getholforder();
store.getorderId();

function onDecode(result) {
    const name = result

    const appUrl = window.Laravel.appUrl || '';
    const pathID = window.location.href.replace(`${appUrl}/branch/`, "").replace("/pos", "");
    const data = {id: pathID};

    axios.post('https://admin.ebitans.com/api/v1/getcodeproduct', data).then((response) => {

        if (name == '') {

        } else {
            if (response.data.data != null) {
                store.scantemp = !store.scantemp
                console.log(response.data.data[0].vr);
                store.products = response.data.data
                if (response.data.data[0].vr == 1) {
                    store.openmodal('exampleModals224')
                    // store.openmodal(`exampleModals`+response.data.data[0].id)
                } else {
                    if (store.setcartone) {
                        store.addtocart(response.data.data[0].id);
                        store.setcartone = false
                    } else {
                        store.setcartone = true
                    }
                }
            }
        }
    })
}

store.activateObserver();
document.addEventListener("keydown", function (e) {
    if (e.altKey && (e.key === 't' || e.key === 'T')) {
        store.showaddcustomer();
        e.preventDefault();
    }
    if (e.key == "Escape") {
        store.allhidemodal();
        store.hidemodalss();
        store.cancelmodal();
        store.hidecheckout();
        store.hideholdorder();
        store.hidekeyboard();
        e.preventDefault();
    }
    if (e.altKey && (e.key === 'h' || e.key === 'H')) {
        store.showholdorder();
        e.preventDefault();
    }
    if (e.altKey && (e.key === 'k' || e.key === 'K')) {
        store.showkeyboard();
        e.preventDefault();
    }
    if (e.altKey && (e.key === 'p' || e.key === 'P')) {
        store.placeorder();
        e.preventDefault();
    }
    if (e.altKey && (e.key === 'q' || e.key === 'Q')) {
        store.holdorder();
        e.preventDefault();
    }
    if (e.altKey && (e.key === 'c' || e.key === 'c')) {
        // $('#exampleModal').modal('toggle');
        e.preventDefault();
    }
});
</script>
<style scoped>
.modal-header {
    margin-top: 36px !important;
}

.topheader {
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
}

.form-control:focus {
    z-index: 1;
    overflow: hidden;
    box-shadow: 0 0 0 0.25rem #fff !important;
}

.fade:not(.show) {
    opacity: 1 !important;
}

.holdorderlist {
    list-style: none;
    padding-left: 1rem;
}

.holdorderlist li {
    background-color: rgb(204, 200, 200);
    padding: 10px 5px;
    margin-bottom: 2px;
    border: 1px solid #fff;
    border-radius: 10px;
    cursor: pointer;
}

.ttext1 {
    min-width: 81px !important;
}

.mobilenav1 {
    display: none;
}

.desktopnav1 {
    display: flex;
}

.mobilediv22 {
    display: none;
}

.fixed {
    position: fixed;
    top: 0;
    width: 100%;
}

.desktopdiv22 {
    display: flex;
}

@media only screen and (max-width: 991px) {
    .topheader {
        margin-top: 0px !important;
    }

    .mobilenav1 {
        display: block !important;
    }

    .mobilediv22 h4 {
        font-size: 17px;
        margin-left: 0px !important;
    }

    .mobilediv22 img {
        width: 36px !important;
    }

    .desktopnav1 {
        display: none !important;
    }

    .divradius {
        border-radius: 20px;
    }

    .mobiledown1 {
        margin-top: 15px;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between !important;
    }

    .input-group-text {
        margin-left: 2px;
        margin-right: 2px;
    }

    .mobilediv22 {
        display: block;
        margin-top: 10px;
        padding-bottom: 10px;
    }

    .desktopdiv22 {
        display: none !important;
    }

    .input-group {
        justify-content: center;
    }
}
</style>
