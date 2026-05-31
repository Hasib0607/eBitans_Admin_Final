<template>
    <div :class="isActive ? 'section text-center active' : 'section text-center'" @click="setActive(data.mouth)">
        <div v-if="save > 0" class="discount mx-auto">
            SAVE {{ save }} {{ regionCurrency }}
        </div>
        <div :style="save > 0 ? {} : { marginTop: '15px' }" class="element text-center mx-auto">
            <div class="form-check">
                <input
                    :id="'package-form'+data.mouth"
                    :checked="isChecked"
                    :value="data.mouth"
                    class="form-check-input"
                    name="package"
                    type="radio"
                    @change="updateSelected(data.mouth)"
                >
                <label :for="'package-form'+data.mouth" class="form-check-label">
                    {{ data.mouth == 12 ? "1 YEAR" : data.mouth + " MONTH" }}
                </label>
            </div>
            <div class="price">
                {{ discount_price }}
            </div>
            <div class="time">{{ regionCurrency }} / <span v-if="data.mouth == 12">yearly</span><span
                v-else>month</span></div>
            <div class="description">
                Renewal fee will be the same until further notice
            </div>
        </div>
        <div v-show="data.mouth === 12" class="setup mx-auto">
            free website setup
        </div>
    </div>
</template>

<script>
export default {
    name: 'package-options-element',
    data() {
        return {
            package: ''
        }
    },
    props: ['plan', 'data', 'selected', 'visitor'],
    mounted() {
        this.package = this.data.package
        this.data.package === 'twelvedis' ?
            this.visitor && this.visitor.countryCode !== 'BD' ?
                this.package = 'usd_12_dis' :
                this.package = 'twelvedis' :
            this.visitor && this.visitor.countryCode !== 'BD' ?
                this.package = 'usd_1_dis' :
                this.package = 'onedis'
    },
    computed: {
        isActive() {
            return this.selected === this.data.mouth;
        },
        discount_price() {
            return this.visitor && this.visitor.countryCode !== 'BD' ?
                ((this.plan.usd_price - this.save) * this.data.mouth).toFixed(2) :
                Math.round((this.plan.price - this.save) * this.data.mouth);
        },
        save() {
            return this.visitor && this.visitor.countryCode !== 'BD' ?
                this.plan.usd_price * this.plan[this.package] / 100 ?? 0 :
                this.plan.price * this.plan[this.package] / 100 ?? 0;
        },
        regionCurrency() {
            return this.visitor && this.visitor.countryCode !== 'BD' ? 'USD' : 'BDT'
        },
        isChecked() {
            return this.selected === this.data.mouth;
        }
    },
    methods: {
        setActive(mouth) {
            this.$emit('update:selected', mouth); // Emit the selected mouth value to the parent
            this.$emit('selectPlan', this.plan.id); // Emit the selected mouth value to the parent
        },
        updateSelected(mouth) {
            this.$emit('update:selected', mouth);
        }
    }
};
</script>


<style>
.section.active .discount, .section.active .setup {
    color: white;
    background-color: #F1593A;
}

.section.active .element {
    border: 1px solid #F1593A;
}

.discount {
    color: #442721;
    background-color: #e9d9d9;
    font-size: 14px;
    font-weight: bold;
    padding: 6px 12px;
    border-radius: 20px;
    height: 30px;
    width: 141px;
    position: relative;
}

.element {
    height: 270px;
    width: 205px;
    padding-left: 20px;
    padding-right: 20px;
    background-color: #FCEEEE;
    margin-top: -15px;
    border-radius: 7px;
}

.element .form-check {
    padding: 0px;
    padding-top: 30px;
}

.element .form-check .form-check-label {
    color: #000000;
    font-size: 16px;
    font-weight: bold;
}

.element .price {
    padding-top: 15px;
    font-size: 48px;
    color: #442721;
    font-weight: bold;
}

.element .time {
    font-size: 16px;
    color: #9e8782;
    font-weight: bold;
}

.element .description {
    font-size: 14px;
    color: #a89895
}

.setup {
    font-size: 14px;
    color: #442721;
    background-color: #E9D8D8;
    border-radius: 0 0 5px 5px;
    height: 24px;
    width: 147px;
}
</style>
