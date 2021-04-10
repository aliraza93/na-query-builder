require('./vue-asset');
import VueIziToast from 'vue-izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import BootstrapVue from "bootstrap-vue";
import Vue from 'vue'
window.Vue = require('vue');

Vue.use(VueIziToast);
Vue.use(BootstrapVue)

//SystemMaintenance Components
Vue.component('system-maintenance', require('./components/system/SystemMaintenance/index.vue').default)

//System Logs Components
Vue.component('system-logs', require('./components/system/SystemLogs/index.vue').default)

//System Time Components
Vue.component('system-time', require('./components/system/SystemTime/index.vue').default)

//LDAP Configurations Components
Vue.component('ldap-configurations', require('./components/system/LDAPConfigurations/index.vue').default)
var app = new Vue({

    el: '#system'
});