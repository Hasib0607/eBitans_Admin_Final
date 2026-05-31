<template>
    <div class="col-md-6 mb-3 px-2">
        <div :class="['card addons-elements ', active ? 'active': '']" @click="handleSend(selectKey,true)">
            <img :alt="`addons image`" :src="`/addons/${item.image}`"/>
            <div class="badge-overlay">
                <span v-if="item.type === 'counter'" class="top-left badge primary">Custom</span>
                <span v-else-if="item.type === 'monthly'" class="top-left badge third">Monthly</span>
                <span v-else class="top-left badge secondary">Once</span>
            </div>
            <div class="d-flex justify-content-between align-items-center h-100 mt-3">
                <div>
                    <div class="card-title">
                        {{ item.title }}
                    </div>
                    <div v-if="item.id == 13">
                        <div class="card-sub-title" v-if="visitor && visitor.countryCode !== 'BD'">
                            USD {{ posUSDDiscountPrice != "0" ? posUSDDiscountPrice : '' }}
                            <span v-if="posUSDPrice">BDT {{ posUSDPrice }}</span>
                        </div>
                        <div class="card-sub-title" v-else>
                            BDT {{ posDiscountPrice != "0" ? posDiscountPrice : '' }}
                            <span v-if="posPrice">BDT {{ posPrice }}</span>
                        </div>
                    </div>
                    <div v-else>
                        <div class="card-sub-title" v-if="visitor && visitor.countryCode !== 'BD'">
                            USD
                            {{
                                item.usd_offer_price ? (JSON.parse(item.usd_offer_price)[selectKey] ?? JSON.parse(item.usd_price)[selectKey]) : 0
                            }}
                            <span>USD {{ item.usd_price && (JSON.parse(item.usd_price)[selectKey] ?? 0) }}</span>
                        </div>
                        <div class="card-sub-title" v-else>
                            BDT {{
                                item.offerprice ? (JSON.parse(item.price[selectKey]) ?? JSON.parse(item.price[selectKey])) : 0
                            }}
                            <span v-if="item.offerprice[selectKey]">BDT {{ item.price[selectKey] || 0 }}</span>
                        </div>
                    </div>
                </div>
                <button v-if="item.type === 'oneTime'" class="btn btn-primary btn-sm my-auto">Buy Now</button>
                <select v-else class="form-select form-select-sm py-0" @click.stop=""
                        @change.stop="handleChange($event)" style="width: 30%">
                    <option v-for="(option, i) in getMonthOrValue(item)" :key="i" :value="i">
                        {{ getSelectValue(item, option) }}
                    </option>
                </select>

                <select v-if="item.id == 13" class="form-select form-select-sm py-0 ml-1" @click.stop=""
                        @change.stop="handleChangePos($event)" style="width: 30%">
                    <option v-for="(option, j) in posPlan" :key="j" :value="option.id">
                        {{ option.name }}
                    </option>
                </select>
            </div>
        </div>
    </div>

    <div v-if="item.title.toLowerCase() === 'domain'" id="domainModal" aria-hidden="true"
         aria-labelledby="domainModalLabel"
         class="modal fade "
         tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Domain Buying Information</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="">
                                <label>Domain</label>
                                <br>
                                <input v-model="domain" @input="convertDomainToLowercase" class="form-control"
                                       name="domain" type="text" required>
                            </div>
                            <div class="mt-3">
                                <label>Email</label>
                                <input id="email" v-model="email" class="form-control" required
                                       type="text">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                    <button class="btn btn-primary" type="button" @click="addDomainInfo()">Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import eventBus from "../../eventBus";

export default {
    name: 'addons_card',
    data() {
        return {
            selectKey: 0,
            active: false,
            email: '',
            domain: '',
            posID: 1,
            posPrice: 0,
            posDiscountPrice: 0,
            posUSDPrice: 0,
            posUSDDiscountPrice: 0,
        }
    },
    props: {
        item: {
            type: Object,
            required: true
        },
        visitor: {
            type: Object,
            default: () => ({})  // Provide a default empty object
        },
        posPlan: {
            type: Array,
            required: true
        },
    },
    emits: ['itemData', 'removeItem'],
    mounted() {
        eventBus.on('resetActive', this.resetActive);
        eventBus.on('removePackage', this.removePackage);
    },
    beforeDestroy() {
        eventBus.off('resetActive', this.resetActive);
        eventBus.off('removePackage', this.removePackage);
    },
    methods: {
        resetActive() {
            this.active = false;  // Reset the active state
        },
        getMonthOrValue(item) {
            return item.type.toLowerCase() === 'counter' ? item.name : item.monthorvalue;
        },
        getSelectValue(item, option) {
            return item.type.toLowerCase() === 'monthly' ? option + " Month" : option;
        },
        handleChange(event) {
            event.stopPropagation();
            this.selectKey = event.target.value;
            this.calculatePosPrice();
            this.handleSend(this.selectKey, false);
        },
        handleChangePos(event) {
            event.stopPropagation();
            this.posID = event.target.value || 1;
            this.calculatePosPrice();
            this.handleSend(this.selectKey, false);
        },
        handleSend(index, status) {
            if (status && this.active) {
                this.active = false;
                this.$emit('removeItem');
                if (this.item.title.toLowerCase() === "domain") {
                    this.domain = "";
                    this.email = "";
                }
            } else {
                this.active = true;
                if (this.item.title.toLowerCase() === "domain") {
                    const modalElement = document.getElementById('domainModal');
                    if (modalElement) {
                        const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                        modal.show();
                    }
                }
                this.addItem(index);
            }
        },
        addItem(index) {
            const {id, offerprice, price, usd_offer_price, usd_price, name, monthorvalue, type} = this.item;
            let newPrice = price ? price[index] : 0;
            let newUSDPrice = usd_price ? JSON.parse(usd_price)[index] : 0;
            let newOfferprice = offerprice && offerprice[index] !== '' ? offerprice[index] : 0;
            let newUsd_offer_price = usd_offer_price && usd_offer_price[index] !== '' ? JSON.parse(usd_offer_price)[index] : 0;

            let posID = null;
            if (id == 13) {
                newPrice = this.posPrice;
                newUSDPrice = this.posUSDPrice;
                newOfferprice = this.posDiscountPrice;
                newUsd_offer_price = this.posUSDDiscountPrice;
                posID = this.posID;
            }

            this.$emit('itemData', {
                ...this.item,
                name: name && name[index] !== '' ? name[index] : "unknown name",
                monthorvalue: monthorvalue && monthorvalue[index] !== '' ? monthorvalue[index] : 0,
                price: newPrice,
                usd_price: newUSDPrice,
                offerprice: newOfferprice,
                usd_offer_price: newUsd_offer_price,
                months: type === 'monthly' ? monthorvalue && monthorvalue[index] !== '' ? monthorvalue[index] : 0 : null,
                quantity: type === 'counter' ? monthorvalue && monthorvalue[index] !== '' ? parseFloat(monthorvalue[index]) : 0 : 1,
                posID: posID,
                domain: this.domain,
                email: this.email,
                activeTime: 0
            });
        },
        convertDomainToLowercase() {
            const cursorPosition = event.target.selectionStart;  // Get current cursor position
            this.domain = this.domain.toLowerCase(); // Convert the domain to lowercase
            this.$nextTick(() => {
                event.target.setSelectionRange(cursorPosition, cursorPosition); // Set cursor position back
            });
        },
        addDomainInfo() {
            if (this.domain == "" || this.email == "") {
                swal.fire({
                    title: 'Error',
                    text: "Please input Domain name and email address",
                    type: 'error'
                })

                return false;
            }

            let modal = null;
            const modalElement = document.getElementById('domainModal');
            if (modalElement) {
                modal = bootstrap.Modal.getInstance(modalElement);
            }

            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to save the changes?",
                showCancelButton: true,
                confirmButtonText: "Yes",
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    this.addItem(this.selectKey);

                    if (modal) {
                        modal.hide();
                    }
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire("Changes are not saved", "", "info");
                }
            });
        },
        removePackage(id) {
            if (this.item.id === id) {
                this.active = false;
            }
        },
        calculatePosPrice() {
            const plan = this.posPlan.find(item => item.id == this.posID) || {};

            const {monthorvalue} = this.item;
            const month = monthorvalue && monthorvalue[this.selectKey] !== '' ? parseInt(monthorvalue[this.selectKey]) : 1;
            const price = plan?.price && plan?.price !== "" ? parseFloat(plan?.price) : 0;
            const usdPrice = plan?.usd_price && plan?.usd_price !== "" ? parseFloat(plan?.usd_price) : 0;
            const discount_type = plan?.discount_type || "";
            const onedis = plan?.onedis || 0;
            const sixdis = plan?.sixdis || 0;
            const twelvedis = plan?.twelvedis || 0;
            const twentyfourdis = plan?.twentyfourdis || 0;

            let discount = 0;
            if (month == 1) {
                discount = onedis;
            } else if (month == 6) {
                discount = sixdis;
            } else if (month == 12) {
                discount = twelvedis;
            } else if (month == 24) {
                discount = twentyfourdis;
            } else {
                discount = 0;
            }

            const discount_price = this.calculateDiscountPrice(discount_type, price, discount);
            const discount_price_usd = this.calculateDiscountPrice(discount_type, usdPrice, discount);

            const totalprice = (month * price);
            const totalUSDprice = (month * usdPrice);

            const totalDiscountPrice = totalprice - discount_price;
            const totalUSDDiscountPrice = totalUSDprice - discount_price_usd;

            this.posPrice = totalprice;
            this.posUSDPrice = totalUSDprice;
            this.posDiscountPrice = totalDiscountPrice;
            this.posUSDDiscountPrice = totalUSDDiscountPrice;

        },
        calculateDiscountPrice(type, price, discount) {
            if (type == "percent") {
                return Math.round(((price * discount) / 100));
            } else if (type == "fixed") {
                return Math.round(price - discount);
            }

            return Math.round(price);
        }
    },
    watch: {
        // Watch for changes in posPlan
        posPlan: {
            immediate: true, // Run the function immediately when the component is mounted
            handler(newVal) {
                if (newVal && newVal.length > 0) {
                    this.calculatePosPrice(); // Call the function when posPlan is received
                }
            },
        },
    }
}
</script>


<style scoped>
select.form-select.form-select-sm {
    border-color: #D2D6DA;
}

.addons-elements {
    cursor: pointer;
}


.card.addons-elements {
    border-radius: 5px;
    padding: 10px;
}

.card.addons-elements div img {
    width: 100%;
}

.addons-elements div .card-title {
    padding-bottom: 0;
    margin-bottom: 0;
    font-size: 13px;
    font-weight: bold;
    color: #442721;
}

.addons-elements div .card-sub-title {
    font-size: 15px;
    font-weight: bolder;
    color: #F1593A;
}

.addons-elements div div .card-sub-title span {
    font-size: 10px;
    text-decoration: line-through;
    color: #C9BFBF;
    display: block;
}

.badge-overlay {
    position: absolute;
    left: 0%;
    top: 0px;
    width: 100%;
    height: 100%;
    overflow: hidden;
    pointer-events: none;
    z-index: 100;
    -webkit-transition: width 1s ease, height 1s ease;
    -moz-transition: width 1s ease, height 1s ease;
    -o-transition: width 1s ease, height 1s ease;
    transition: width 0.4s ease, height 0.4s ease
}

.badge {
    margin: 0;
    color: white;
    padding: 7px 10px;
    font-size: 8px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
    line-height: normal;
    text-transform: uppercase;
    background: #F1593A;
    border-radius: 0 !important;
}

.badge::before, .badge::after {
    content: '';
    position: absolute;
    top: 0;
    margin: 0 -1px;
    width: 100%;
    height: 100%;
    background: inherit;
    min-width: 55px
}

.badge::before {
    right: 100%
}

.badge::after {
    left: 100%
}

.top-left {
    position: absolute;
    top: 0;
    left: 0;
    -ms-transform: translateX(0%) translateY(150%) rotate(-45deg);;
    -webkit-transform: translateX(0%) translateY(150%) rotate(-45deg);;
    transform: translateX(0%) translateY(150%) rotate(-45deg);
    -ms-transform-origin: top left;
    -webkit-transform-origin: top left;
    transform-origin: top left;
}

.badge.primary {
    background: #F1593A;
}

.badge.secondary {
    background: #9e7eff;
}

.badge.third {
    background: #7EB2FF;
}

.card.addons-elements.active {
    border: 2px solid #f55938;
}

.ml-1 {
    margin-left: 3px;
}
</style>
