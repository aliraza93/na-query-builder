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

//Generate CA Components
Vue.component('generate-ca', require('./components/Proxy/GenerateCA/index').default)

//Upload CA Components
Vue.component('upload-ca', require('./components/Proxy/UploadCA/index.vue').default)
var app = new Vue({

    el: '#proxy'
});