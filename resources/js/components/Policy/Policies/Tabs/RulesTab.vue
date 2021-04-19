<template>
    <div class="tab-pane active" id="rules" aria-labelledby="rules-tab" role="tabpanel">
        <div class="card">
            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="d-flex justify-content-between align-items-center mx-0 row">
                    <div class="col-sm-12 col-md-6 d-flex mb-1" style="margin-top: auto;">
                        <input type="text" class="form-control" placeholder="Type Rule Name..." v-model="rulename" v-on:keyup="get_policy_rules()">
                        <input type="text" class="form-control" placeholder="Type Rule Priority..." v-model="rulepriority" v-on:keyup="get_policy_rules()">
                    </div>
                    <div class="col-sm-12 col-md-6 mb-1">
                        <div class="row d-flex" style="float: right;">
                            <div class="col-md-8">
                                <button style="margin-top: 7px;" class="btn btn-primary" data-toggle="modal" data-target="#add-policy-rule">Add Rule</button>
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
                                                <input class="custom-control-input" v-model="rule_name" type="checkbox" id="rule_name">
                                                <label class="custom-control-label" for="rule_name">Rule Name</label>
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
                            <th class="text-center" v-if="rule_name">Rule Name</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody v-if="show">
                        <tr v-for="(value,index) in policies.data" v-bind:key="index">
                            <td class="text-center" v-if="priority"> {{ value.priority }}</td>
                            <td class="text-center" v-if="rule_name">{{ value.rulename.rule_name }}</td>
                            <td class="text-center">
                                <button type="button" @click="sendInfo(value.rule_id)" data-toggle="modal" data-target="#delete-rule" class="btn">
                                    <i style="margin-top: 1px; color: red;" class="fa fa-trash"></i>
                                </button>
                                <button data-toggle="tooltip" @click="changePriority(value.rule_id, 'up')" title="Priority Up" class="btn">
                                    <i style="margin-top: 1px;" class="fa fa-arrow-up"></i>
                                </button>
                                <button data-toggle="tooltip" @click="changePriority(value.rule_id, 'down')" title="Priority Down" class="btn">
                                    <i style="margin-top: 1px;" class="fa fa-arrow-down"></i>
                                </button>
                                <a :href="`rule-details/` + value.rule_id" data-toggle="tooltip" title="Go To Rule" class="btn">
                                    <i style="margin-top: 1px;" class="fa fa-arrow-right"></i>
                                </a>
                                <!-- Delete Rule Modal -->
                                <div class="modal custom-modal fade" id="delete-rule" role="dialog">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <div class="form-header">
                                                    <h3>Delete Rule</h3>
                                                    <p>Are you sure want to delete?</p>
                                                </div>
                                                <div class="modal-btn delete-action">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <a href="javascript:void(0);" @click="destroy(selected_rule_id)" class="btn btn-primary continue-btn">Delete</a>
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
                                <!-- /Delete Rule Modal -->
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center" style="margin-top: 15px;" v-if="!isLoading && !show">
                    <h4>Oops! No Rules Found</h4>
                </div>
                <pagination :pageData="policies"></pagination>
            </div>
        </div>
        <add-rule :policy="policy"></add-rule>
    </div>
</template>
<script>
import Pagination  from '../../../pagination/pagination.vue'
import { EventBus } from "../../../../vue-asset";
import AddRule from './AddRule.vue'

export default {

    props: ['policy'],

    components: { AddRule, Pagination },
    data() {
        return {
            policies: [],

            rule_name: true,
            priority: true,
            block_page: true,
            when_changed: true,

            allSelected: false,
            selected: [],
            selected_rule_id: '',
            rulename: '',
            rulepriority: '',
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
        this.get_policy_rules();
        EventBus.$on("policy-rules-added", function() {
            _this.get_policy_rules();
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

        
        get_policy_rules(page = 1) {
            this.isLoading = true;
            axios
                .get(
                base_url +
                    "policy/policy-rules-list?page="+
                    page+
                    "&rule_name=" +
                    this.rulename +
                    "&priority=" +
                    this.rulepriority
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
            vm.get_policy_rules(pageNo);
        },
        
        showMessage(data) {
            if (data.status  == "success") {
                this.$toast.success(data.message, 'Success Alert', this.notificationSystem.options.success );
            } else {
                this.$toast.error(data.message, "Error Alert", this.notificationSystem.options.error);
            }
        },

        changePriority(id, action) {
            axios.post(base_url + 'policy/change-policyrule-priority/'+ id + '/' + action)
            .then(response => {
                EventBus.$emit("policy-rules-added");
            })
        },

        sendInfo(id) {
            this.selected_rule_id = id;
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