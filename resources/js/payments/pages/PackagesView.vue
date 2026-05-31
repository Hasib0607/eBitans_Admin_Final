<template>
    <div class="main-sections card mt-3">
        <div class="header text-center">
            <div class="title">
                You are almost there! Complete your order
            </div>
            <div class="sub-title">
                Selected Plan: <span>{{plan.name}}</span>
            </div>
        </div>
        <div class="body container">
            <package-feature :details="plan.details"/>
            <package-price-option :plan="plan" :visitor="visitor"/>
        </div>
    </div>
</template>

<script>
import PackagePriceOption from "../components/PackagePriceOptions.vue";
import PackageFeature from "../components/PackagesFeature.vue";

export default {
    name: 'package-view',
    components: {
        PackageFeature,
        PackagePriceOption,
    },
    props: {
        plan: {
            type: Object,
            required: true
        },
        plans: {
            type: Object,
            required: true
        },
        visitor: {
            type: Object,
            required: true
        },
    },

}
</script>

<style scoped>
.main-sections {
    padding: 10px;
    padding-top: 20px;
    width: 100%;
}

.main-sections .header {
    color: #442721;
}

.main-sections .header .title {
    font-size: 28px;
    font-weight: bold;
}

.main-sections .header .sub-title {
    font-size: 16px;
    font-weight: normal;
}

.main-sections .header .sub-title span {
    color: #F1593A;
    font-weight: bolder;
}

.main-sections .body {
    width: 100%;
    padding-top: 30px;
}




</style>
