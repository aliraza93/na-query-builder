<template>
    <div class="tab-pane active" id="rules" aria-labelledby="rules-tab" role="tabpanel">
        <div class="card mt-2">
            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="d-flex justify-content-between align-items-center mx-0 row">
                    <div class="col-sm-12 col-md-6 d-flex" style="margin-top: auto;">
                        <input type="text" class="form-control" placeholder="Type Rule Name..." v-model="rulename" v-on:keyup="get_rules()">
                        <input type="text" class="form-control" placeholder="Type Policy Priority..." v-model="matchaction" v-on:keyup="get_rules()">
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="row d-flex" style="float: right;">
                            <div class="col-md-8">
                                <a href="rule-builder" style="margin-top: 7px;" class="btn btn-primary">Add Rules</a>
                            </div>
                            <div class="col-md-4">
                                <div class="ropdown dropdown-user" style="float: right;">
                                    <button class="btn dropdown-toggle dropdown-user-link" id="dropdown-user" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-bars" style="font-size: 20px;"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" id="showMenu" aria-labelledby="dropdown-user">
                                        <a class="dropdown-item d-flex" href="javascript:void(0);">
                                            <div class="custom-control custom-checkbox"> 
                                                <input class="custom-control-input" v-model="rule_name" type="checkbox" id="rule_name">
                                                <label class="custom-control-label" for="rule_name">Rule Name</label>
                                            </div>
                                        </a>
                                        <a class="dropdown-item d-flex" href="javascript:void(0);">
                                            <div class="custom-control custom-checkbox"> 
                                                <input class="custom-control-input" v-model="action" type="checkbox" id="action">
                                                <label class="custom-control-label" for="action">Action</label>
                                            </div>
                                        </a>
                                        <a class="dropdown-item d-flex" href="javascript:void(0);">
                                            <div class="custom-control custom-checkbox"> 
                                                <input class="custom-control-input" v-model="immediate_flag" type="checkbox" id="immediate_flag">
                                                <label class="custom-control-label" for="immediate_flag">Exit on First Match</label>
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
                            <th class="text-center" v-if="rule_name">Rule Name</th>
                            <th class="text-center" v-if="action">Action</th>
                            <th class="text-center" v-if="immediate_flag">Exit on First Match</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody v-if="show">
                        <tr v-for="(value,index) in rules.data" v-bind:key="index">
                            <td class="text-center" v-if="rule_name">{{ value.rule_name }}</td>
                            <td class="text-center" v-if="action">{{ value.match_action }}</td>
                            <td class="text-center" v-if="immediate_flag">{{ value.immediate_flag }}</td>
                            <td class="text-center">
                                <a :href="`rule-builder/` + value.rule_id" data-toggle="tooltip" title="Go To Rule" class="btn">
                                    <i style="margin-top: 1px;" class="fa fa-edit"></i>
                                </a>
                                <button type="button" @click="sendInfo(value.rule_id)" data-toggle="modal" data-target="#delete-page" class="btn">
                                    <i style="margin-top: 1px; color: red;" class="fa fa-trash"></i>
                                </button>
                                <!-- Delete Rule Modal -->
                                <div class="modal custom-modal fade" id="delete-page" role="dialog">
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
                <pagination :pageData="rules"></pagination>
            </div>
        </div>
        <!-- <add-rule></add-rule> -->
    </div>
</template>
<script>
import Pagination  from '../../../pagination/pagination.vue';
import { EventBus } from "../../../../vue-asset";

export default {
    components: {
        Pagination
    },
    data() {
        return {
            rules: [],

            rule_name: true,
            immediate_flag: true,
            action: true,
            
            rulename: '',
            matchaction: '',
            selected_rule_id: '',
            isLoading: false,
            rules_ids: [],
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
        this.get_rules();
        EventBus.$on("rules-added", function() {
            _this.get_rules();
        });
    },

    mounted() {
        
    },
    
    methods: {
        get_rules(page = 1) {
            this.isLoading = true;
            axios
                .get(
                base_url +
                    "policy/rules-list?page="+
                    page+
                    "&rule_name=" +
                    this.rulename +
                    "&match_action=" +
                    this.matchaction
                )
                .then(response => {
                    this.rules = response.data
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
            vm.get_rules(pageNo);
        },
        
        showMessage(data) {
            if (data.status  == "success") {
                this.$toast.success(data.message, 'Success Alert', this.notificationSystem.options.success );
            } else {
                this.$toast.error(data.message, "Error Alert", this.notificationSystem.options.error);
            }
        },

        sendInfo(id) {
            this.selected_rule_id = id;
        },

        destroy(id) {
            axios.delete(base_url + "policy/rule/" + id)

            .then(response => {
                EventBus.$emit("rules-added");
                $('#delete-rule').modal('hide');
                this.showMessage(response.data);
            })
            .catch(err => {
                if (err.response) {
                    this.showMessage(err.response.data)
                }
            });
            
        },
            
    },

    computed: {
        show() {
            return this.rules.data ? (this.rules.data.length >= 1 ? true: false) : null
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