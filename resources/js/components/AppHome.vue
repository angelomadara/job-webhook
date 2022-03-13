<template>
    <div>
        <top-bar :app_name="appname"></top-bar>
        <app-content></app-content>
    </div>
</template>

<script>
import TopBar from './AppHeader.vue'
import AppContent from './AppContent.vue'
export default {
    props: ['appname'],
    components:{
        TopBar,
        AppContent
    }
}
</script>

<style>
</style>
