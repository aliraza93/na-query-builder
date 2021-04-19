<template>
    <div class="container">
        <div class="row">
            <ad-data-computer-info></ad-data-computer-info>
            <ad-data-add-subnet></ad-data-add-subnet>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="card-header border-bottom p-1">
                            <div class="head-label">
                                <h6 class="mb-0">Ad Data Subnets</h6>
                            </div>
                            <div class="dt-action-buttons text-right">
                                <div class="dt-buttons flex-wrap d-inline-flex">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mx-0 row">
                            <div class="col-sm-12 col-md-6">
                                <div class="dataTables_filter">
                                    <label style="float: left;">Search:
                                        <input type="search" class="form-control" placeholder="" v-model="name" v-on:keyup="get_users()" aria-controls="DataTables_Table_0">
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="row d-flex" style="float: right;">
                                    <div class="col-md-8">
                                        <button style="margin-top: 7px;" class="btn btn-primary" data-toggle="modal" data-target="#add-subnet">Add Subnet</button>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="ropdown dropdown-user" style="float: right;">
                                            <button class="btn dropdown-toggle dropdown-user-link" id="dropdown-user" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-bars" style="font-size: 20px;"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" id="showMenu" aria-labelledby="dropdown-user">
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="enable" type="checkbox" id="enable">
                                                        <label class="custom-control-label" for="enable">Enable</label>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="subnet_name" type="checkbox" id="subnet_name">
                                                        <label class="custom-control-label" for="subnet_name">Subnet Name</label>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="description" type="checkbox" id="description">
                                                        <label class="custom-control-label" for="description">Description</label>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="ip_address_from" type="checkbox" id="ip_address_from">
                                                        <label class="custom-control-label" for="ip_address_from">IP Address From</label>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="ip_address_to" type="checkbox" id="ip_address_to">
                                                        <label class="custom-control-label" for="ip_address_to">IP Address To</label>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="ip_address_points" type="checkbox" id="ip_address_points">
                                                        <label class="custom-control-label" for="ip_address_points">IP Address Points</label>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="loading text-center" v-if="isLoading">
                            <b-spinner variant="primary" label="Text Centered"></b-spinner>
                        </div>
                        <table v-else class="datatables-basic table dataTable no-footer dtr-column" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                            <thead>
                                <tr role="row">
                                    <th class="text-center" v-if="enable">Enable</th>
                                    <th class="text-center" v-if="subnet_name">Subnet Name</th>
                                    <th class="text-center" v-if="description">Description</th>
                                    <th class="text-center" v-if="ip_address_from">IP Address From</th>
                                    <th class="text-center" v-if="ip_address_to">IP Address To</th>
                                    <th class="text-center" v-if="ip_address_points">IP Address Points</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="show">
                                <tr v-for="(value,index) in ad_data_subnet.data" v-bind:key="index">
                                    <td class="text-center" v-if="enable">True</td>
                                    <td class="text-center" v-if="subnet_name">{{ value.user_name }}</td>
                                    <td class="text-center" v-if="description"></td>
                                    <td class="text-center" v-if="ip_address_from">192.155.15.1</td>
                                    <td class="text-center" v-if="ip_address_to">192.155.451.1</td>
                                    <td class="text-center" v-if="ip_address_points"></td>
                                    <td class="text-center">
                                        <a :href="`subnet/` + value.id" data-toggle="tooltip" type="button" @click="showUser(value.id)" title="Go To Subnet" class="btn">
                                            <i style="margin-top: 1px;" class="fa fa-eye"></i>
                                        </a>
                                        <button title="View Info" data-toggle="tooltip" class="btn" @click="view(value.id)">
                                            <i class="fa fa-info-circle"></i>
                                        </button>
                                        <button title="Delete Subnet" data-toggle="tooltip" class="btn" @click="view(value.id)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-center" style="margin-top: 15px;" v-if="!isLoading && !show">
                            <h4>Oops! No Subnets Found</h4>
                        </div>
                        <pagination :pageData="ad_data_subnet"></pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Pagination  from '../../pagination/pagination.vue';
import MenuIcon from 'vue-material-design-icons/Menu.vue';
import Close from 'vue-material-design-icons/Close.vue';
import { EventBus } from "../../../vue-asset";
import AdDataComputerInfo from '../Computer/AdDataComputerInfo.vue';
import AdDataAddSubnet from './AdDataAddSubnet.vue';

export default {
    components: {
        Pagination,
        MenuIcon,
        Close,
        AdDataComputerInfo,
        AdDataAddSubnet
    },
    data() {
        return {
            ad_data_subnet: [],

            enable: true,
            subnet_name: true,
            ip_address_from: true,
            ip_address_to: true,
            ip_address_points: true,
            description: true,

            allSelected: false,
            name: '',
            selected: [],
            isLoading: false,
            ad_data_subnet_ids: [],
            errors: null,
            notificationSystem: {
            options: {
                success: {
                    position: "center",
                    timeout: 3000,
                    class: 'success_notification'
                },
                error: {
                    position: "center",
                    timeout: 4000,
                    class: 'error_notification'
                },
                completed: {
                    position: 'center',
                    timeout: 1000,
                    class: 'complete_notification'
                },
                info: {
                    overlay: true,
                    zindex: 999,
                    position: 'center',
                    timeout: 3000,
                    class: 'info_notification',
                }
            }
            },
        };
    },
    created() {
        var _this = this;
        this.get_users();
        EventBus.$on("ad-data-users", function() {
            _this.get_users();
        });
    },

    mounted() {
        
    },
    
    methods: {

        //Select all checkboxes
        selectAll() {
            this.ad_data_subnet_ids = [];
            if (!this.allSelected) {
                for (var user in this.ad_data_subnet.data) {
                    this.ad_data_subnet_ids.push(this.ad_data_subnet.data[user].id);
                }
            }
        },
        select: function() {
            this.allSelected = false;
        },

        
        get_users(page = 1) {
            this.isLoading = true;
            axios
                .get(
                base_url +
                    "ad-data/users-list?page="+
                    page+
                    "&name=" +
                    this.name
                )
                .then(response => {
                    this.ad_data_subnet = response.data
                    this.isLoading = false;
                })
                .catch(err => {
                if (err.response) {
                    this.errors = err.response.data.errors;
                }
            });
        },

        //Show User Page
        // showUser(id) {
        //     axios.get(base_url + 'ad-data/user/' + id).then(response => {})
        // },

        pageClicked(pageNo) {
            var vm = this;
            vm.get_users(pageNo);
        },
        
        showMessage(data) {
            if (data.status  == "success") {
                this.$toast.success(data.message, 'Success Alert', this.notificationSystem.options.success );
            } else {
                this.$toast.error(data.message, "Error Alert", this.notificationSystem.options.error);
            }
        },

        
        view(id) {
            // $('#basic-modals').modal('show');
            EventBus.$emit("show-user-info", id);
        },
            
    },

    computed: {
        show() {
            return this.ad_data_subnet.data ? (this.ad_data_subnet.data.length >= 1 ? true: false) : null
        },

        showMenu() {
            if ($(".ropdown").hasClass("show")) {
                console.log('has class')
                return false
            }
            else{
                console.log('has no class')
                return true
            }
        }
    }
}
</script>

<style scoped>
    .feather-25{
        width: 25px;
        height: 25px;
    }
</style>