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
                            <div class="col-sm-12 col-md-6 d-flex" style="margin-top: auto;">
                                <input type="text" class="form-control" placeholder="Type Policy Name..." v-model="policyname" v-on:keyup="get_policies()">
                                <input type="text" class="form-control" placeholder="Type Policy Priority..." v-model="policypriority" v-on:keyup="get_policies()">
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
                                    <td class="text-center" v-if="priority"> {{ value.priority }}</td>
                                    <td class="text-center" v-if="policy_name">{{ value.policy_name }}</td>
                                    <td class="text-center" v-if="block_page">{{ value.blockpage.title }}</td>
                                    <td class="text-center">
                                        <button data-toggle="tooltip" @click="editPolicy(value.policy_id)" title="Edit Policy" class="btn">
                                            <i style="margin-top: 1px;" class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" @click="sendInfo(value.policy_id)" data-toggle="modal" data-target="#delete-policy" class="btn">
                                            <i style="margin-top: 1px; color: red;" class="fa fa-trash"></i>
                                        </button>
                                        <button data-toggle="tooltip" @click="changePriority(value.policy_id, 'up')" title="Priority Up" class="btn">
                                            <i style="margin-top: 1px;" class="fa fa-arrow-up"></i>
                                        </button>
                                        <button data-toggle="tooltip" @click="changePriority(value.policy_id, 'down')" title="Priority Down" class="btn">
                                            <i style="margin-top: 1px;" class="fa fa-arrow-down"></i>
                                        </button>
                                        <a :href="`policy-details/` + value.policy_id" data-toggle="tooltip" title="Go To Policy" class="btn">
                                            <i style="margin-top: 1px;" class="fa fa-arrow-right"></i>
                                        </a>
                                        <!-- Delete Policy Modal -->
                                        <div class="modal custom-modal fade" id="delete-policy" role="dialog">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <div class="form-header">
                                                            <h3>Delete Policy</h3>
                                                            <p>Are you sure want to delete?</p>
                                                        </div>
                                                        <div class="modal-btn delete-action">
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <a href="javascript:void(0);" @click="destroy(selected_policy_id)" class="btn btn-primary continue-btn">Delete</a>
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
                                        <!-- /Delete Policy Modal -->
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
import { EventBus } from "../../../vue-asset";
import AddPolicy from './AddPolicy.vue';
import EditPolicy from './EditPolicy.vue';

export default {
    components: {
        Pagination,
        AddPolicy,
        EditPolicy
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
            selected_policy_id: '',
            policyname: '',
            policypriority: '',
            isLoading: false,
            policies_ids: [],
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
        this.get_policies();
        EventBus.$on("policies-added", function() {
            _this.get_policies();
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

        
        get_policies(page = 1) {
            this.isLoading = true;
            axios
                .get(
                base_url +
                    "policy/policies-list?page="+
                    page+
                    "&policy_name=" +
                    this.policyname +
                    "&priority=" +
                    this.policypriority
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

        pageClicked(pageNo) {
            var vm = this;
            vm.get_policies(pageNo);
        },
        
        showMessage(data) {
            if (data.status  == "success") {
                this.$toast.success(data.message, 'Success Alert', this.notificationSystem.options.success );
            } else {
                this.$toast.error(data.message, "Error Alert", this.notificationSystem.options.error);
            }
        },

        changePriority(id, action) {
            axios.post(base_url + 'policy/change-priority/'+ id + '/' + action)
            .then(response => {
                EventBus.$emit("policies-added");
            })
        },

        
        editPolicy(id) {
            EventBus.$emit("edit-policy", id);
        },

        sendInfo(id) {
            this.selected_policy_id = id;
        },

        destroy(id) {
            axios.delete(base_url + "policy/delete-policy/" + id)

            .then(response => {
                EventBus.$emit("policies-added");
                $('#delete-policy').modal('hide');
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