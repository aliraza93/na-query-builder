<template>
    <!-- Modal -->
    <div class="modal fade" id="user-details" tabindex="-1" role="dialog" aria-labelledby="user-detailsTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">User Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-1">ID</h5>
                                <h5 class="mb-1">Display Name</h5>
                                <h5 class="mb-1">Name</h5>
                                <h5 class="mb-1">Given Name</h5>
                                <h5 class="mb-1">Account Name</h5>
                                <h5 class="mb-1">Office Name</h5>
                                <h5 class="mb-1">Telephone Number</h5>
                                <h5 class="mb-1">Email</h5>
                                <h5 class="mb-1">Department</h5>
                                <h5 class="mb-1">Distinguished Name</h5>
                                <h5 class="mb-1">Last Login</h5>
                                <h5 class="mb-1">Login Count</h5>
                                <h5 class="mb-1">User Principal Name</h5>
                                <h5 class="mb-1">Active</h5>
                                <h5 class="mb-1">When Created</h5>
                                <h5 class="mb-1">When Changed</h5>
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-1">56</h5>
                                <h5 class="mb-1">Usman</h5>
                                <h5 class="mb-1">Yanky</h5>
                                <h5 class="mb-1">Usman</h5>
                                <h5 class="mb-1">Test</h5>
                                <h5 class="mb-1">jhkhk</h5>
                                <h5 class="mb-1">564545451</h5>
                                <h5 class="mb-1">usman@unial.com</h5>
                                <h5 class="mb-1">Test Department</h5>
                                <h5 class="mb-1">DS Usman</h5>
                                <h5 class="mb-1">2021-08-12</h5>
                                <h5 class="mb-1">45</h5>
                                <h5 class="mb-1">usman@unial.com</h5>
                                <h5 class="mb-1">true</h5>
                                <h5 class="mb-1">2021-02-12</h5>
                                <h5 class="mb-1">2021-11-12</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Accept</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { EventBus } from "../../vue-asset";

export default {

    data() {
    return {
        user: {
            id: '',
            display_name: "Joseph",
            name: "",
            given_name: "",
            account_name: '',
            office_name: '',
            telephone_number: '',
            email: '',
            department: '',
            distinguished_name: '',
            last_login: '',
            login_count: '',
            user_prinicpal_name: '',
            active: '',
            when_created: '',
            when_changed: ''
        },
        title: '',
        extras: '',
        status: false,
        errors: null,
        };
    },

    mounted() {
        console.log('component called')
    },

    created() {
        var _this = this;
       
        EventBus.$on('show-user-info',function(id){
            _this.user.id = id;
            // _this.getEditData(id);
            $('#user-details').modal('show');
        });
    },

    methods: {
        getEditData(id){
            axios.get(base_url+'booking/'+id+'/edit')
            .then(response => {
                this.user = {
                    id : response.data.id,
                    display_name: "Joseph",
                    name: response.data.name,
                    given_name: response.data.name,
                    account_name: response.data.name,
                    office_name: response.data.name,
                    telephone_number: '',
                    email: response.data.email,
                    department: '',
                    distinguished_name: 'DS ' + response.data.name,
                    last_login: response.data.created_at,
                    login_count: 5,
                    user_prinicpal_name: 'Principal ' + response.data.name,
                    active: true,
                    when_created: response.data.created_at,
                    when_changed: response.data.updated_at
                };
            })
        },
    },
}
</script>