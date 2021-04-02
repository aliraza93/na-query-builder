require('./vue-asset');
import VueIziToast from 'vue-izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import Vue from 'vue'
window.Vue = require('vue');

Vue.use(VueIziToast);

Vue.component('ad-data-list', require('./components/AdData/AdData.vue').default)
Vue.component('ad-data-user-view', require('./components/AdData/AdDataUserView').default)

var app = new Vue({

    el: '#ad-data'
});