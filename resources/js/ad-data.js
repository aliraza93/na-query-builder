require('./vue-asset');
import VueIziToast from 'vue-izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import Vue from 'vue'
window.Vue = require('vue');

Vue.use(VueIziToast);

//Ad Data User Components
Vue.component('ad-data-user-list', require('./components/AdData/User/AdDataUsers.vue').default)
Vue.component('ad-data-user-view', require('./components/AdData/User/AdDataUserView').default)

//Ad Data Computer Components
Vue.component('ad-data-computers-list', require('./components/AdData/Computer/AdDataComputers.vue').default)
Vue.component('ad-data-computer-view', require('./components/AdData/Computer/AdDataComputerView').default)

//Ad Data Subnet Components
Vue.component('ad-data-subnet-list', require('./components/AdData/Subnet/AdDataSubnets.vue').default)
Vue.component('ad-data-subnet-view', require('./components/AdData/Subnet/AdDataSubnetView').default)

//Ad Data Tree Component
Vue.component('ad-data-tree-view', require('./components/AdData/TreeView/AdDataTreeView.vue').default)

//Ad Data Groups Component
Vue.component('ad-data-groups-list', require('./components/AdData/Groups/AdDataGroups.vue').default)

//Ad Data Containers Component
Vue.component('ad-data-containers-list', require('./components/AdData/Containers/AdDataContainers.vue').default)
Vue.component('ad-data-container-view', require('./components/AdData/Containers/AdDataContainerView').default)

//Ad Data Organizational Units Components
Vue.component('ad-data-organizational-units-list', require('./components/AdData/OrganizationalUnits/AdDataOrganizationalUnits.vue').default)

var app = new Vue({

    el: '#ad-data'
});