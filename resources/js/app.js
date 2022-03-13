/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * libraries
 */
import DatePicker from 'vue-bootstrap-datetimepicker'
Vue.use(DatePicker)

/**
 * parent component
 */
Vue.component('AppHome', require('./components/AppHome.vue').default);
/**
 * vue router
 */
import router from './router/router.js'
/**
 * classes
 */
import User from './class/User.js'
window.User = User

import Helper from './class/Helper'
import Vue from 'vue';
window.Helper = Helper

window.EventBus = new Vue();

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});
