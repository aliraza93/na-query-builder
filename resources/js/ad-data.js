require('./vue-asset');
import VueIziToast from 'vue-izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import BootstrapVue from "bootstrap-vue";
import {
  ValidationObserver,
  ValidationProvider,
  extend,
  localize
} from "vee-validate";
import * as rules from "vee-validate/dist/rules";

import Vue from 'vue'
window.Vue = require('vue');

// Install VeeValidate rules and localization
Object.keys(rules).forEach(rule => {
    extend(rule, rules[rule]);
  });

// Install VeeValidate components globally
Vue.component("ValidationObserver", ValidationObserver);
Vue.component("ValidationProvider", ValidationProvider);

Vue.use(BootstrapVue);

Vue.use(VueIziToast);

//Ad Data User Components
Vue.component('user-list', require('./components/AdData/User/index.vue').default)
Vue.component('user-view', require('./components/AdData/User/view').default)

//Ad Data Computer Components
Vue.component('computers-list', require('./components/AdData/Computer/index.vue').default)
Vue.component('computer-view', require('./components/AdData/Computer/view').default)

//Ad Data Subnet Components
Vue.component('subnet-list', require('./components/AdData/Subnet/index.vue').default)
Vue.component('subnet-view', require('./components/AdData/Subnet/view').default)

//Ad Data Tree Component
Vue.component('tree-view', require('./components/AdData/TreeView/index.vue').default)

//Ad Data Groups Component
Vue.component('groups-list', require('./components/AdData/Groups/index.vue').default)

//Ad Data Containers Component
Vue.component('containers-list', require('./components/AdData/Containers/index.vue').default)
Vue.component('container-view', require('./components/AdData/Containers/view').default)

//Ad Data Organizational Units Components
Vue.component('organizational-units-list', require('./components/AdData/OrganizationalUnits/index.vue').default)

var app = new Vue({

    el: '#ad-data'
});