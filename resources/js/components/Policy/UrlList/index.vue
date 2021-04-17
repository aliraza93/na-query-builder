<template>
    <div class="container">
        <div class="row">
            <add-url></add-url>
            <edit-url></edit-url>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="card-header border-bottom p-1">
                            <div class="head-label">
                                <h6 class="mb-0">URL List</h6>
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
                                        <input type="search" class="form-control" placeholder="" v-model="title" v-on:keyup="get_url_list()" aria-controls="DataTables_Table_0">
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="row d-flex" style="float: right;">
                                    <div class="col-md-8">
                                        <button style="margin-top: 7px;" class="btn btn-primary" data-toggle="modal" data-target="#add-url">Add Url</button>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="ropdown dropdown-user" style="float: right;">
                                            <button class="btn dropdown-toggle dropdown-user-link" id="dropdown-user" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-bars" style="font-size: 20px;"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" id="showMenu" aria-labelledby="dropdown-user">
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="list_name" type="checkbox" id="list_name">
                                                        <label class="custom-control-label" for="list_name">List Name</label>
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
                                    <th v-if="list_name">List Name</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="show">
                                <tr v-for="(value,index) in url_list.data" v-bind:key="index">
                                    <td v-if="list_name">{{ value.list_title }}</td>
                                    <td class="text-center">
                                        <button data-toggle="tooltip" @click="editURL(value.list_id)" title="Edit URL" class="btn">
                                            <i style="margin-top: 1px;" class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" @click="sendInfo(value.list_id)" data-toggle="modal" data-target="#delete-url" class="btn">
                                            <i style="margin-top: 1px; color: red;" class="fa fa-trash"></i>
                                        </button>
                                        <a :href="`list-details/` + value.list_id" title="View Info" data-toggle="tooltip" class="btn" @click="view(value.id)">
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                        <!-- Delete URL List Modal -->
                                        <div class="modal custom-modal fade" id="delete-url" role="dialog">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <div class="form-header">
                                                            <h3>Delete URL List</h3>
                                                            <p>Are you sure want to delete?</p>
                                                        </div>
                                                        <div class="modal-btn delete-action">
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <a href="javascript:void(0);" @click="destroy(selected_url_id)" class="btn btn-primary continue-btn">Delete</a>
                                                                </div>
                                                                <div class="col-6">
                                                                    <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /Delete URL List Modal -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-center" style="margin-top: 15px;" v-if="!isLoading && !show">
                            <h4>Oops! No URL Found</h4>
                        </div>
                        <pagination :pageData="url_list"></pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Pagination  from '../../pagination/pagination.vue';
import { EventBus } from "../../../vue-asset";
import AddUrl from './AddUrl.vue';
import EditUrl from './EditUrl.vue';

export default {
    components: {
        Pagination,
        AddUrl,
        EditUrl
    },
    data() {
        return {
            url_list: [],

            list_name: true,
            priority: true,
            block_page: true,
            when_changed: true,

            allSelected: false,
            selected: [],
            selected_url_id: '',
            title: '',
            isLoading: false,
            url_list_ids: [],
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
        _this.get_url_list()
        EventBus.$on("url-list-added", function() {
            _this.get_url_list();
        });
    },

    mounted() {
        
    },
    
    methods: {

        //Select all checkboxes
        selectAll() {
            this.url_list_ids = [];
            if (!this.allSelected) {
                for (var user in this.url_list.data) {
                    this.url_list_ids.push(this.url_list.data[user].id);
                }
            }
        },
        select: function() {
            this.allSelected = false;
        },

        get_url_list(page = 1) {
            this.isLoading = true;
            axios
                .get(
                base_url +
                    "policy/url-list?page="+
                    page+
                    "&list_title=" +
                    this.title
                )
                .then(response => {
                    this.url_list = response.data
                    this.isLoading = false;
                })
                .catch(err => {
                if (err.response) {
                    this.errors = err.response.data.errors;
                }
            });
        },

        pageClicked(pageNo) {
            var vm = this;
            vm.get_url_list(pageNo);
        },
        
        showMessage(data) {
            if (data.status  == "success") {
                this.$toast.success(data.message, 'Success Alert', this.notificationSystem.options.success );
            } else {
                this.$toast.error(data.message, "Error Alert", this.notificationSystem.options.error);
            }
        },

        editURL(id) {
            EventBus.$emit("edit-url", id);
        },

        sendInfo(id) {
            this.selected_url_id = id;
        },

        destroy(id) {
            axios.delete(base_url + "policy/url-list/" + id)

            .then(response => {
                EventBus.$emit("url-list-added");
                $('#delete-url').modal('hide');
                this.showMessage(response.data);
            })
            .catch(err => {
                if (err.response) {
                    this.showMessage(err.response.data)
                }
            });
            
        }
            
    },

    computed: {
        show() {
            return this.url_list.data ? (this.url_list.data.length >= 1 ? true: false) : null
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