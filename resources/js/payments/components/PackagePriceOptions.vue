<template>
    <div class="container my-3">
        <div class="title text-center">
            Choose a period
        </div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-3 mb-4" v-if="plan.id !== 8">
                <package-options-element
                    v-if="plan.id !== 8"
                    :data="{mouth: 1, package: 'onedis'}"
                    :plan="plan"
                    :visitor="visitor"
                    :selected="selected"
                    @update:selected="updateSelected"
                    @selectPlan="selectedPlan"
                />
            </div>
            <div class="col-md-3 mb-4">
                <package-options-element
                    :data="{mouth: 12, package: 'twelvedis'}"
                    :plan="plan"
                    :visitor="visitor"
                    :selected="selected"
                    @update:selected="updateSelected"
                    @selectPlan="selectedPlan"
                />
            </div>
            <div class="col-md-3"></div>
        </div>
        <div class="footer container-lg d-flex gap-3 justify-content-between">
            <a class="btn btn-primary"
               :href="'/admin/payment/packages'">Back</a>
            <a class="btn btn-primary" :href="'/admin/payment/addons?plan='+selectPlan+'&month='+selected">Next</a>
        </div>
    </div>
</template>

<script>
import PackageOptionsElement from "./packageOptionElement.vue";

export default {
    name: 'package-price-option',
    components: {PackageOptionsElement},
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
    data() {
        return {
            selected: 12,
            selectPlan: this.plan.id
        };
    },
    methods: {
        updateSelected(newValue) {
            this.selected = newValue; // Update the selected value
        },
        selectedPlan(newValue) {
            this.selectPlan = newValue; // Update the selected value
        }
    }
}
</script>


<style scoped>
.container .title {
    color: #F1593A;
    font-size: 22px;
    margin-bottom: 15px;
    font-weight: bolder;
}
</style>
