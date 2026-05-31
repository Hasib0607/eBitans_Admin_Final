<template>
    
</template>
<script>
import { ref } from 'vue'
import ProductItem from './ProductItem.vue';
const location=window.location.href;
const location1=location.replace("https://admin.ebitans.com/branch/", "");
const location2=location1.replace("/pos", "");
console.log(location2)
const data={id:location2};
    export default{ 
        components: { ProductItem },
        data() {
            return {
                products:[]
            }
        },
        created() {
            this.axios.post('/getproducts',data).then((response) => {
                console.log(response.data.data[0])
                this.products=response.data.data
            })
        }
    }        
</script>
<style scoped>
.productrow::-webkit-scrollbar {
  display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.productrow {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}
.modal-header{
      margin-top: 36px !important;
    }
</style>