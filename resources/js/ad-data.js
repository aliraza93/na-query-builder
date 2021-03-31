require('./vue-asset');
import VueIziToast from 'vue-izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import Vue from 'vue'
window.Vue = require('vue');

Vue.use(VueIziToast);

Vue.component('ad-data-list', require('./components/AdData/AdData.vue').default)

var app = new Vue({

    el: '#ad-data'
});