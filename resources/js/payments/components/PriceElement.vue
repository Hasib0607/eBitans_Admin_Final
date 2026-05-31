<template>
    <div class="main-elements" @click="navigateTo('packages/'+plan.id)">
        <div class="header text-center">
            <div class="title">
                {{ plan.name }}
            </div>
            <div class="sub-title">Expand your business</div>
            <div class="offer">
                <span class="text-decoration-line-through">
                    <span v-if="plan[package] > 0">
                        <span v-if="visitor && visitor.countryCode !== 'BD'">USD</span> <span v-else>BDT</span>
                        {{ visitor && visitor.countryCode !== 'BD' ? plan.usd_price ?? 0 : plan.price }}
                    </span>
                    <button>save {{
                            plan[package]
                        }}{{
                            plan.discount_type == "percent" ? '%' : visitor && visitor.countryCode !== 'BD' ? BDT : USD
                        }}</button>
                </span>
            </div>
        </div>
        <hr>
        <div class="body">
            <ul v-if="plan.details && plan.details.length">
                <li v-for="(detail, index) in plan.details" :key="index">
                    <span v-if="detail?.type === 'package'">
                        <i aria-hidden="true" class="fa fa-check"></i>
                        {{ detail.title }}
                    </span>
                </li>
            </ul>
        </div>

        <div class="footer">
            <span style="font-size: 18px;">
                Select This Package
            </span>
        </div>

    </div>
</template>

<script>
export default {
    name: 'price_element',
    data() {
        return {
            package: 'twelvedis'
        }
    },
    props: {
        plan: {
            type: Object,
            required: true
        },
        visitor: {
            type: Object,
            required: true
        },
    },
    computed: {
        discount_price() {
            let price = this.visitor && this.visitor.countryCode !== 'BD' ?
                this.plan.usd_price ?? 0 : this.plan.price;
            return price - (price * this.plan[this.package] / 100);
        },
    },
    methods: {
        navigateTo(page) {
            window.location.href = page;
        },
    },
    mounted() {
        this.visitor && this.visitor.countryCode !== 'BD' ?
            this.package = 'usd_12_dis' :
            this.package = 'twelvedis'
    }
}

</script>

<style scoped>
.main-elements {
    position: relative;
    margin: 0;
    color: #212123;
    width: 100%;
    min-width: 0;
    border-radius: 4px;
    background-color: #FCF6F4;
    padding: 5px;
    cursor: pointer;
    min-height: 100%;
}

.main-elements .header {
    padding-top: 15px;
}

.main-elements .header .title {
    font-size: 25px;
}

.main-elements .header .sub-title {
    font-size: 13px;
    color: #6D6262;
}

.main-elements .header .offer {
    font-size: 10px;
    color: #6D6262;
}

.main-elements .header .offer span button {
    margin-left: 10px;
    padding: 0 6px;
    font-size: 10px;
    color: #F1593A;
    background-color: #F1D0C9;
    border: 1px solid #DD8D7C;
    border-radius: 3px;
}

.main-elements .header .offer span button:hover {
    background-color: #ecad9f;
}

.main-elements hr {
    color: #DD8D7C;
    margin: 10px;
}

.main-elements .body {
    padding: 15px;
    font-size: 13px;
    display: flex;
    justify-content: left;
    margin-bottom: 40px;
}

.main-elements .body ul {
    list-style-type: none;
    padding: 0;
}

.main-elements .body ul li i {
    font-weight: 100;
    color: #15A034;
    margin-right: 10px;
}

.main-elements .footer {
    position: absolute;
    bottom: 5px;
    background-color: #F1593A;
    color: white;
    width: calc(100% - 10px);
    height: 58px;
    border-radius: 8px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 25px;
}

.main-elements .footer:hover {
    background-color: #f44622;
    color: white;
}

.main-elements .footer span span {
    font-size: 13px;
    padding: 0 3px;
}

.main-elements .footer span span.top {
    vertical-align: text-top;
}
</style>
