<template>
    <div class="tab-pane active" id="rules" aria-labelledby="rules-tab" role="tabpanel">
        <div class="card">
            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="d-flex justify-content-between align-items-center mx-0 row">
                    <div class="col-sm-12 col-md-6 d-flex mb-1" style="margin-top: auto;">
                        <input type="text" class="form-control" placeholder="Type List URL..." v-model="listurl" v-on:keyup="get_named_list_entry()">
                        <input type="text" class="form-control" placeholder="Type List Type..." v-model="listtype" v-on:keyup="get_named_list_entry()">
                    </div>
                    <div class="col-sm-12 col-md-6 mb-1">
                        <div class="row d-flex" style="float: right;">
                            <div class="col-md-8">
                                <button style="margin-top: 7px;" class="btn btn-primary" data-toggle="modal" data-target="#add-url">Add URL</button>
                            </div>
                            <div class="col-md-4">
                                <div class="ropdown dropdown-user" style="float: right;">
                                    <button class="btn dropdown-toggle dropdown-user-link" id="dropdown-user" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-bars" style="font-size: 20px;"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" id="showMenu" aria-labelledby="dropdown-user">
                                        <a class="dropdown-item d-flex" href="javascript:void(0);">
                                            <div class="custom-control custom-checkbox"> 
                                                <input class="custom-control-input" v-model="match_string" type="checkbox" id="match_string">
                                                <label class="custom-control-label" for="match_string">URL</label>
                                            </div>
                                        </a>
                                        <a class="dropdown-item d-flex" href="javascript:void(0);">
                                            <div class="custom-control custom-checkbox"> 
                                                <input class="custom-control-input" v-model="list_type" type="checkbox" id="list_type">
                                                <label class="custom-control-label" for="list_type">List Type</label>
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
                            <th class="text-center" v-if="match_string">URL</th>
                            <th class="text-center" v-if="list_type">List Type</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody v-if="show">
                        <tr v-for="(value,index) in named_list_entry.data" v-bind:key="index">
                            <td class="text-center" v-if="match_string"> {{ value.match_string }}</td>
                            <td class="text-center" v-if="list_type">{{ value.operator_code }}</td>
                            <td class="text-center">
                                <button type="button" @click="sendInfo(value.rule_id)" data-toggle="modal" data-target="#delete-list" class="btn">
                                    <i style="margin-top: 1px; color: red;" class="fa fa-trash"></i>
                                </button>
                                <!-- Delete List Modal -->
                                <div class="modal custom-modal fade" id="delete-list" role="dialog">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <div class="form-header">
                                                    <h3>Delete List</h3>
                                                    <p>Are you sure want to delete?</p>
                                                </div>
                                                <div class="modal-btn delete-action">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <a href="javascript:void(0);" @click="destroy(selected_list_id)" class="btn btn-primary continue-btn">Delete</a>
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
                                <!-- /Delete List Modal -->
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center" style="margin-top: 15px;" v-if="!isLoading && !show">
                    <h4>Oops! No URL Lists Found</h4>
                </div>
                <pagination :pageData="named_list_entry"></pagination>
            </div>
        </div>
        <add-url :url="url"></add-url>
    </div>
</template>
<script>
import Pagination  from '../../../pagination/pagination.vue'
import { EventBus } from "../../../../vue-asset";
import AddUrl from './AddUrl.vue';

export default {

    props: ['url'],

    components: { Pagination, AddUrl },
    data() {
        return {
            named_list_entry: [],

            list_type: true,
            match_string: true,
            block_page: true,
            when_changed: true,

            selected_list_id: '',
            listtype: '',
            listurl: '',
            
            isLoading: false,
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
        this.get_named_list_entry();
        EventBus.$on("url-list-added", function() {
            _this.get_named_list_entry();
        });
    },

    mounted() {
        
    },
    
    methods: {
        
        get_named_list_entry(page = 1) {
            this.isLoading = true;
            axios
                .get(
                base_url +
                    "policy/named-list-entry/" + this.url.list_id + "?page="+
                    page+
                    "&operator_code=" +
                    this.listtype +
                    "&match_string=" +
                    this.listurl
                )
                .then(response => {
                    this.named_list_entry = response.data
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
            vm.get_named_list_entry(pageNo);
        },
        
        showMessage(data) {
            if (data.status  == "success") {
                this.$toast.success(data.message, 'Success Alert', this.notificationSystem.options.success );
            } else {
                this.$toast.error(data.message, "Error Alert", this.notificationSystem.options.error);
            }
        },

        sendInfo(id) {
            this.selected_list_id = id;
        },

        destroy(id) {
            axios.delete(base_url + "policy/delete-policy-rule/" + id)

            .then(response => {
                EventBus.$emit("policy-rules-added");
                $('#delete-rule').modal('hide');
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
            return this.named_list_entry.data ? (this.named_list_entry.data.length >= 1 ? true: false) : null
        },
    }
    
}
</script>