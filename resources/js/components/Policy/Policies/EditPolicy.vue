<template>
    <!-- Modal -->
    <div class="modal fade" id="edit-policy" tabindex="-1" role="dialog" aria-labelledby="edit-policyTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title text-center" id="exampleModalCenterTitle">Computer Details</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label>Policy Name: </label>
                    <div class="form-group">
                        <input type="text" placeholder="Policy Name" class="form-control" />
                    </div>

                    <label>Description: </label>
                    <div class="form-group">
                        <select class="form-control">
                            <option value="Test">Test</option>
                            <option value="Test2">Test2</option>
                            <option value="Test3">Option3</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
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
       
        EventBus.$on('edit-policy',function(id){
            _this.user.id = id;
            // _this.getEditData(id);
            $('#edit-policy').modal('show');
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