<template>
    <div class="main container-fluid">
        <div class="row">
            <div class="col-md-5 scroll-container">
                <div ref="scrollContainer" class="row itemsOfElements mt-3" tabindex="0" @mousedown="focusContainer"
                    @mouseenter="focusContainer" @wheel="handleScroll">
                    <addon-card v-for="(item, index) in addons" :key="index" :item="item" :visitor="visitor"
                        :posPlan="item.id == '13' ? pos : []" @itemData="addNew"
                        @removeItem="removeAddonPackage(item)" />
                </div>
            </div>
            <div class="col-md-7 mt-3 right-section">
                <div class="card container-fluid my-auto p-3">
                    <div class="row my-auto">
                        <div class="col-md-10 col-sm-8 my-auto">
                            <input v-model="couponCode" aria-label=".form-control-sm example"
                                class="form-control form-control-sm" placeholder="Apply Coupons" type="text">
                        </div>
                        <div class="col-md-2 col-sm-4 my-auto">
                            <button class="btn btn-primary btn-sm" @click="handleSendCoupon">APPLY</button>
                        </div>
                    </div>
                </div>

                <div class="card mt-3 invoice">
                    <div class="card-header d-flex justify-content-between">
                        <h6>Order Details</h6>
                        <div>
                            <h6>Date</h6>
                            <p>{{ todayDate }}</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6>Package Name</h6>
                        <div v-for="(item, index) in packages" :key="index"
                            class="item d-flex justify-content-between align-items-center">
                            <div style="display: flex; gap:10px;align-items: start;">
                                <div v-if="item.type && item.type === 'package'" class="item-header mt-3">
                                    Package: {{ item.name }}
                                    <p>{{ item.month }} Month <span class="text-danger" style="cursor: pointer"
                                            @click="removePackage(index)">remove</span></p>
                                </div>
                                <div v-else class="item-header mt-3">
                                    Addons: {{ item.title }}
                                    <p v-if="item.type.toLowerCase() === 'counter'">{{ item.name }} <span
                                            class="text-danger" style="cursor: pointer"
                                            @click="removePackage(index)">remove</span>
                                    </p>
                                    <p v-else>{{ item.monthorvalue }} Month <span class="text-danger"
                                            style="cursor: pointer" @click="removePackage(index)">remove</span>
                                    </p>
                                </div>
                                <select class="form-select form-select-sm py-0" @click.stop=""
                                    @change.stop="handleActiveStatus($event, index)"
                                    style="width: 65%;height: 30px;margin-top: 17px;">
                                    <option value="0">Active Later</option>
                                    <option value="1">Active Now</option>
                                </select>
                            </div>
                            <div v-if="visitor && visitor.countryCode !== 'BD'" class="item-price">
                                USD {{ item.usd_offer_price > 0 ? item.usd_offer_price : item.usd_price }}
                            </div>
                            <div v-else class="item-price">
                                BDT {{ item.offerprice > 0 ? item.offerprice : item.price }}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="item d-flex justify-content-between align-items-center">
                            <div class="item-header mt-3">
                                SUBTOTAL
                            </div>
                            <div class="item-price">
                                <span v-if="visitor && visitor.countryCode !== 'BD'">USD</span> <span v-else>BDT</span>
                                {{ subtotal }}
                            </div>
                        </div>
                        <div class="item d-flex justify-content-between mt-2 align-items-center">
                            <div class="item-header mt-2">
                                Coupon Discount
                            </div>
                            <div class="item-price">
                                <span v-if="visitor && visitor.countryCode !== 'BD'">USD</span>
                                <span v-else>BDT</span>
                                {{ discount }}
                            </div>
                        </div>

                        <div v-if="manual_discount > 0"
                            class="item d-flex justify-content-between mt-2 align-items-center">
                            <div class="item-header mt-2">
                                Manual Discount
                            </div>
                            <div class="item-price">
                                <span v-if="visitor && visitor.countryCode !== 'BD'">USD</span>
                                <span v-else>BDT</span>
                                {{ manual_discount }}
                            </div>
                        </div>
                        <div v-if="lateFee > 0" class="item d-flex justify-content-between mt-2 align-items-center">
                            <div class="item-header mt-2">
                                Late Fee
                                <p style="margin:0;">
                                    {{ lateFeeDays }} days overdue
                                </p>
                            </div>
                            <div class="item-price">
                                <span v-if="visitor && visitor.countryCode !== 'BD'">USD</span>
                                <span v-else>BDT</span>
                                {{ lateFee }}
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="item d-flex justify-content-between align-items-center">
                            <div class="item-header mt-3">
                                TOTAL
                            </div>
                            <div class="item-price">
                                <span v-if="visitor && visitor.countryCode !== 'BD'">USD</span> <span v-else>BDT</span>
                                {{ payableTotal }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-check form-check-info text-start ps-0 mt-3">
                    <label class="form-check-label" for="flexCheckDefault">
                        I have read and agree with the eBitans <a href="https://www.ebitans.com.bd/terms-and-conditions"
                            target="_blank" class="text-dark font-weight-bolder">Terms &
                            Conditions</a>, <a href="https://www.ebitans.com.bd/privacy-policy" target="_blank"
                            class="text-dark font-weight-bolder">Privacy Policy</a> and <a
                            href="https://www.ebitans.com.bd/return-and-refund-policy" target="_blank"
                            class="text-dark font-weight-bolder">Refund Policy</a>

                    </label>
                </div>
                <div class="card mt-3 button-section p-3">
                    <div class="d-flex justify-content-end my-auto">
                        <button v-if="visitor && visitor.countryCode !== 'BD'" class="btn btn-primary btn-sm mx-2"
                            @click='sendAddonsOrderWithPayPal'>PayPal
                        </button>
                        <button class="btn btn-primary btn-sm mx-2" @click="sendAddonsOrderWithBkash">Bkash</button>
                        <!--                        <button class="btn btn-primary btn-sm mx-2" @click="sendAddonsOrderWithAmarPay">Card Payment-->
                        <!--                        </button>-->
                        <button class="btn btn-primary btn-sm mx-2" @click="sendAddonsOrderWithNagad">Nagad</button>

                        <button v-if="userType === 'superadmin' || userType === 'superstaff'"
                            class="btn btn-primary btn-sm ml-2" data-bs-target="#manualPaymentModal"
                            data-bs-toggle="modal">
                            Manual
                        </button>
                    </div>
                </div>

                <div v-if="canShowDueOrders" class="card mt-3 p-3 due-orders-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Existing Due Payments</h6>
                        <span v-if="openDueOrders.length" class="badge bg-gradient-warning text-dark">
                            {{ openDueOrders.length }} Open
                        </span>
                    </div>

                    <div v-for="order in openDueOrders" :key="order.id" class="due-order-row">
                        <div class="due-order-main">
                            <div class="due-order-title">{{ order.order_no || `EBI-${order.id}` }}</div>
                            <div class="due-order-meta">
                                Total: {{ formatCurrency(order.total) }} | Paid: {{ formatCurrency(order.paid_amount) }} | Due: {{ formatCurrency(order.due_amount) }}
                            </div>
                            <div class="due-order-meta">
                                Status: {{ formatDueStatus(order.due_amount_status) }} | Updated: {{ formatShortDate(order.updated_at) }}
                            </div>
                        </div>
                        <div class="due-order-actions">
                            <a :href="getOrderInvoiceUrl(order)" target="_blank" class="btn btn-outline-primary btn-sm">
                                Invoice
                            </a>
                            <button class="btn btn-warning btn-sm" type="button" @click="openDueUpdateModal(order)">
                                Update Due
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div id="manualPaymentModal" aria-hidden="true" aria-labelledby="nagadPaymentModalLabel"
                    class="modal fade" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <table class="table table-striped mb-0" width="100%">
                                    <tbody>
                                        <tr>
                                            <th style="text-align:start">Payable Amount</th>
                                            <td>
                                                <span v-if="visitor && visitor.countryCode !== 'BD'">USD</span>
                                                <span v-else>BDT</span>
                                                {{ payableAmount }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">

                                        <!-- Payment Method -->
                                        <div>
                                            <label>Payment Method</label>
                                            <select class="form-control" v-model="payment_method">
                                                <option value="bkash_manual">Bkash</option>
                                                <option value="nagad_manual">Nagad</option>
                                                <option value="rocket_manual">Rocket</option>
                                                <option value="bank_transfer">Bank Transfer</option>
                                                <option value="hand_cash">Hand Cash</option>
                                                <option value="due">Due</option>
                                            </select>
                                        </div>

                                        <!-- Payment Type -->
                                        <div class="mt-3">
                                            <label>Payment Type</label>

                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="radio" id="payment_type_full"
                                                    name="payment_type" value="full" v-model="payment_type">
                                                <label class="form-check-label" for="payment_type_full">
                                                    Full Payment
                                                </label>
                                            </div>

                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="radio" id="payment_type_partial"
                                                    name="payment_type" value="partial" v-model="payment_type">
                                                <label class="form-check-label" for="payment_type_partial">
                                                    Partial Payment
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Paid Amount -->
                                        <div v-if="payment_type === 'partial'" class="mt-3">
                                            <label>Paid Amount</label>
                                            <input v-model.number="paid_amount" type="number" class="form-control"
                                                min="0" :max="payableAmount" placeholder="Enter paid amount">
                                        </div>

                                        <!-- Due Amount -->
                                        <div v-if="payment_type === 'partial'" class="mt-3">
                                            <label>Due Amount</label>
                                            <input class="form-control" :value="dueAmount" readonly>
                                        </div>

                                        <!-- Manual Discount -->
                                        <div class="mt-3">
                                            <label>Manual Discount Amount</label>
                                            <input v-model.number="manual_discount" class="form-control" type="number"
                                                min="0" placeholder="Enter discount amount">
                                        </div>

                                        <!-- Bank Name -->
                                        <div v-if="payment_method === 'bank_transfer'" class="mt-3">
                                            <label>Bank Name</label>
                                            <input v-model="bank_name" class="form-control" type="text"
                                                placeholder="Enter bank name">
                                        </div>

                                        <!-- Account Number -->
                                        <div v-if="payment_method === 'bank_transfer'" class="mt-3">
                                            <label>Account Number</label>
                                            <input v-model="account_number" class="form-control" type="text"
                                                placeholder="Enter account number">
                                        </div>

                                        <!-- Mobile Number -->
                                        <div v-if="payment_method !== 'hand_cash' && payment_method !== 'bank_transfer' && payment_method !== 'due'"
                                            class="mt-3">
                                            <label>Payment Number</label>
                                            <input v-model="phone" class="form-control" type="text"
                                                placeholder="Enter number">
                                        </div>

                                        <!-- Transaction ID -->
                                        <div v-if="payment_method !== 'hand_cash' && payment_method !== 'due'"
                                            class="mt-3">
                                            <label>Transaction ID</label>
                                            <input v-model="transaction" class="form-control" type="text"
                                                placeholder="Enter transaction id">
                                        </div>

                                        <!-- Comment -->
                                        <div class="mt-3">
                                            <label>Comment</label>
                                            <textarea v-model="manual_discount_comment" class="form-control" rows="3"
                                                placeholder="Write reason / note"></textarea>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>

                                <button class="btn" v-if="nagadLoading"
                                    style="width: 160px; display: flex; justify-content: center; align-items: center; background: #f1593a; color: #fff;">
                                    <svg aria-hidden="true" role="status"
                                        class="inline w-14 h-14 me-3 text-white animate-spin" viewBox="0 0 100 101"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                            fill="#E5E7EB" />
                                        <path
                                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                            fill="currentColor" />
                                    </svg>
                                    Processing...
                                </button>

                                <button v-else class="btn btn-primary" :disabled="nagadLoading" type="button"
                                    @click="sendAddonsOrderWithManual()">
                                    Send
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="existingDuePaymentModal" aria-hidden="true" class="modal fade" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <table class="table table-striped mb-0" width="100%">
                                    <tbody>
                                        <tr>
                                            <th style="text-align:start">Order</th>
                                            <td>{{ selectedDueOrder ? (selectedDueOrder.order_no || `EBI-${selectedDueOrder.id}`) : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th style="text-align:start">Current Due</th>
                                            <td>{{ formatCurrency(selectedDueOrder ? selectedDueOrder.due_amount : 0) }}</td>
                                        </tr>
                                        <tr>
                                            <th style="text-align:start">Total Paid</th>
                                            <td>{{ formatCurrency(selectedDueOrder ? selectedDueOrder.paid_amount : 0) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div>
                                            <label>Payment Method</label>
                                            <select class="form-control" v-model="dueUpdateForm.payment_method">
                                                <option value="bkash">Bkash</option>
                                                <option value="nagad">Nagad</option>
                                                <option value="rocket">Rocket</option>
                                                <option value="bank_transfer">Bank Transfer</option>
                                                <option value="hand_cash">Hand Cash</option>
                                                <option value="due">Due</option>
                                                <option value="paypal">PayPal</option>
                                                <option value="amarpay">AmarPay</option>
                                            </select>
                                        </div>

                                        <div class="mt-3">
                                            <label>Additional Paid Amount</label>
                                            <input v-model.number="dueUpdateForm.additional_paid_amount" type="number"
                                                class="form-control" min="0"
                                                :max="selectedDueOrder ? selectedDueOrder.due_amount : 0"
                                                placeholder="Enter paid amount">
                                        </div>

                                        <div class="mt-3">
                                            <label>Remaining Due</label>
                                            <input class="form-control" :value="remainingDuePreview" readonly>
                                        </div>

                                        <div v-if="dueUpdateForm.payment_method === 'bank_transfer'" class="mt-3">
                                            <label>Bank Name</label>
                                            <input v-model="dueUpdateForm.bank_name" class="form-control" type="text"
                                                placeholder="Enter bank name">
                                        </div>

                                        <div v-if="dueUpdateForm.payment_method === 'bank_transfer'" class="mt-3">
                                            <label>Account Number</label>
                                            <input v-model="dueUpdateForm.account_number" class="form-control" type="text"
                                                placeholder="Enter account number">
                                        </div>

                                        <div v-if="dueUpdateForm.payment_method !== 'hand_cash' && dueUpdateForm.payment_method !== 'bank_transfer' && dueUpdateForm.payment_method !== 'due'"
                                            class="mt-3">
                                            <label>Payment Number</label>
                                            <input v-model="dueUpdateForm.payment_number" class="form-control" type="text"
                                                placeholder="Enter number">
                                        </div>

                                        <div v-if="dueUpdateForm.payment_method !== 'hand_cash' && dueUpdateForm.payment_method !== 'due'"
                                            class="mt-3">
                                            <label>Transaction ID</label>
                                            <input v-model="dueUpdateForm.transaction_id" class="form-control" type="text"
                                                placeholder="Enter transaction id">
                                        </div>

                                        <div class="mt-3">
                                            <label>Comment</label>
                                            <textarea v-model="dueUpdateForm.note" class="form-control" rows="3"
                                                placeholder="Write note"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                                <button v-if="dueUpdateLoading" class="btn btn-warning" type="button">
                                    Processing...
                                </button>
                                <button v-else class="btn btn-warning" type="button" @click="submitExistingDueUpdate">
                                    Save Payment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import addonCard from '../components/addonCard.vue'
import axios from "axios";
import moment from 'moment';
import { number } from "tailwindcss/lib/util/dataTypes";
import eventBus from "../../eventBus";
import { createLogger } from "vuex";

export default {
    name: 'addons',
    data() {
        return {
            couponCode: "",
            packages: [],
            discount: 0,
            manual_discount: 0,
            manual_discount_comment: '',
            bank_name: '',
            account_number: '',
            paid_amount: 0,
            payment_type: 'full',
            phone: '',
            transaction: '',
            nagadLoading: false,
            activeTime: 0,
            payment_method: 'bkash_manual',
            openDueOrders: [],
            selectedDueOrder: null,
            dueUpdateLoading: false,
            dueUpdateForm: {
                additional_paid_amount: 0,
                payment_method: 'bkash',
                payment_number: '',
                transaction_id: '',
                bank_name: '',
                account_number: '',
                note: '',
            }
        }
    },
    props: {
        userType: {
            type: String,
            default: ''
        },
        addons: {
            type: Array,
            required: true
        },
        visitor: {
            type: Object,
            default: () => ({})  // Provide a default empty object
        },
        plan: {
            type: [Object, Array],
            required: false,
            default: () => []
        },
        pos: {
            type: Array,
            required: true
        },
        //for late fee
        lateFee: {
            type: Number,
            default: 0
        },
        lateFeeDays: {
            type: Number,
            default: 0
        },
        lateFeeReason: {
            type: [String, null],
            default: null
        },
        dueOrders: {
            type: Array,
            default: () => []
        },
    },
    components: {
        addonCard
    },
    mounted() {
        if (this.plan && Object.keys(this.plan).length) {
            const {
                id,
                name,
                month,
                price,
                discount_type,
                onedis,
                twelvedis,
                usd_1_dis,
                usd_12_dis,
                usd_price,
                usd_discount_type
            } = this.plan;

            // Determine the correct price based on the visitor's country code
            let _price;
            let _usd_price;


            // If visitor is not from BD, use USD pricing
            if (usd_discount_type === 'percent') {
                if (month == "12") {
                    _usd_price = usd_price - (usd_price * usd_12_dis / 100);
                } else {
                    _usd_price = usd_price - (usd_price * usd_1_dis / 100);
                }
            } else {
                if (month == "12") {
                    _usd_price = usd_price - usd_12_dis;
                } else {
                    _usd_price = usd_price - usd_1_dis;
                }
            }
            _usd_price = (_usd_price * month).toFixed(2);


            // If visitor is from BD, use BDT pricing
            if (discount_type === 'percent') {
                if (month == "12") {
                    _price = price - (price * twelvedis / 100);
                } else {
                    _price = price - (price * onedis / 100);
                }
            } else {
                if (month == "12") {
                    _price = price - twelvedis;
                } else {
                    _price = price - onedis;
                }
            }

            _price = Math.round(_price * month);


            // Ensure the plan is properly pushed into the packages array
            this.packages.push({
                id,
                name,
                month,
                type: 'package', // Ensure the type is set as 'package'
                price: _price,   // Calculated price based on conditions
                usd_price: _usd_price, // Add usd_price for possible future use
                usd_offer_price: usd_price ? _usd_price : 0, // Set usd_offer_price if applicable
                offerprice: price ? _price : 0, // Set offerprice if applicable
                activeTime: this.activeTime || 0,
            });
        }

        this.openDueOrders = Array.isArray(this.dueOrders)
            ? this.dueOrders.filter((order) => Number(order?.due_amount || 0) > 0)
            : [];
    },
    methods: {
        resetAllChildren() {
            eventBus.emit('resetActive');
        },
        focusContainer() {
            this.$refs.scrollContainer.focus();
        },
        handleScroll(event) {
            const container = this.$refs.scrollContainer;
            container.scrollTop += event.deltaY;
        },
        addNew(data) {
            const isIndex = this.packages.findIndex(item => item.type === data.type && item.id === data.id);

            if (isIndex >= 0) {
                // Update the existing item
                this.packages[isIndex] = { ...data };
            } else {
                // Add the new item
                this.packages.push({ ...data });
            }
            this.discount !== 0 && this.handleSendCoupon();
        },
        removePackage(index) {
            const item = this.packages[index];
            this.packages.splice(index, 1);
            eventBus.emit('removePackage', item.id);
        },
        removeAddonPackage(data) {
            const isIndex = this.packages.findIndex(item => item.type === data.type && item.id === data.id);
            if (isIndex >= 0) {
                this.packages.splice(isIndex, 1);
            }
        },
        resetState() {
            this.couponCode = "";
            this.packages = [];
            this.discount = 0;
            this.manual_discount = 0;
            this.manual_discount_comment = '';
            this.bank_name = '';
            this.account_number = '';
            this.paid_amount = 0;
            this.phone = '';
            this.transaction = '';
            this.payment_method = 'bkash_manual';
            this.payment_type = 'full';
        },
        formatCurrency(value) {
            const amount = Number(value || 0).toFixed(2);
            if (this.visitor && this.visitor.countryCode !== 'BD') {
                return `USD ${amount}`;
            }
            return `BDT ${amount}`;
        },
        formatShortDate(date) {
            return moment(date).format('DD MMM YYYY, h:mm A');
        },
        formatDueStatus(status) {
            if (!status) return '';
            return status
                .split('_')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
        },
        getOrderInvoiceUrl(order) {
            if (!order) {
                return '#';
            }

            const paymentHistories = Array.isArray(order.payment_histories)
                ? order.payment_histories
                : (Array.isArray(order.paymentHistories) ? order.paymentHistories : []);

            const latestHistoryId = paymentHistories.length
                ? paymentHistories[0].id
                : null;

            if (latestHistoryId) {
                return `/admin/payment/payments/invoice/${order.id}?payment_history_id=${latestHistoryId}`;
            }

            return `/admin/payment/payments/invoice/${order.id}`;
        },
        getExistingDueModal() {
            const modalElement = document.getElementById('existingDuePaymentModal');
            if (!modalElement || typeof bootstrap === 'undefined') {
                return null;
            }

            return bootstrap.Modal.getOrCreateInstance(modalElement);
        },
        openDueUpdateModal(order) {
            this.selectedDueOrder = { ...order };
            this.dueUpdateForm = {
                additional_paid_amount: 0,
                payment_method: order.payment_method || 'bkash',
                payment_number: order.payment_number || '',
                transaction_id: '',
                bank_name: order.bank_name || '',
                account_number: order.account_number || '',
                note: '',
            };

            const modal = this.getExistingDueModal();
            if (modal) {
                modal.show();
            }
        },
        replaceDueOrder(updatedOrder) {
            const index = this.openDueOrders.findIndex(item => Number(item.id) === Number(updatedOrder.id));
            if (index >= 0) {
                this.openDueOrders[index] = {
                    ...this.openDueOrders[index],
                    ...updatedOrder,
                };

                if (Number(this.openDueOrders[index].due_amount || 0) <= 0) {
                    this.openDueOrders.splice(index, 1);
                }
            }
        },
        async submitExistingDueUpdate() {
            if (!this.selectedDueOrder || !this.selectedDueOrder.id) {
                return false;
            }

            const currentDue = Number((this.selectedDueOrder && this.selectedDueOrder.due_amount) || 0);
            const additionalPaid = Number(this.dueUpdateForm.additional_paid_amount || 0);

            if (additionalPaid <= 0) {
                swal.fire({
                    title: 'Error',
                    text: 'Please enter additional paid amount',
                    type: 'error'
                });
                return false;
            }

            if (additionalPaid > currentDue) {
                swal.fire({
                    title: 'Error',
                    text: 'Additional paid amount cannot be greater than current due amount',
                    type: 'error'
                });
                return false;
            }

            if (this.dueUpdateForm.payment_method === 'bank_transfer' && this.dueUpdateForm.bank_name === '') {
                swal.fire({
                    title: 'Error',
                    text: 'Please enter bank name',
                    type: 'error'
                });
                return false;
            }

            if (this.dueUpdateForm.payment_method === 'bank_transfer' && this.dueUpdateForm.account_number === '') {
                swal.fire({
                    title: 'Error',
                    text: 'Please enter account number',
                    type: 'error'
                });
                return false;
            }

            if (
                this.dueUpdateForm.payment_method !== 'hand_cash' &&
                this.dueUpdateForm.payment_method !== 'due' &&
                this.dueUpdateForm.payment_method !== 'bank_transfer' &&
                (this.dueUpdateForm.payment_number === '' || this.dueUpdateForm.transaction_id === '')
            ) {
                swal.fire({
                    title: 'Error',
                    text: 'Please input payment number and transaction ID',
                    type: 'error'
                });
                return false;
            }

            if (this.dueUpdateForm.payment_method === 'bank_transfer' && this.dueUpdateForm.transaction_id === '') {
                swal.fire({
                    title: 'Error',
                    text: 'Please input transaction ID',
                    type: 'error'
                });
                return false;
            }

            try {
                this.dueUpdateLoading = true;
                const response = await axios.post(`/admin/payment/payments/${this.selectedDueOrder.id}/update-due`, this.dueUpdateForm);

                if (response?.data?.status) {
                    const updatedOrder = response?.data?.order || {};
                    this.replaceDueOrder(updatedOrder);

                    const modal = this.getExistingDueModal();
                    if (modal) {
                        modal.hide();
                    }

                    swal.fire({
                        title: 'Success',
                        text: response?.data?.message || 'Due payment updated successfully.',
                        type: 'success'
                    });

                    if (response?.data?.invoice_url) {
                        window.open(response.data.invoice_url, '_blank');
                    }
                } else {
                    swal.fire({
                        title: 'Error',
                        text: response?.data?.message || 'Could not update due payment.',
                        type: 'error'
                    });
                }
            } catch (err) {
                swal.fire({
                    title: 'Error',
                    text: err?.response?.data?.message || 'Something went wrong. Please try again.',
                    type: 'error'
                });
            } finally {
                this.dueUpdateLoading = false;
            }
        },
        handleActiveStatus(event, index) {
            event.stopPropagation();
            this.packages[index].activeTime = event.target.value || 0;
        },
        getDisCountPrice(regular_price, discount_price, discount_type) {
            if (discount_type === 'percent') {
                return Math.floor(parseFloat(discount_price) / 100 * parseFloat(regular_price));
            } else if (discount_type === 'fixed') {
                return discount_price;
            } else if (discount_type === 'no_discount') {
                return 0;
            }
        },
        async handleSendCoupon() {
            const _discount = await this.getCoupon(this.couponCode, this.subtotal);
            this.discount = _discount;
        },
        async getCoupon(code, subtotal) {
            let _discount = 0;
            try {
                const response = await axios.post('/admin/get-coupon', {
                    code,
                    subtotal
                });

                let regular_price = this.subtotal;
                let discount_price = response.data?.discount_amount;
                let discount_type = response.data?.discount_type;

                _discount = this.getDisCountPrice(regular_price, discount_price, discount_type) || 0;
            } catch (err) {
                _discount = 0;
            }
            return _discount;
        },
        async sendAddonsOrderWithBkash() {
            this.sendAddonsOrder("bkash");
        },
        async sendAddonsOrderWithPayPal() {
            this.sendAddonsOrder("paypal");

        },
        async sendAddonsOrderWithAmarPay() {
            this.sendAddonsOrder("amarpay");
        },
        async sendAddonsOrderWithNagad() {
            this.sendAddonsOrder("nagad");
        },
        async sendAddonsOrder(payment_method = "") {
            const month = this.plan?.month ?? 0;
            const data = {
                code: this.couponCode,
                addons: this.packages,
                subtotal: this.subtotal,
                discount: this.discount,
                plan_id: this.plan?.id,
                month: month,
                payment_method: payment_method,
                late_fee: this.lateFee,
            }
            if (payment_method == "") {
                swal.fire({
                    title: 'Error',
                    text: "Invalid payment method!",
                    type: 'error'
                })

                return false;
            }
            if (this.subtotal <= 0) {
                swal.fire({
                    title: 'Error',
                    text: "Payable amount can't be zero!",
                    type: 'error'
                })

                return false;
            }
            try {
                const response = await axios.post('/admin/save-addons-order', data);
                window.location = response.data.url;
            } catch (err) {
            }
        },
        async sendAddonsOrderWithManual() {
            if (this.payment_method === "") {
                swal.fire({
                    title: 'Error',
                    text: "Please select a payment method",
                    type: 'error'
                });
                return false;
            }

            if (this.subtotal <= 0) {
                swal.fire({
                    title: 'Error',
                    text: "Payable amount can't be zero!",
                    type: 'error'
                });
                return false;
            }

            if (this.manual_discount < 0) {
                swal.fire({
                    title: 'Error',
                    text: "Manual discount cannot be negative",
                    type: 'error'
                });
                return false;
            }

            if (this.manual_discount > (this.subtotal - this.discount)) {
                swal.fire({
                    title: 'Error',
                    text: "Manual discount cannot exceed payable amount before late fee",
                    type: 'error'
                });
                return false;
            }

            if (this.payment_method === 'bank_transfer' && this.bank_name === '') {
                swal.fire({
                    title: 'Error',
                    text: "Please enter bank name",
                    type: 'error'
                });
                return false;
            }

            if (this.payment_method === 'bank_transfer' && this.account_number === '') {
                swal.fire({
                    title: 'Error',
                    text: "Please enter account number",
                    type: 'error'
                });
                return false;
            }

            if (
                this.payment_method !== "hand_cash" &&
                this.payment_method !== "due" &&
                this.payment_method !== "bank_transfer" &&
                (this.phone === "" || this.transaction === "")
            ) {
                swal.fire({
                    title: 'Error',
                    text: "Please input payment number and transaction ID",
                    type: 'error'
                });
                return false;
            }

            if (this.payment_method === "bank_transfer" && this.transaction === "") {
                swal.fire({
                    title: 'Error',
                    text: "Please input transaction ID",
                    type: 'error'
                });
                return false;
            }

            if (this.payment_type === 'partial') {
                if (!this.paid_amount || this.paid_amount <= 0) {
                    swal.fire({
                        title: 'Error',
                        text: "Please enter paid amount",
                        type: 'error'
                    });
                    return false;
                }

                if (this.paid_amount > this.payableAmount) {
                    swal.fire({
                        title: 'Error',
                        text: "Paid amount cannot be greater than payable amount",
                        type: 'error'
                    });
                    return false;
                }
            }

            if (
                (this.manual_discount > 0 || this.payment_method === 'due' || this.payment_type === 'partial') &&
                this.manual_discount_comment === ''
            ) {
                swal.fire({
                    title: 'Error',
                    text: "Please write a comment / reason",
                    type: 'error'
                });
                return false;
            }

            const month = this.plan?.month ?? 0;

            const data = {
                code: this.couponCode,
                addons: this.packages,
                subtotal: this.subtotal,
                discount: this.discount,
                manual_discount: this.manual_discount,
                manual_discount_comment: this.manual_discount_comment,
                bank_name: this.bank_name,
                account_number: this.account_number,
                paid_amount: this.paid_amount,
                due_amount: this.dueAmount,
                plan_id: this.plan?.id,
                month: month,
                phone: this.phone,
                transaction: this.transaction,
                payment_method: this.payment_method,
                payment_type: this.payment_type,
                late_fee: this.lateFee,
            };

            try {
                this.nagadLoading = true;
                const response = await axios.post('/admin/buy-addons-manual', data);

                if (response?.data?.status) {
                    this.nagadLoading = false;
                    this.resetState();

                    const modalElement = document.getElementById('manualPaymentModal');
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    this.resetAllChildren();

                    if (modal) {
                        modal.hide();
                        swal.fire({
                            title: 'Success',
                            text: response?.data?.message,
                            type: 'success',
                        }).then((result) => {
                            if (result.value) {
                                window.location = '/admin/payment/payments';
                            }
                        });
                    }
                } else {
                    this.nagadLoading = false;
                    swal.fire({
                        title: 'Error',
                        text: response?.data?.message,
                        type: 'error',
                    });
                }
            } catch (err) {
                this.nagadLoading = false;
                swal.fire({
                    title: 'Error',
                    text: "Something went wrong. Please try again.",
                    type: 'error',
                });
            }
        }
    },
    computed: {
        subtotal() {
            return this.packages.reduce((prev, current) => {
                let value = 0;
                if (this.visitor && this.visitor.countryCode !== 'BD') {
                    value = current.usd_offer_price > 0 ? current.usd_offer_price : current.usd_price;
                } else {
                    value = current.offerprice > 0 ? current.offerprice : current.price;
                }
                return prev + parseFloat(value);
            }, 0);
        },

        payableAmount() {
            const sub = parseFloat(this.subtotal) || 0;
            const couponDiscount = parseFloat(this.discount) || 0;
            const manualDiscount = parseFloat(this.manual_discount) || 0;
            const late = parseFloat(this.lateFee) || 0;

            const total = (sub - couponDiscount - manualDiscount) + late;
            return total > 0 ? total : 0;
        },

        dueAmount() {
            if (this.payment_type !== 'partial') {
                return 0;
            }

            const payable = parseFloat(this.payableAmount) || 0;
            const paid = parseFloat(this.paid_amount) || 0;
            const due = payable - paid;

            return due > 0 ? due : 0;
        },

        payableTotal() {
            return this.payableAmount;
        },

        canShowDueOrders() {
            return (this.userType === 'superadmin' || this.userType === 'superstaff')
                && this.openDueOrders.length > 0;
        },

        remainingDuePreview() {
            const currentDue = Number((this.selectedDueOrder && this.selectedDueOrder.due_amount) || 0);
            const additionalPaid = Number(this.dueUpdateForm.additional_paid_amount || 0);
            const remaining = currentDue - additionalPaid;

            return remaining > 0 ? remaining : 0;
        },

        todayDate() {
            let date = new Date().toJSON();
            return moment(date).format('MMMM Do YYYY');
        }
    }
}
</script>

<style scoped>
.itemsOfElements {
    height: 65vh;
    overflow-y: scroll;
    /* Ensure vertical scrolling */
    padding: 10px;
    box-sizing: border-box;
    outline: none;
}

/* Custom Scrollbar for WebKit Browsers (e.g., Chrome, Safari) */
.itemsOfElements::-webkit-scrollbar {
    width: 8px;
    /* Vertical scrollbar width */
}

.itemsOfElements::-webkit-scrollbar-track {
    background: #f1d0c9;
    border-radius: 10px;
}

.itemsOfElements::-webkit-scrollbar-thumb {
    background: #dd8d7c;
    border-radius: 10px;
    border: 2px solid transparent;
    background-clip: padding-box;
}

.itemsOfElements::-webkit-scrollbar-thumb:hover {
    background: #f1593a;
}

.invoice.card .card-body,
.invoice.card .card-footer {
    border-top: 1px solid #d8d8d8;
}


.item .item-header {
    font-size: 14px;
    font-weight: bold;
    color: #000000;
}

.item .item-header p {
    font-size: 10px;
    text-align: left;
    color: #c1c4cb;
}

.item .item-price {
    font-size: 15px;
    font-weight: bolder;
    color: black;
}

.invoice div div p {
    font-size: 11px;
    font-weight: bold;
    text-align: right;
}

.invoice.card .card-header {
    padding-bottom: 0;
    margin-bottom: 0;
}

.right-section div div input {
    background-color: #F8F3F2;
    padding: 5px !important;
}

button.btn.btn-primary.btn-sm {
    border-radius: 3px;
    margin-bottom: 0;
}

.main {
    margin-top: 10px
}

.right-section .card div div button {
    width: 100%;
}

.right-section .card {
    border-radius: 5px;
}

.due-orders-card {
    border-radius: 8px;
    margin-bottom: 0;
}

.due-order-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
    border-top: 1px solid #f1d0c9;
}

.due-order-row:first-of-type {
    border-top: 0;
}

.due-order-main {
    flex: 1;
    min-width: 0;
}

.due-order-title {
    font-size: 14px;
    font-weight: 700;
    color: #000;
}

.due-order-meta {
    font-size: 12px;
    color: #6c757d;
    margin-top: 2px;
    word-break: break-word;
}

.due-order-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

@media (max-width: 767px) {
    .due-order-row {
        flex-direction: column;
        align-items: flex-start;
    }

    .due-order-actions {
        width: 100%;
        justify-content: flex-start;
    }
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
