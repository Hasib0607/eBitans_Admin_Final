
<template>
    <div class="d-flex align-items-center justify-content-between mx-2 my-3 rounded-3 overflow-hidden" style="background-color:#E8F3EE">
        <img src="https://images.pexels.com/photos/90946/pexels-photo-90946.jpeg?auto=compress&cs=tinysrgb&w=1600" width="100" class="ms-1" />
        <div class="text-start ms-2">
            <p class="mb-0 ms-2">{{name}}</p>
            <p class="mb-0 ms-2">$100</p>
        </div>
        <div></div>
        <div class="text-center  me-2">
            <div class="d-flex pt-2">
                <div class="rounded-4 value-button" id="decrease" v-on:click="decreaseValue" value="Decrease Value">-</div>
                <input type="number" class="rounded-1 numberss" id="number" v-model="qty" />
                <div class="rounded-4 value-button" id="increase" @click="increaseValue" value="Increase Value">+</div>
            </div>
            <div class="text-center py-2">Remove</div>
        </div>
    </div>
</template>
<script setup>   

import {ref, nextTick} from 'vue'
    var item=defineProps(['id','name','qty','itemss'])
    const qty=ref(item.qty);
    const itemssss=ref(item.itemss);
    console.log(itemssss)
    async function increaseValue(event) {
        qty.value++;
        console.log(qty)
        await nextTick()
    }
    // function decreaseValue(event){
    //     qty--;
    //     console.log(qty)
    //     document.getElementById('number').value=qty;
    // }
    function decreaseValue(event){
        axios.get('https://admin.ebitans.com/api/v1/po').then((response) => {
        console.log(response.data)
        
        })
    }
    
</script>
<style scoped>
.value-button {
  display: inline-block;
  border: 1px solid #FFBB2A;
  margin: 0px;
  width: 22px;
  height: 26px;
  text-align: center;
  vertical-align: middle;
  padding: 0px 0;
  background: #FFBB2A;
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

.value-button:hover {
  cursor: pointer;
}

form #decrease {
  margin-right: -4px;
  border-radius: 8px 0 0 8px;
}

form #increase {
  margin-left: -4px;
  border-radius: 0 8px 8px 0;
}

form #input-wrap {
  margin: 0px;
  padding: 0px;
}

input#number {
  text-align: center;
  border: none;
  border-top: 1px solid #fff;
  border-bottom: 1px solid #fff;
  margin: 0px;
  width: 40px;
  height: 26px;
}

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>