<template>
    <div class="container">
        <div class="row">
            <add-policy></add-policy>
            <edit-policy></edit-policy>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="card-header border-bottom p-1">
                            <div class="head-label">
                                <h6 class="mb-0">Policies</h6>
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
                                        <button style="margin-top: 7px;" class="btn btn-primary" data-toggle="modal" data-target="#add-policy">Add Policy</button>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="ropdown dropdown-user" style="float: right;">
                                            <button class="btn dropdown-toggle dropdown-user-link" id="dropdown-user" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-bars" style="font-size: 20px;"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" id="showMenu" aria-labelledby="dropdown-user">
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="priority" type="checkbox" id="priority">
                                                        <label class="custom-control-label" for="priority">Priority</label>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="policy_name" type="checkbox" id="policy_name">
                                                        <label class="custom-control-label" for="policy_name">Policy Name</label>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="block_page" type="checkbox" id="block_page">
                                                        <label class="custom-control-label" for="block_page">Block Page</label>
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
                                    <th class="text-center" v-if="priority">Priority</th>
                                    <th class="text-center" v-if="policy_name">Policy Name</th>
                                    <th class="text-center" v-if="block_page">Block Page</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="show">
                                <tr v-for="(value,index) in policies.data" v-bind:key="index">
                                    <td class="text-center" v-if="priority"> {{ index+1 }}</td>
                                    <td class="text-center" v-if="policy_name">{{ value.user_name }}</td>
                                    <td class="text-center" v-if="block_page">{{ value.user_name }}</td>
                                    <td class="text-center">
                                        <button data-toggle="tooltip" @click="editPolicy(value.id)" title="Go To Computer" class="btn">
                                            <i style="font-size: 17px; margin-top: 1px;" class="fa fa-edit"></i>
                                        </button>
                                        <a :href="`policy/` + value.id" title="View Info" data-toggle="tooltip" class="btn" @click="view(value.id)">
                                            <i class="fa fa-info-circle"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-center" style="margin-top: 15px;" v-if="!isLoading && !show">
                            <h4>Oops! No Policies Found</h4>
                        </div>
                        <pagination :pageData="policies"></pagination>
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
import AddPolicy from './AddPolicy.vue';
import EditPolicy from './EditPolicy.vue';
// import AdDataComputerInfo from './AdDataComputerInfo.vue';

export default {
    components: {
        Pagination,
        MenuIcon,
        Close,
        AddPolicy,
        EditPolicy,
        // AdDataComputerInfo
    },
    data() {
        return {
            policies: [],

            policy_name: true,
            priority: true,
            block_page: true,
            when_changed: true,

            allSelected: false,
            selected: [],
            name: '',
            isLoading: false,
            policies_ids: [],
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
            this.policies_ids = [];
            if (!this.allSelected) {
                for (var user in this.policies.data) {
                    this.policies_ids.push(this.policies.data[user].id);
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
                    this.policies = response.data
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
        editPolicy(id) {
            EventBus.$emit("edit-policy", id);
        },
            
    },

    computed: {
        show() {
            return this.policies.data ? (this.policies.data.length >= 1 ? true: false) : null
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