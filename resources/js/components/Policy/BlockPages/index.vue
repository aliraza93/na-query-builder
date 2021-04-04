<template>
    <div class="container">
        <div class="row">
            <add-page></add-page>
            <edit-page></edit-page>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="card-header border-bottom p-1">
                            <div class="head-label">
                                <h6 class="mb-0">Block Pages</h6>
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
                                        <button style="margin-top: 7px;" class="btn btn-primary" data-toggle="modal" data-target="#add-page">Add Page</button>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="ropdown dropdown-user" style="float: right;">
                                            <button class="btn dropdown-toggle dropdown-user-link" id="dropdown-user" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-bars" style="font-size: 20px;"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" id="showMenu" aria-labelledby="dropdown-user">
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="page_name" type="checkbox" id="page_name">
                                                        <label class="custom-control-label" for="page_name">Page Name</label>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="default_page" type="checkbox" id="default_page">
                                                        <label class="custom-control-label" for="default_page">Default Page</label>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="loading" v-if="isLoading">
                            <h2 style="text-align:center">Loading.......</h2>
                        </div>
                        <table v-else class="datatables-basic table dataTable no-footer dtr-column" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                            <thead>
                                <tr role="row">
                                    <th v-if="page_name">Page Name</th>
                                    <th class="text-center" v-if="default_page">Default Page</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="show">
                                <tr v-for="(value,index) in block_page.data" v-bind:key="index">
                                    <td v-if="page_name">{{ value.name }}</td>
                                    <td class="text-center" v-if="default_page">True</td>
                                    <td class="text-center">
                                        <button data-toggle="tooltip" @click="editPage(value.id)" title="Go To Computer" class="btn">
                                            <i style="font-size: 17px; margin-top: 1px;" class="fa fa-edit"></i>
                                        </button>
                                        <a :href="`policy/` + value.id" title="View Info" data-toggle="tooltip" class="btn" @click="view(value.id)">
                                            <i class="fa fa-info-circle"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-center" style="margin-top: 15px;" v-if="!show">
                            <h4>Oops! No Block Pages Found</h4>
                        </div>
                        <pagination :pageData="block_page"></pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Pagination  from '../../pagination/pagination.vue';
import { EventBus } from "../../../vue-asset";
import AddPage from './AddPage.vue';
import EditPage from './EditPage.vue';

export default {
    components: {
        Pagination,
        AddPage,
        EditPage
    },
    data() {
        return {
            block_page: [],

            page_name: true,
            priority: true,
            default_page: true,
            when_changed: true,

            allSelected: false,
            selected: [],
            name: '',
            isLoading: false,
            block_page_ids: [],
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
            this.block_page_ids = [];
            if (!this.allSelected) {
                for (var user in this.block_page.data) {
                    this.block_page_ids.push(this.block_page.data[user].id);
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
                    this.block_page = response.data
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
        editPage(id) {
            EventBus.$emit("edit-page", id);
        },
            
    },

    computed: {
        show() {
            return this.block_page.data ? (this.block_page.data.length >= 1 ? true: false) : null
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