<template>
    <div class="container">
        <div class="row">
            <!-- <ad-data-computer-info></ad-data-computer-info> -->
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="card-header border-bottom p-1">
                            <div class="head-label">
                                <h6 class="mb-0">Live Traffic Logs</h6>
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
                                        <button v-if="ad_data_computer_ids != ''" style="margin-top: 7px;" type="button" class="btn btn-primary">Add proxy User</button>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="ropdown dropdown-user" style="float: right;">
                                            <button class="btn dropdown-toggle dropdown-user-link" id="dropdown-user" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-bars" style="font-size: 20px;"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" id="showMenu" aria-labelledby="dropdown-user">
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="source_ip" type="checkbox" id="source_ip">
                                                        <label class="custom-control-label" for="source_ip">Source IP</label>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="traffic_source_name" type="checkbox" id="traffic_source_name">
                                                        <label class="custom-control-label" for="traffic_source_name">Traffic Source Name</label>
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
                                                        <input class="custom-control-input" v-model="rule_name" type="checkbox" id="rule_name">
                                                        <label class="custom-control-label" for="rule_name">Rule Name</label>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="policy_assignment" type="checkbox" id="policy_assignment">
                                                        <label class="custom-control-label" for="policy_assignment">Policy Assignment</label>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="nlp_response_time" type="checkbox" id="nlp_response_time">
                                                        <label class="custom-control-label" for="nlp_response_time">NLP Response time</label>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="nlp_top5" type="checkbox" id="nlp_top5">
                                                        <label class="custom-control-label" for="nlp_top5">NLP Top5</label>
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
                                    <th v-if="source_ip">Source IP</th>
                                    <th class="text-center" v-if="traffic_source_name">Traffic Source Name</th>
                                    <th class="text-center" v-if="policy_name">Policy Name</th>
                                    <th class="text-center" v-if="rule_name">Rule Name</th>
                                    <th class="text-center" v-if="policy_assignment">Policy Assignment</th>
                                    <th class="text-center" v-if="nlp_response_time">NLP Response Time</th>
                                    <th class="text-center" v-if="nlp_top5">NLP Top5</th>
                                </tr>
                            </thead>
                            <tbody v-if="show">
                                <tr v-for="(value,index) in ad_data_computer.data" v-bind:key="index">
                                    <td v-if="source_ip">192.168.1.5</td>
                                    <td class="text-center" v-if="traffic_source_name">{{ value.user_name }}</td>
                                    <td class="text-center" v-if="policy_name">Home</td>
                                    <td class="text-center" v-if="rule_name">Block All</td>
                                    <td class="text-center" v-if="policy_assignment">Home Level</td>
                                    <td class="text-center" v-if="nlp_response_time">3072</td>
                                    <td class="text-center" v-if="nlp_top5">Insurance 97%</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-center" style="margin-top: 15px;" v-if="!isLoading && !show">
                            <h4>Oops! No Logs Found</h4>
                        </div>
                        <pagination :pageData="ad_data_computer"></pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Pagination  from '../../pagination/pagination.vue';
import { EventBus } from "../../../vue-asset";

export default {
    components: {
        Pagination
    },
    data() {
        return {
            ad_data_computer: [],

            source_ip: true,
            traffic_source_name: true,
            policy_name: true,
            rule_name: true,
            policy_assignment: true,
            nlp_response_time: true,
            nlp_top5: true,

            allSelected: false,
            selected: [],
            name: '',
            isLoading: false,
            ad_data_computer_ids: [],
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
            this.ad_data_computer_ids = [];
            if (!this.allSelected) {
                for (var user in this.ad_data_computer.data) {
                    this.ad_data_computer_ids.push(this.ad_data_computer.data[user].id);
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
                    this.ad_data_computer = response.data
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
            // $('#basic-modals').modal('show');
            EventBus.$emit("show-user-info", id);
        },
            
    },

    computed: {
        show() {
            return this.ad_data_computer.data ? (this.ad_data_computer.data.length >= 1 ? true: false) : null
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