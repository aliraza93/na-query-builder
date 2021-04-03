require('./vue-asset');
import VueIziToast from 'vue-izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import Vue from 'vue'
window.Vue = require('vue');

Vue.use(VueIziToast);

//Policies Components
Vue.component('policies-list', require('./components/Policy/Policies/Policies.vue').default)
Vue.component('policy-view', require('./components/Policy/Policies/PolicyView').default)

//Reports Components
Vue.component('reports-query', require('./components/Policy/Reports/ReportsQuery.vue').default)

var app = new Vue({

    el: '#policy'
});