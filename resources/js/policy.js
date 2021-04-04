require('./vue-asset');
import VueIziToast from 'vue-izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import Vue from 'vue'
window.Vue = require('vue');

Vue.use(VueIziToast);

//Policies Components
Vue.component('policies-list', require('./components/Policy/Policies/index.vue').default)
Vue.component('policy-view', require('./components/Policy/Policies/view').default)

//Reports Components
Vue.component('reports-query', require('./components/Policy/Reports/index.vue').default)

//Rules Components
Vue.component('rules-list', require('./components/Policy/Rules/index.vue').default)

//Url List Components
Vue.component('url-list', require('./components/Policy/UrlList/index.vue').default)

//Block Pages Components
Vue.component('block-pages-list', require('./components/Policy/BlockPages/index.vue').default)

//Settings Components
Vue.component('system-settings', require('./components/Policy/Settings/index.vue').default)
var app = new Vue({

    el: '#policy'
});