<template>
    <div class="card card-body my-2 text-center bg-main border-0" @click="searchproduct(id)">
        <img v-bind:src="'https://admin.ebitans.com/assets/images/icon/' + icon"/>
        <p class="mb-0" style="font-size:13px">{{name}} ({{product}})</p>
    </div>
</template>
<script>
    // defineProps(['name','product','icon','id'])
    export default{
    props:(['name','product','icon','id']),
    data() {
        return {
            bus: Vue.createApp()
        }
    },
    methods:{
        searchproduct(id){
            this.$root.theRootMethod()
        }
    }
    }
</script>
<style scoped>
.bg-main{
    background-color:#E8F3EE;
}
</style>