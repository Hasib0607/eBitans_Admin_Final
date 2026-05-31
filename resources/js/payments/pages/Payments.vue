<template>
    <div class="main-section px-3">
        <div v-for="(order, index) in localOrders" :key="index" class="card">
            <div class="row p-3" v-if="order.modulus_id">
                <div class="item col my-auto">
                    <div class="pt-title bold">EBI-{{ order?.id }}</div>
                    <div class="subtitle">{{ formatDate(order.updated_at) }}</div>
                </div>
                <div class=" col my-auto">
                    <div class="pt-title">Modulus</div>
                    <div class="subtitle">{{ order?.modulus_name }}</div>
                </div>
                <div class="col my-auto">
                    <div class="pt-title bold">{{ order.price }} BDT</div>
                    <div class="subtitle">with Discount & Tax</div>
                </div>
                <div class="item col my-auto">
                    <div class="pt-title">Payment Type</div>
                    <div class="subtitle">{{ formatPaymentType(order.payment_type) }}</div>
                </div>
                <div class="col my-auto text-center d-flex justify-content-center mt-2">
                    <button
                        :class="order.status === 'Complete'
                            ? 'btn btn-sm complete-status '
                            : order.status === 'Processing'
                                ? 'btn btn-sm process-status'
                                : 'btn btn-sm failed-status'"
                    >{{ order.status }}
                    </button>
                </div>
                <div class="col my-auto text-center d-flex justify-content-center mt-2"></div>
            </div>

            <div class="row p-3" v-else>
                <div class="item col my-auto">
                    <div class="pt-title bold" v-if="order?.order_no">{{ order?.order_no }}</div>
                    <div class="pt-title bold" v-else>EBI-{{ order?.id }}</div>
                    <div class="subtitle">{{ formatDate(order.updated_at) }}</div>
                </div>

                <div class="col my-auto">
                    <div class="pt-title">Package</div>
                    <div class="subtitle" v-if="order.addons">
                        {{ order.name }} <span v-for="(item, i) in order.addons" :key="i">{{ item.name }},</span>
                    </div>
                </div>

                <div class="col my-auto">
                    <div class="pt-title bold">{{ order.total }} {{ order.code }}</div>
                    <div class="subtitle">with Discount & Tax</div>
                    <div v-if="hasManualSummary(order)" class="payment-summary mt-1">
                        <div>Paid: {{ formatAmount(order.paid_amount) }}</div>
                        <div :class="Number(order.due_amount || 0) > 0 ? 'due-text' : 'cleared-text'">
                            Due: {{ formatAmount(order.due_amount) }}
                        </div>
                        <div>{{ formatDueStatus(order.due_amount_status) }}</div>
                    </div>
                </div>

                <div class="item col my-auto">
                    <div class="pt-title">Payment Type</div>
                    <div class="subtitle">{{ formatPaymentType(order.payment_method) }}</div>
                </div>

                <div class="col my-auto text-center d-flex justify-content-center mt-2">
                    <button
                        :class="order.status === 'Complete'
                            ? 'btn btn-sm complete-status '
                            : order.status === 'Processing'
                                ? 'btn btn-sm process-status'
                                : 'btn btn-sm failed-status'"
                    >{{ order.status }}
                    </button>
                </div>

                <div class="col my-auto text-center d-flex justify-content-center mt-2 gap-2 actions-wrap">
                    <a :href="`/admin/payment/payments/invoice/${order.id}`" target="_blank" class="btn btn-primary">
                        View
                    </a>
                    <button
                        v-if="canUpdateDue(order)"
                        type="button"
                        class="btn btn-warning"
                        @click="openUpdateModal(order)"
                    >
                        Update Due
                    </button>
                </div>
            </div>
        </div>

        <div id="updateDueModal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <table class="table table-striped mb-0" width="100%">
                            <tbody>
                                <tr>
                                    <th style="text-align:start">Current Due</th>
                                    <td>{{ formatAmount(selectedOrder?.due_amount) }}</td>
                                </tr>
                                <tr>
                                    <th style="text-align:start">Total Paid</th>
                                    <td>{{ formatAmount(selectedOrder?.paid_amount) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div>
                                    <label>Payment Method</label>
                                    <select class="form-control" v-model="updateForm.payment_method">
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
                                    <input
                                        v-model.number="updateForm.additional_paid_amount"
                                        type="number"
                                        class="form-control"
                                        min="0"
                                        :max="selectedOrder?.due_amount || 0"
                                        placeholder="Enter paid amount"
                                    >
                                </div>

                                <div class="mt-3">
                                    <label>Remaining Due</label>
                                    <input class="form-control" :value="calculatedRemainingDue" readonly>
                                </div>

                                <div v-if="updateForm.payment_method === 'bank_transfer'" class="mt-3">
                                    <label>Bank Name</label>
                                    <input
                                        v-model="updateForm.bank_name"
                                        class="form-control"
                                        type="text"
                                        placeholder="Enter bank name"
                                    >
                                </div>

                                <div v-if="updateForm.payment_method === 'bank_transfer'" class="mt-3">
                                    <label>Account Number</label>
                                    <input
                                        v-model="updateForm.account_number"
                                        class="form-control"
                                        type="text"
                                        placeholder="Enter account number"
                                    >
                                </div>

                                <div
                                    v-if="updateForm.payment_method !== 'hand_cash' && updateForm.payment_method !== 'bank_transfer' && updateForm.payment_method !== 'due'"
                                    class="mt-3"
                                >
                                    <label>Payment Number</label>
                                    <input
                                        v-model="updateForm.payment_number"
                                        class="form-control"
                                        type="text"
                                        placeholder="Enter number"
                                    >
                                </div>

                                <div
                                    v-if="updateForm.payment_method !== 'hand_cash' && updateForm.payment_method !== 'due'"
                                    class="mt-3"
                                >
                                    <label>Transaction ID</label>
                                    <input
                                        v-model="updateForm.transaction_id"
                                        class="form-control"
                                        type="text"
                                        placeholder="Enter transaction id"
                                    >
                                </div>

                                <div class="mt-3">
                                    <label>Comment</label>
                                    <textarea
                                        v-model="updateForm.note"
                                        class="form-control"
                                        rows="3"
                                        placeholder="Write note"
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                        <button
                            v-if="updateLoading"
                            class="btn btn-warning"
                            style="min-width: 160px;"
                            type="button"
                        >
                            Processing...
                        </button>
                        <button
                            v-else
                            class="btn btn-warning"
                            type="button"
                            @click="submitDueUpdate"
                        >
                            Save Payment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';

export default {
    name: 'payments',
    props: ['orders'],
    data() {
        return {
            localOrders: [],
            selectedOrder: null,
            updateLoading: false,
            updateModalInstance: null,
            updateForm: {
                additional_paid_amount: 0,
                payment_method: 'bkash',
                payment_number: '',
                transaction_id: '',
                bank_name: '',
                account_number: '',
                note: '',
            },
        };
    },
    computed: {
        calculatedRemainingDue() {
            const currentDue = Number(this.selectedOrder?.due_amount || 0);
            const additionalPaid = Number(this.updateForm.additional_paid_amount || 0);
            const remainingDue = currentDue - additionalPaid;

            return remainingDue > 0 ? remainingDue : 0;
        },
    },
    mounted() {
        this.localOrders = Array.isArray(this.orders) ? [...this.orders] : [];
    },
    methods: {
        formatDate(date) {
            return moment(date).format('MMMM Do YYYY, h:mm a');
        },
        formatPaymentType(type) {
            if (!type) return '';
            return type
                .split('_')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
        },
        formatDueStatus(status) {
            if (!status) return '';
            return status
                .split('_')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
        },
        formatAmount(amount) {
            return Number(amount || 0).toFixed(2);
        },
        hasManualSummary(order) {
            return order && (
                order.paid_amount !== null
                || order.due_amount !== null
                || order.due_amount_status
            );
        },
        canUpdateDue(order) {
            return order
                && !order.modulus_id
                && order.status === 'Complete'
                && Number(order.due_amount || 0) > 0;
        },
        getModalInstance() {
            const modalElement = document.getElementById('updateDueModal');
            if (!modalElement || typeof bootstrap === 'undefined') {
                return null;
            }

            if (!this.updateModalInstance) {
                this.updateModalInstance = new bootstrap.Modal(modalElement);
            }

            return this.updateModalInstance;
        },
        resetUpdateForm() {
            this.updateForm = {
                additional_paid_amount: 0,
                payment_method: this.selectedOrder?.payment_method || 'bkash',
                payment_number: this.selectedOrder?.payment_number || '',
                transaction_id: '',
                bank_name: this.selectedOrder?.bank_name || '',
                account_number: this.selectedOrder?.account_number || '',
                note: '',
            };
        },
        openUpdateModal(order) {
            this.selectedOrder = { ...order };
            this.resetUpdateForm();

            const modal = this.getModalInstance();
            if (modal) {
                modal.show();
            }
        },
        replaceOrderInList(updatedOrder) {
            const index = this.localOrders.findIndex(item => Number(item.id) === Number(updatedOrder.id));
            if (index >= 0) {
                this.localOrders[index] = {
                    ...this.localOrders[index],
                    ...updatedOrder,
                };
            }
        },
        async submitDueUpdate() {
            if (!this.selectedOrder?.id) {
                return;
            }

            const currentDue = Number(this.selectedOrder?.due_amount || 0);
            const additionalPaidAmount = Number(this.updateForm.additional_paid_amount || 0);

            if (additionalPaidAmount <= 0) {
                swal.fire({
                    title: 'Error',
                    text: 'Please enter additional paid amount',
                    type: 'error',
                });
                return;
            }

            if (additionalPaidAmount > currentDue) {
                swal.fire({
                    title: 'Error',
                    text: 'Additional paid amount cannot be greater than current due amount',
                    type: 'error',
                });
                return;
            }

            if (this.updateForm.payment_method === 'bank_transfer' && this.updateForm.bank_name === '') {
                swal.fire({
                    title: 'Error',
                    text: 'Please enter bank name',
                    type: 'error',
                });
                return;
            }

            if (this.updateForm.payment_method === 'bank_transfer' && this.updateForm.account_number === '') {
                swal.fire({
                    title: 'Error',
                    text: 'Please enter account number',
                    type: 'error',
                });
                return;
            }

            if (
                this.updateForm.payment_method !== 'hand_cash'
                && this.updateForm.payment_method !== 'due'
                && this.updateForm.payment_method !== 'bank_transfer'
                && (this.updateForm.payment_number === '' || this.updateForm.transaction_id === '')
            ) {
                swal.fire({
                    title: 'Error',
                    text: 'Please input payment number and transaction ID',
                    type: 'error',
                });
                return;
            }

            if (
                this.updateForm.payment_method === 'bank_transfer'
                && this.updateForm.transaction_id === ''
            ) {
                swal.fire({
                    title: 'Error',
                    text: 'Please input transaction ID',
                    type: 'error',
                });
                return;
            }

            try {
                this.updateLoading = true;

                const response = await axios.post(
                    `/admin/payment/payments/${this.selectedOrder.id}/update-due`,
                    this.updateForm
                );

                if (response?.data?.status) {
                    const updatedOrder = response?.data?.order || {};

                    this.selectedOrder = {
                        ...this.selectedOrder,
                        ...updatedOrder,
                    };
                    this.replaceOrderInList(this.selectedOrder);

                    const modal = this.getModalInstance();
                    if (modal) {
                        modal.hide();
                    }

                    swal.fire({
                        title: 'Success',
                        text: response?.data?.message || 'Due payment updated successfully.',
                        type: 'success',
                    });
                } else {
                    swal.fire({
                        title: 'Error',
                        text: response?.data?.message || 'Could not update due payment.',
                        type: 'error',
                    });
                }
            } catch (err) {
                const message = err?.response?.data?.message || 'Something went wrong. Please try again.';

                swal.fire({
                    title: 'Error',
                    text: message,
                    type: 'error',
                });
            } finally {
                this.updateLoading = false;
            }
        },
    },
};
</script>

<style scoped>
.main-section {
    margin-top: 20px;
}

div.main-section.px-3 div.card {
    margin-bottom: 1px;
}

.card {
    border-radius: 2px;
    padding: 10px;
}

.pt-title {
    font-size: 14px;
    font-weight: bolder;
    color: #000000;
}

.pt-title.bold {
    font-weight: bold;
}

.subtitle {
    font-size: 10px;
    font-weight: bold;
    color: #b6b6b6;
}

.payment-summary {
    font-size: 11px;
    font-weight: 600;
    color: #6c757d;
    line-height: 1.5;
}

.due-text {
    color: #dc3545;
}

.cleared-text {
    color: #198754;
}

button.btn.btn-sm {
    padding-left: 30px;
    padding-right: 30px;
    border-radius: 3px;
    margin-bottom: 0;
}

.btn.complete-status {
    background: #c3f3be;
    color: #737373;
}

.btn.failed-status {
    background: #f3bebe;
    color: #737373;
}

.btn.process-status {
    background: #BED3F3;
    color: #737373;
}

button.btn.btn-sm:hover {
    background: #b0cbf3;
}

.actions-wrap {
    flex-wrap: wrap;
}
</style>
