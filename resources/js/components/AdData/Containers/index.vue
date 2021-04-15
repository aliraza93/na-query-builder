<template>
    <div class="container">
        <div class="row">
            <ad-data-computer-info></ad-data-computer-info>
            <ad-data-add-container></ad-data-add-container>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="card-header border-bottom p-1">
                            <div class="head-label">
                                <h6 class="mb-0">Ad Data Containers</h6>
                            </div>
                            <div class="dt-action-buttons text-right">
                                <div class="dt-buttons flex-wrap d-inline-flex">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mx-0 row">
                            <div class="col-sm-12 col-md-6 d-flex" style="margin-top: auto;">
                                <input type="text" class="form-control" placeholder="Type Common Name..." v-model="commonname" v-on:keyup="get_containers()">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="row d-flex" style="float: right;">
                                    <div class="col-md-8">
                                        <button style="margin-top: 7px;" class="btn btn-primary" data-toggle="modal" data-target="#add-container">Add Container</button>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="ropdown dropdown-user" style="float: right;">
                                            <button class="btn dropdown-toggle dropdown-user-link" id="dropdown-user" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-bars" style="font-size: 20px;"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" id="showMenu" aria-labelledby="dropdown-user">
                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="custom-control custom-checkbox"> 
                                                        <input class="custom-control-input" v-model="container_name" type="checkbox" id="container_name">
                                                        <label class="custom-control-label" for="container_name">Container Name</label>
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
                                    <th v-if="container_name">Container Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="show">
                                <tr v-for="(value,index) in ad_data_container.data" v-bind:key="index">
                                    <td v-if="container_name">{{ value.common_name }}</td>
                                    <td class="d-flex">
                                        <a :href="`computer/` + value.id" data-toggle="tooltip" type="button" @click="showContainer(value.id)" title="Go To Computer" class="btn">
                                            <i style="margin-top: 1px;" class="fa fa-eye"></i>
                                        </a>
                                        <button title="View Info" data-toggle="tooltip" class="btn" @click="view(value.id)">
                                            <i class="fa fa-info-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-center" style="margin-top: 15px;" v-if="!isLoading && !show">
                            <h4>Oops! No Containers Found</h4>
                        </div>
                        <pagination :pageData="ad_data_container"></pagination>
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
import AdDataComputerInfo from './AdDataContainerInfo.vue';
import AdDataAddContainer from './AdDataAddContainer.vue';

export default {
    components: {
        Pagination,
        MenuIcon,
        Close,
        AdDataComputerInfo,
        AdDataAddContainer
    },
    data() {
        return {
            ad_data_container: [],

            container_name: true,
            distinguished_name: true,
            when_created: true,
            when_changed: true,

            allSelected: false,
            selected: [],
            commonname: '',
            isLoading: false,
            ad_data_container_ids: [],
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
        this.get_containers();
        EventBus.$on("ad-data-users", function() {
            _this.get_containers();
        });
    },

    mounted() {
        
    },
    
    methods: {

        //Select all checkboxes
        selectAll() {
            this.ad_data_container_ids = [];
            if (!this.allSelected) {
                for (var user in this.ad_data_container.data) {
                    this.ad_data_container_ids.push(this.ad_data_container.data[user].id);
                }
            }
        },
        select: function() {
            this.allSelected = false;
        },

        //Get All Containers
        get_containers(page = 1) {
            this.isLoading = true;
            axios
                .get(
                base_url +
                    "ad-data/containers-list?page="+
                    page+
                    "&common_name=" +
                    this.commonname
                )
                .then(response => {
                    this.ad_data_container = response.data
                    this.isLoading = false;
                })
                .catch(err => {
                if (err.response) {
                    this.errors = err.response.data.errors;
                }
            });
        },

        //Show User Page
        // showContainer(id) {
        //     axios.get(base_url + 'ad-data/user/' + id).then(response => {})
        // },

        pageClicked(pageNo) {
            var vm = this;
            vm.get_containers(pageNo);
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
            return this.ad_data_container.data ? (this.ad_data_container.data.length >= 1 ? true: false) : null
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