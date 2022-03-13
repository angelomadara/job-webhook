<template>
    <div class="container-fluid">
        <div class="row">
            <app-sidebar></app-sidebar>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <router-view></router-view>
            </main>
        </div>
    </div>
</template>

<script>
import AppSidebar from './AppSidebar.vue'
export default {
  components: { AppSidebar },

}
</script>

<style>

</style>
