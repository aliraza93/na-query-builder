require('./vue-asset');
import VueIziToast from 'vue-izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import Vue from 'vue'
window.Vue = require('vue');

Vue.use(VueIziToast);

//Listeners Components
Vue.component('listeners', require('./components/Proxy/Listeners/index.vue').default)

//Proxy CA Components
Vue.component('proxy-ca', require('./components/Proxy/CA/index').default)

var app = new Vue({

    el: '#proxy'
});