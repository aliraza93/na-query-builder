<template>
    <div class="container">
        <div class="row">
            <ad-data-computer-info></ad-data-computer-info>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="card-header border-bottom p-1">
                            <div class="head-label">
                                <h6 class="mb-0">Ad Data Computers</h6>
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
                                        <button v-if="ad_data_computer_ids != ''" style="margin-top: 7px;" type="button" class="btn btn-primary">Add proxy User</button>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="ropdown dropdown-user" style="float: right;">
                                            <button class="btn dropdown-toggle dropdown-user-link" id="dropdown-user" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-bars" style="font-size: 20px;"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" id="showMenu" aria-labelledby="dropdown-user">
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="display_name" type="checkbox" id="display_name">
                                                        <label class="custom-control-label" for="display_name">Display Name</label>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="distinguished_name" type="checkbox" id="distinguished_name">
                                                        <label class="custom-control-label" for="distinguished_name">Distinguished Name</label>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="when_created" type="checkbox" id="when_created">
                                                        <label class="custom-control-label" for="when_created">When Crated</label>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="when_changed" type="checkbox" id="when_changed">
                                                        <label class="custom-control-label" for="when_changed">When Changed</label>
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
                                    <th class="text-center" v-if="display_name">Display Name</th>
                                    <th class="text-center" v-if="distinguished_name">Distinguish Name</th>
                                    <th class="text-center" v-if="when_created">When Created</th>
                                    <th class="text-center" v-if="when_changed">When Changed</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="show">
                                <tr v-for="(value,index) in ad_data_computer.data" v-bind:key="index">
                                    <td class="text-center" v-if="display_name">AD {{ value.user_name }}</td>
                                    <td class="text-center" v-if="distinguished_name">Distinguish Name {{ value.user_name }}</td>
                                    <td class="text-center" v-if="when_created">{{ value.when_created }}</td>
                                    <td class="text-center" v-if="when_changed">{{ value.when_updated }}</td>
                                    <td class="text-center">
                                        <a :href="`computer/` + value.id" data-toggle="tooltip" type="button" @click="showUser(value.id)" title="Go To Computer" class="btn">
                                            <i style="font-size: 17px; margin-top: 1px;" class="fa fa-eye"></i>
                                        </a>
                                        <button title="View Info" data-toggle="tooltip" class="btn" @click="view(value.id)">
                                            <i class="fa fa-info-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-center" style="margin-top: 15px;" v-if="!isLoading && !show">
                            <h4>Oops! No Computers Found</h4>
                        </div>
                        <pagination :pageData="ad_data_computer"></pagination>
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
import AdDataComputerInfo from './AdDataComputerInfo.vue';

export default {
    components: {
        Pagination,
        MenuIcon,
        Close,
        AdDataComputerInfo
    },
    data() {
        return {
            ad_data_computer: [],

            display_name: true,
            distinguished_name: true,
            when_created: true,
            when_changed: true,

            allSelected: false,
            selected: [],
            name: '',
            isLoading: false,
            ad_data_computer_ids: [],
            errors: null,
            notificationSystem: {
            options: {
                success: {
                    position: "topRight",
                    timeout: 3000,
                    class: 'success_notification'
                },
                error: {
                    position: "topRight",
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
            this.ad_data_computer_ids = [];
            if (!this.allSelected) {
                for (var user in this.ad_data_computer.data) {
                    this.ad_data_computer_ids.push(this.ad_data_computer.data[user].id);
                }
            }
        },
        select: function() {
            this.allSelected = false;
        },

        //Get All Users
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
                    this.ad_data_computer = response.data
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

        //View User Info
        view(id) {
            // $('#basic-modals').modal('show');
            EventBus.$emit("show-user-info", id);
        },
            
    },

    computed: {
        show() {
            return this.ad_data_computer.data ? (this.ad_data_computer.data.length >= 1 ? true: false) : null
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