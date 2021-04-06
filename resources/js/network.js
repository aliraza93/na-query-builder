require('./vue-asset');
import VueIziToast from 'vue-izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import Vue from 'vue'
window.Vue = require('vue');

Vue.use(VueIziToast);

//Interface Components
Vue.component('network-interface', require('./components/Network/Interface/index.vue').default)

//Network Firewall Components
Vue.component('network-firewall', require('./components/Network/Firewall/index.vue').default)

var app = new Vue({

    el: '#network'
});