<template>
    <!-- Modal -->
    <div class="modal fade" id="user-details" tabindex="-1" role="dialog" aria-labelledby="user-detailsTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title text-center" id="exampleModalCenterTitle">Container Details</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th scope="row">ID</th>
                                            <td>24</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">id</th>
                                            <td>f4656fgf56465</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Display Name</th>
                                            <td colspan="2">Test-PC</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Distinguished Name</th>
                                            <td>CN=1vvfhgfhg</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Account Name</th>
                                            <td>Test Account Name</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Last Login</th>
                                            <td colspan="2">2021-12-03</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Operating System</th>
                                            <td>WIndows 10</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Service Pack</th>
                                            <td colspan="2">Service Pack 1</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Operating System Version</th>
                                            <td>6.1</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">When Created</th>
                                            <td>2021-10-06</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">When Changed</th>
                                            <td colspan="2">2021-12-03</td>
                                        </tr>
                                    </tbody>
                                </table>
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
import { EventBus } from "../../../vue-asset";

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
                    name: response.data.user_name,
                    given_name: response.data.user_name,
                    account_name: response.data.user_name,
                    office_name: response.data.user_name,
                    telephone_number: '',
                    email: response.data.email,
                    department: '',
                    distinguished_name: 'DS ' + response.data.user_name,
                    last_login: response.data.when_created,
                    login_count: 5,
                    user_prinicpal_name: 'Principal ' + response.data.user_name,
                    active: true,
                    when_created: response.data.when_created,
                    when_changed: response.data.when_updated
                };
            })
        },
    },
}
</script>