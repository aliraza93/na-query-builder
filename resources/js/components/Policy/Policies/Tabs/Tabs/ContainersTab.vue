<template>
    <div class="tab-pane" id="containers" aria-labelledby="containers-tab" role="tabpanel">
        <div class="card">
            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="d-flex justify-content-between align-items-center mx-0 row">
                    <div class="col-sm-12 col-md-6 d-flex mb-1" style="margin-top: auto;">
                        
                    </div>
                    <div class="col-sm-12 col-md-6 mb-1">
                        <div class="row d-flex" style="float: right;">
                            <div class="col-md-8">
                                <button style="margin-top: 7px;" class="btn btn-primary" data-toggle="modal" data-target="#add-policy-container">Add Container</button>
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
                                                <input class="custom-control-input" v-model="container_name" type="checkbox" id="container_name">
                                                <label class="custom-control-label" for="container_name">Container</label>
                                            </div>
                                        </a>
                                        <a class="dropdown-item d-flex" href="javascript:void(0);">
                                            <div class="custom-control custom-checkbox"> 
                                                <input class="custom-control-input" v-model="enforced" type="checkbox" id="enforced">
                                                <label class="custom-control-label" for="enforced">Enforced</label>
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
                            <th class="text-center" v-if="container_name">Container</th>
                            <th class="text-center" v-if="enforced">Enforced</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody v-if="show">
                        <tr v-for="(value,index) in policy_containers" v-bind:key="index">
                            <td class="text-center" v-if="priority"> {{ value.priority_policy }}</td>
                            <td class="text-center" v-if="container_name">{{ value.contname.common_name }}</td>
                            <td class="text-center" v-if="enforced">{{ value.enforced_flag }}</td>
                            <td class="text-center">
                                <button type="button" @click="sendInfo(value.ts_id, value.policy_id)" data-toggle="modal" data-target="#delete-container" class="btn">
                                    <i style="margin-top: 1px; color: red;" class="fa fa-trash"></i>
                                </button>
                                <!-- Delete Container Modal -->
                                <div class="modal custom-modal fade" id="delete-container" role="dialog">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <div class="form-header">
                                                    <h3>Delete Container</h3>
                                                    <p>Are you sure want to delete?</p>
                                                </div>
                                                <div class="modal-btn delete-action">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <a href="javascript:void(0);" @click="destroy(selected_ts_id, selected_policy_id)" class="btn btn-primary continue-btn">Delete</a>
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
                                <!-- /Delete Container Modal -->
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center" style="margin-top: 15px;" v-if="!isLoading && !show">
                    <h4>Oops! No Policy Containers Found</h4>
                </div>
                <pagination :pageData="policy_containers"></pagination>
            </div>
        </div>
        <add-container :policy="policy"></add-container>
    </div>
</template>
<script>
import Pagination  from '../../../../pagination/pagination.vue'
import { EventBus } from "../../../../../vue-asset";
import AddContainer from './AddContainer.vue';

export default {

    props: ['policy'],

    components: { AddContainer, Pagination },
    data() {
        return {
            policy_containers: [],

            container_name: true,
            priority: true,
            enforced: true,

            selected_ts_id: '',
            selected_policy_id: '',

            rulename: '',
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
        this.get_policy_containers();
        EventBus.$on("policy-containers-added", function() {
            _this.get_policy_containers();
        });
    },

    mounted() {
        
    },
    
    methods: {

        get_policy_containers(page = 1) {
            this.isLoading = true;
            var arrayAllUsers = [];
            axios
                .get(
                base_url +
                    "policy/policy-data-list/" +this.policy.policy_id + "?page="+
                    page
                )
                .then(response => {
                    var data = response.data
                    data.data.forEach(element => {
                        if(element.contname) {
                            arrayAllUsers.push(element);
                        }
                    });
                    this.policy_containers = arrayAllUsers;
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
            vm.get_policy_containers(pageNo);
        },
        
        showMessage(data) {
            if (data.status  == "success") {
                this.$toast.success(data.message, 'Success Alert', this.notificationSystem.options.success );
            } else {
                this.$toast.error(data.message, "Error Alert", this.notificationSystem.options.error);
            }
        },

        sendInfo(ts_id, policy_id) {
            this.selected_ts_id = ts_id;
            this.selected_policy_id = policy_id
        },

        destroy(ts_id, policy_id) {
            axios.delete(base_url + "policy/delete-policy-data/" + ts_id + "/policy/" + policy_id)

            .then(response => {
                EventBus.$emit("policy-containers-added");
                $('#delete-container').modal('hide');
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
            return this.policy_containers ? (this.policy_containers.length >= 1 ? true: false) : null
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