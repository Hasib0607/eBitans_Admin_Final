<template>
    <div class="d-flex align-items-center justify-content-between">
        <p class="fw-bold mb-0">Subtotal</p>
        <p class="mb-0">৳{{ subtotal }}</p>
    </div>
    <div class="d-flex align-items-center justify-content-between">
        <p class="fw-bold mb-0">Tax</p>
        <p class="mb-0">৳{{ tax }}</p>
    </div>
    <div class="d-flex align-items-center justify-content-between">
        <p class="fw-bold mb-0">Discount</p>
        <!-- <input type="number" v-model="discount" name="discount" class="form-control" style="width:100px"> -->
        <p class="mb-0">৳{{ discount }}</p>
    </div>
    <hr>
    <div class="d-flex align-items-center justify-content-between">
        <p class="fw-bold mb-0">Total</p>
        <p class="mb-0 text-success fw-bold">৳{{ total }}</p>
    </div>
</template>
<script setup>
defineProps(['subtotal', 'total', 'discount', 'tax'])
</script>
