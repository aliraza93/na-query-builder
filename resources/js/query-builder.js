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
Vue.component('form-validation', require('./components/QueryBuilder/FormValidation.vue').default)
Vue.component('querybuilder-operators', require('./components/QueryBuilder/Operators/index.vue').default)

Vue.component('querybuilder-triggers', require('./components/QueryBuilder/Triggers/index.vue').default)

Vue.component('querybuilder-operands', require('./components/QueryBuilder/Operands/index.vue').default)
var app = new Vue({

    el: '#query-builder'
});