import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import Login from '../components/auth/Login'
import Logout from '../components/auth/Logout'
import Dashboard from '../components/admin/Dashboard'
import GoogleJobsTable from '../components/admin/google/JobsTable'
import ArbeitsagenturJobsTable from '../components/admin/arbeitsagentur/JobsTable'
import Statistiken from '../components/admin/arbeitsagentur/Statistiken'


const routes = [
    { path: '/', component: Login },
    // { path: '/login', component: Login },
    { path: '/logout', component: Logout },
    { path: '/dashboard', component: Dashboard, name: 'dashboard' },

    // google routes
    { path: '/google/jobs-table', component: GoogleJobsTable, name: 'google-jobs-table' },

    // arbeitsagentur routes
    { path: '/arbeitsagentur/jobs-table', component: ArbeitsagenturJobsTable, name: 'arbeitsagentur-jobs-table' },
    { path: '/arbeitsagentur/statistiken', component: Statistiken, name: 'arbeitsagentur-statistiken' },
]

const router = new VueRouter({
    routes: routes,
    hashbang : false, // removes hash in url
    mode : 'history' // helps remove hash in url
})

export default router
