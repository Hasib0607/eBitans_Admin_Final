<template>
    <div class="bg-white p-2 mt-2 rounded-4">
        <div class="d-flex justify-content-between align-items-center px-2 py-2">
            <p class="mb-0">New Order Bill</p>
            <p class="mb-0">Sunday, 28 October, 2022</p>            
        </div>
        <hr>
        <Cart/>
    </div>
</template>
<script>
import Cart from './Cart.vue';
    export default{ components: { Cart } }
</script>