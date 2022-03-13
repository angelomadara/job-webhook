<template>
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-1 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="/">{{app_name}}</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="navbar-nav ml-auto justify-content-end" v-if="isLoggedIn">
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ user_name }}
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <router-link class="dropdown-item" to="/logout">Logout</router-link>
                </div>
            </li>
        </ul>
    </header>
</template>

<script>
export default {
    props: ['app_name'],
    data(){
        return {
            isLoggedIn: User.isLoggedIn(),
            user_name: User.isLoggedIn() ? User.name() : ''
        }
    },
    created(){
        EventBus.$on('logout',()=>{
            User.logout()
        })
    }
}
</script>

<style>

</style>
