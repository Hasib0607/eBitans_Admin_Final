<template>
        <div class="col-md-2 mb-3">
            <div class="card card-body text-center bg-white">
                <img v-bind:src="'https://admin.ebitans.com/assets/images/product/' + image" height="150" />
                
                <span class="mt-1">{{name}}</span>
                <h5>{{ promotional_price }}</h5> jkjhjhk
                <h7>৳ {{price}}</h7>
                <a @click="addtocart(id)" class="btn btn-outline-danger mt-2 w-50 mx-auto">Add</a>
            </div>
        </div>   
    </template>
<script setup>
import {nextTick} from 'vue';
    defineProps(['name','image','price', 'promotional_price', 'id'])
    
    async function addtocart(id){
        const sessions=localStorage.getItem('cartsessionid');
        console.log(sessions);
        const data={id:id,session:sessions}
        
        axios.post('https://admin.ebitans.com/api/v1/addtocart',data).then((response) => {
            console.log(response.data)   
                    
        });
        await nextTick()
    }
</script>