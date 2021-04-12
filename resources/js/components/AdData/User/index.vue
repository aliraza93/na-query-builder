<template>
    <div class="container">
        <div class="row">
            <ad-data-user-info></ad-data-user-info>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="card-header border-bottom p-1">
                            <div class="head-label">
                                <h6 class="mb-0">AD Data Users</h6>
                            </div>
                            <div class="dt-action-buttons text-right">
                                <div class="dt-buttons flex-wrap d-inline-flex">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mx-0 row">
                            <div class="col-sm-12 col-md-6 d-flex" style="margin-top: auto;">
                                <input type="text" class="form-control" placeholder="Type Common Name..." v-model="commonname" v-on:keyup="get_users()">
                                <input type="text" class="form-control" placeholder="Type Given Name..." v-model="givenname" v-on:keyup="get_users()">
                                <input type="text" class="form-control" placeholder="Type Email..." v-model="emailaddress" v-on:keyup="get_users()">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="row d-flex" style="float: right;">
                                    <div class="col-md-8">
                                        <button v-if="ad_data_users_ids != ''" style="margin-top: 7px;" type="button" class="btn btn-primary">Add proxy User</button>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="ropdown dropdown-user" style="float: right;">
                                            <button class="btn dropdown-toggle dropdown-user-link" id="dropdown-user" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-bars" style="font-size: 20px;"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" id="showMenu" aria-labelledby="dropdown-user">
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="common_name" type="checkbox" id="common_name">
                                                        <label class="custom-control-label" for="common_name">Common Name</label>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="surname" type="checkbox" id="surname">
                                                        <label class="custom-control-label" for="surname">Surname</label>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="given_name" type="checkbox" id="given_name">
                                                        <label class="custom-control-label" for="given_name">Given Name</label>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="email" type="checkbox" id="email">
                                                        <label class="custom-control-label" for="email">Email</label>
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
                                    <th class="dt-checkboxes-cell dt-checkboxes-select-all text-center">
                                        <div class="custom-control custom-checkbox"> 
                                            <input class="custom-control-input" v-model="allSelected" @click="selectAll" type="checkbox" value="" id="checkboxSelectAll">
                                            <label class="custom-control-label" for="checkboxSelectAll"></label>
                                        </div>
                                    </th>
                                    <th class="text-center" v-if="common_name">Common Name</th>
                                    <th class="text-center" v-if="surname">Surname</th>
                                    <th class="text-center" v-if="given_name">Given Name</th>
                                    <th class="text-center" v-if="email">Email</th>
                                    <th class="text-center" v-if="when_created">When Created</th>
                                    <th class="text-center" v-if="when_changed">When Changed</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="show">
                                <tr v-for="(value,index) in ad_data_users.data" v-bind:key="index">
                                    <td class=" dt-checkboxes-cell text-center">
                                        <div class="custom-control custom-checkbox"> 
                                            <input v-model="ad_data_users_ids" class="custom-control-input dt-checkboxes" type="checkbox" @click="select" :value="value.id" :id="`checkbox` + value.id">
                                            <label class="custom-control-label" :for="`checkbox` + value.id"></label>
                                        </div>
                                    </td>
                                    <td class="text-center" v-if="common_name">{{ value.common_name }}</td>
                                    <td class="text-center" v-if="surname">{{ value.surname }}</td>
                                    <td class="text-center" v-if="given_name">{{ value.given_name }}</td>
                                    <td class="text-center" v-if="email">{{ value.email_addresses }}</td>
                                    <td class="text-center" v-if="when_created">{{ value.when_created }}</td>
                                    <td class="text-center" v-if="when_changed">{{ value.when_updated }}</td>
                                    <td class="text-center">
                                        <a :href="`user/` + value.id" data-toggle="tooltip" type="button" @click="showUser(value.id)" title="Go To User" class="btn">
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
                            <h4>Oops! No Users Found</h4>
                        </div>
                        <pagination :pageData="ad_data_users"></pagination>
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
import AdDataUserInfo from './AdDataUserInfo.vue';

export default {
    components: {
        Pagination,
        MenuIcon,
        Close,
        AdDataUserInfo
    },
    data() {
        return {
            ad_data_users: [],

            common_name: true,
            surname: true,
            given_name: true,
            email: true,
            when_created: true,
            when_changed: true,

            commonname: '',
            givenname: '',
            emailaddress: '',
            allSelected: false,
            selected: [],
            isLoading: false,
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
            axios
                .get(
                base_url +
                    "ad-data/users-list?page="+
                    page+
                    "&common_name=" +
                    this.commonname +
                    "&given_name=" +
                    this.givenname +
                    "&email=" +
                    this.emailaddress
                )
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
            EventBus.$emit("show-user-info", id);
        },
            
    },

    computed: {
        show() {
            return this.ad_data_users.data ? (this.ad_data_users.data.length >= 1 ? true: false) : null
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