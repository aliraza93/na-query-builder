<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="card-header border-bottom p-1">
                        <div class="head-label">
                            <h6 class="mb-0">Proxy Users</h6>
                        </div>
                        <div class="dt-action-buttons text-right">
                            <div class="dt-buttons flex-wrap d-inline-flex">
                                
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mx-0 row">
                        <div class="col-sm-12 col-md-6">
                            <!-- <div class="dataTables_length" id="DataTables_Table_0_length"><label>Show <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="custom-select form-control">
                                        <option value="7">7</option>
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="75">75</option>
                                        <option value="100">100</option>
                                    </select> entries</label></div> -->
                            <div class="dataTables_filter">
                                <label style="float: left;">Search:
                                    <input type="search" class="form-control" placeholder="" aria-controls="DataTables_Table_0">
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <a @click="showMenu" type="button" style="float: right;">
                                <menu-icon />
                            </a>
                            <div v-if="menu" class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
                                <a class="dropdown-item" href="#">
                                    <i class="mr-50" data-feather="user"></i> Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">
                                    <i class="mr-50" data-feather="settings"></i> Settings
                                </a>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    <i class="mr-50" data-feather="power"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="loading" v-if="isLoading">
                        <h2 style="text-align:center">Loading.......</h2>
                    </div>
                    <table class="datatables-basic table dataTable no-footer dtr-column" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                        <thead>
                            <tr role="row">
                                <th class="dt-checkboxes-cell dt-checkboxes-select-all">
                                    <div class="custom-control custom-checkbox"> 
                                        <input class="custom-control-input" v-model="allSelected" @click="selectAll" type="checkbox" value="" id="checkboxSelectAll">
                                        <label class="custom-control-label" for="checkboxSelectAll"></label>
                                    </div>
                                </th>
                                <th>Display Name</th>
                                <th>Distinguish Name</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>When Created</th>
                                <th>When Changed</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="show">
                            <tr v-for="(value,index) in ad_data_users.data" v-bind:key="index">
                                <td class=" dt-checkboxes-cell">
                                    <div class="custom-control custom-checkbox"> 
                                        <input v-model="ad_data_users_ids" class="custom-control-input dt-checkboxes" type="checkbox" @click="select" :value="value.id" :id="`checkbox` + value.id">
                                        <label class="custom-control-label" :for="`checkbox` + value.id"></label>
                                    </div>
                                </td>
                                <td>AD {{ value.name }}</td>
                                <td>Distinguish Name {{ value.name }}</td>
                                <td>{{ value.name }}</td>
                                <td>{{ value.email }}</td>
                                <td>{{ value.created_at }}</td>
                                <td>{{ value.updated_at }}</td>
                                <td class="d-flex">
                                    <a data-toggle="tooltip" title="Go To User" href="#" class="mr-1"><i style="font-size: 17px; margin-top: 1px;" class="fa fa-eye"></i></a>
                                    <a href="#" data-toggle="tooltip" title="View Info"><i class="fa fa-info-circle"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-center" style="margin-top: 15px;" v-if="!show">
                        <h4>Oops! No Users Found</h4>
                    </div>
                    <pagination :pageData="ad_data_users"></pagination>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Pagination  from '../pagination/pagination.vue';
import MenuIcon from 'vue-material-design-icons/Menu.vue';

export default {
    components: {
        Pagination,
        MenuIcon
    },
    data() {
        return {
            ad_data_users: [],
            allSelected: false,
            selected: [],
            isLoading: false,
            menu: false,
            ad_data_users_ids: [],
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
        this.get_users();
    },

    mounted() {
        
    },
    
    methods: {

        //Select all checkboxes
        selectAll() {
            this.ad_data_users_ids = [];
            if (!this.allSelected) {
                for (var user in this.ad_data_users.data) {
                    this.ad_data_users_ids.push(this.ad_data_users.data[user].id);
                }
            }
        },
        select: function() {
            this.allSelected = false;
        },

        //Get All Users
        get_users(page = 1) {
            this.isLoading = true;
            axios.get(base_url + "ad-data/users-list?page=" + page)
                .then(response => {
                    this.ad_data_users = response.data
                    this.isLoading = false;
                })
                .catch(err => {
                if (err.response) {
                    this.errors = err.response.data.errors;
                }
            });
        },

        //Show Filter Menu
        showMenu() {
            this.menu = !this.menu;
        },

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
            
    },

    computed: {
        show() {
            return this.ad_data_users.data.length >= 1 ? true: false
        }
    }
}
</script>