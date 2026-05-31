<!-- src/components/Layout.vue -->
<template>
    <div>
        <topMenu/>
        <main>
            <!-- This will render the child components passed to the layout -->
            <slot></slot>
        </main>

        <footer>
            <!-- Footer content -->
            <p>Site Footer</p>
        </footer>
    </div>
</template>

<script>
import topMenu from "../components/top-menu-vue.vue";

export default {
    name: 'mainVue',
    components: {
        topMenu
    }
}
</script>

<style scoped>
header {
    background-color: #f8f9fa;
    padding: 20px;
}

footer {
    background-color: #f8f9fa;
    padding: 20px;
    margin-top: 20px;
}
</style>
