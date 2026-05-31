<template>
    <div class="main-sections card mt-3">
        <div class="header"> Packages</div>
        <div class="body">
            <div class="packages-grid">
                <price_elements v-for="(plan, index) in plans" :key="index" :plan="plan" :visitor="visitor"></price_elements>
            </div>
        </div>
    </div>
</template>

<script>
import price_elements from '../components/PriceElement.vue'

export default {
    name: 'packages',
    components: {
        price_elements
    },
    props: {
        plans: {
            type: Array,
            required: true
        },
        visitor: {
            type: Object,
            required: true
        },
    }
}
</script>

<style>
.moving-tab{
    display: none !important;
}
</style>
<style scoped>

.nav.nav-pills .nav-link.active {
    -webkit-animation: 0.2s ease;
    color: #F1D0C9;
    animation: 0.2s ease;
    background: #F1593A;
}
.main-sections {
    padding: 10px;
    padding-top: 20px;
    width: 100%;
    overflow: hidden;
}

/* Custom Scrollbar for WebKit Browsers (e.g., Chrome, Safari) */
::-webkit-scrollbar {
    height: 8px;
}

::-webkit-scrollbar-track {
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #DD8D7C;
    border-radius: 10px;
    border: 2px solid transparent;
    background-clip: padding-box;
}

::-webkit-scrollbar-thumb:hover {
    background: #F1593A;
    border-radius: 10px;
}

/* Mozilla Firefox Scrollbar Styling */
.scroll-container {
    scrollbar-color: #DD8D7C #F1D0C9; /* thumb color | track color */
    scrollbar-width: thin; /* Thin scrollbar */
}

.main-sections .header {
    display: flex;
    justify-content: center;
    color: #F1593A;
    font-size: 20px;
    padding: 15px;
    font-weight: bolder;
}

.main-sections .body {
    width: 100%;
    overflow: hidden;
}

.packages-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    align-items: stretch;
}

@media (max-width: 1399px) {
    .packages-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

@media (max-width: 991px) {
    .packages-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 575px) {
    .packages-grid {
        grid-template-columns: 1fr;
    }
}
</style>
