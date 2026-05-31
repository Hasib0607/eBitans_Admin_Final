<template>
    <div class="row">
        <div class="col-md-4">
            <div class="title">
                All in One Solution
            </div>
            <ul class="content">
                <li v-for="(detail, index) in details" :key="index" v-show="detail.type ==='all'"><i aria-hidden="true" class="fa fa-check"></i> {{detail.title}}</li>
            </ul>
        </div>
        <div class="col-md-4 border-x">
            <div class="title">
                Features
            </div>
            <ul class="content">
                <li v-for="(detail, index) in details" :key="index" v-show="detail.type ==='features'"><i aria-hidden="true" class="fa fa-check"></i> {{detail.title}}</li>
            </ul>
        </div>
        <div class="col-md-4">
            <div class="title">
                Addons
            </div>
            <ul class="content">
                <li v-for="(detail, index) in details" :key="index" v-show="detail.type ==='addons'"><i aria-hidden="true" class="fa fa-check"></i> {{detail.title}}</li>
            </ul>
        </div>
    </div>
</template>
<script>
export default {
    name: 'package-feature',
    props: ['details']
}
</script>

<style>
.row div.col-md-4.border-x {
    border-right: 1px solid black;
    border-left: 1px solid black;
}

.row div.col-md-4 {
    padding-left: 45px;
}

.row div .title {
    font-size: 18px;
    font-weight: bold;
    color: #000000;
}

.row div ul.content {
    list-style-type: none;
    padding: 0;
    color: #000000;
    font-size: 16px;
}

.row div ul.content li i {
    font-weight: 100;
    color: #15A034;
    margin-right: 10px;
}
</style>
