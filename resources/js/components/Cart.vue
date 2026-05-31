<template>
    <div class="overflow-auto cartrow" style="height:44vh">
        <CartItem v-for="item in items" :key="item.id"  :name="item.name" :qty="item.quantity" :id="item.id" />
    </div>
    <div class="px-2 py-3">
        <CartSummary/>
    </div>
    <div class="">
        <Payment/>
    </div>
</template>
<script>
import axios from 'axios'
import {nextTick} from 'vue';
import VueAxios from 'vue-axios'
import CartItem from './CartItem.vue';
import CartSummary from './CartSummary.vue';
import Payment from './Payment.vue';


    // const items=([
    // {id:1,name:"Product 1",qty:1},
    // {id:2,name:"Product 2",qty:1},
    // {id:3,name:"Product 3",qty:1},
    // {id:4,name:"Product 4",qty:1},
    // {id:5,name:"Product 5",qty:1},
    // {id:6,name:"Product 6",qty:1},
    // {id:7,name:"Product 3",qty:1},
    // {id:8,name:"Product 4",qty:1},
    // {id:9,name:"Product 5",qty:1},
    // {id:10,name:"Product 6",qty:1},
    // ]);
    export default{ 
        components: { CartItem, CartSummary, Payment },
        data(){
            return {
                items:[]
            }
        },
        async created(){
            const sessions=localStorage.getItem('cartsessionid');
            console.log(sessions);
            const data={session:sessions}
            this.axios.post('https://admin.ebitans.com/api/v1/getcarts',data).then((response) => {
                console.log(response.data)
                this.items=response.data
            })
        }
    }
</script>
<style scoped>
.cartrow::-webkit-scrollbar {
  display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.cartrow {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}
.modal-header{
      margin-top: 36px !important;
    }
</style>