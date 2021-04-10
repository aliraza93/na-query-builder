<template>
    <div class="container">
        <div class="row">
            <add-operand></add-operand>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="card-header border-bottom p-1">
                            <div class="head-label">
                                <h6 class="mb-0">Query Builder Operands</h6>
                            </div>
                            <div class="dt-action-buttons text-right">
                                <div class="dt-buttons flex-wrap d-inline-flex">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mx-0 row">
                            <div class="col-sm-12 col-md-6">
                                <div class="dataTables_filter mt-1" style="float: left;">
                                    <input type="search" class="form-control" placeholder="Search Type....." v-model="name" v-on:keyup="get_operands()" aria-controls="DataTables_Table_0">
                                    <input type="search" class="form-control" placeholder="Search OptGroup....." v-model="name" v-on:keyup="get_operands()" aria-controls="DataTables_Table_0">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="row d-flex" style="float: right;">
                                    <div class="col-md-8">
                                        <button style="margin-top: 7px;" class="btn btn-primary" data-toggle="modal" data-target="#add-operand">Add Operand</button>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="ropdown dropdown-user" style="float: right;">
                                            <button class="btn dropdown-toggle dropdown-user-link" id="dropdown-user" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-bars" style="font-size: 20px;"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" id="showMenu" aria-labelledby="dropdown-user">
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="count" type="checkbox" id="count">
                                                        <label class="custom-control-label" for="count">Operators Count</label>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="operator_type" type="checkbox" id="operator_type">
                                                        <label class="custom-control-label" for="operator_type">Type</label>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="optGroup" type="checkbox" id="optGroup">
                                                        <label class="custom-control-label" for="optGroup">OptGroup</label>
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
                                    <th v-if="count">#</th>
                                    <th class="text-center" v-if="operator_type">Type</th>
                                    <th class="text-center" v-if="optGroup"> OptGroup</th>
                                </tr>
                            </thead>
                            <tbody v-if="show">
                                <tr v-for="(value,index) in operators.data" v-bind:key="index">
                                    <td v-if="count">{{ index + 1 }}</td>
                                    <td class="text-center" v-if="operator_type">{{ value.type }}</td>
                                    <td class="text-center" v-if="optGroup">{{ value.optGroup }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-center" style="margin-top: 15px;" v-if="!isLoading && !show">
                            <h4>Oops! No Operands Found</h4>
                        </div>
                        <pagination :pageData="operators"></pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Pagination  from '../../pagination/pagination.vue';
import { EventBus } from "../../../vue-asset";
import AddOperand from './AddOperand.vue'

export default {
    components: {
        Pagination,
        AddOperand
    },
    data() {
        return {
            operators: [],

            count: true,
            operator_type: true,
            optGroup: true,

            name: '',
            isLoading: false,
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
        this.get_operands();
        EventBus.$on("operators-list", function() {
            _this.get_operands();
        });
    },

    mounted() {
        
    },
    
    methods: {

        //Get All Operators
        get_operands(page = 1) {
            this.isLoading = true;
            axios
                .get(
                base_url +
                    "query-builder/operators-list?page="+
                    page+
                    "&name=" +
                    this.name
                )
                .then(response => {
                    this.operators = response.data
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
            vm.get_operands(pageNo);
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
            return this.operators.data ? (this.operators.data.length >= 1 ? true: false) : null
        },
    }
}
</script>
