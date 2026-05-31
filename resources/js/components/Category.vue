<template>
    <div class="bg-white mb-3 px-4 py-3 rounded-4">
        <div class="d-flex flex-row align-items-center justify-content-between">
            <h3 class="catname text-center">All Category</h3>
        </div>
        <hr>
        <div class="catscroll" style="max-height:80vh;overflow-y:auto">
            <CategoryItem v-for="post in posts" :key="post.id" :id="post.id" :name="post.name" :product="post.product" :icon="post.icon"/>
        </div>
    </div>
</template>
<script>
import { ref } from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios' 

const location=window.location.href;
const location1=location.replace("https://admin.ebitans.com/branch/", "");
const location2=location1.replace("/pos", "");
const data={id:location2};
// var posts=[];
// axios.post('https://admin.ebitans.com/api/v1/getcatpos',data).then((response) => {
//     // console.log(response.data)
//     var posts=JSON.parse(JSON.stringify(response.data));
// })

// console.log(posts)

import CategoryItem from './CategoryItem.vue';
    export default{
    components: { CategoryItem },
    data() {
        return {
            posts : []
        }
    },
    created() {
            this.axios.post('https://admin.ebitans.com/api/v1/getcatpos',data).then((response) => {
                console.log(response.data.data[0])
                this.posts=response.data.data
            })
        }
    }
</script>
<style scoped>
.catname{
    font-size:14px;
}
.catscroll::-webkit-scrollbar {
  display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.catscroll {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}
</style>