<template>
    <!-- Modal -->
    <div class="modal fade text-left" id="add-policy-subnet" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add Subnet</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <validation-observer ref="observer" v-slot="{ handleSubmit }">
                    <b-form @submit.stop.prevent="handleSubmit(addSubnet)">
                        <div class="modal-body">
                            <validation-provider
                                name="Subnet"
                                :rules="{ required: true, min: 3 }"
                                v-slot="validationContext"
                                >
                                <b-form-group id="example-input-group-2" label="Subnet" label-for="example-input-2">
                                    <v-select :options="options"
                                        id="example-input-2"
                                        name="example-input-2"
                                        v-model="subnet.data"
                                        :state="getValidationState(validationContext)"
                                        aria-describedby="input-2-live-feedback">
                                    </v-select>
                                    <b-form-invalid-feedback id="input-2-live-feedback">{{ validationContext.errors[1] }}</b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        
                            <div class="form-group">
                                <div class="custom-control custom-checkbox"> 
                                    <input class="custom-control-input" v-model="subnet.enforced_flag" type="checkbox" value="" id="subnetid">
                                    <label class="custom-control-label" for="subnetid">Enforced</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <b-button variant="primary" :disabled="disableSubmitButton" type="submit" value="Submit">
                                {{saving ? "Submitting..." : "Submit"}}
                                <b-spinner v-if="saving" small type="grow"></b-spinner>
                            </b-button>
                            <b-button @click="resetForm()">Reset</b-button>
                        </div>
                    </b-form>
                </validation-observer>
            </div>
        </div>
    </div>
</template>
<script>
import { EventBus } from "../../../../../vue-asset";
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';

export default {

    props: ['policy'],

    components: {vSelect},
    data() {
        return {
            subnet: {
                data: '',
                enforced_flag: ''
            },
            options: [],
            subnets: [],
            saving: false,
            notificationSystem: {
                options: {
                    success: {
                        overlay: true,
                        position: "center",
                        timeout: 3000,
                        class: 'complete_notification'
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
            errors: null
        };
    },

    mounted() {
        this.getSubnets();
    },

    methods: {
        //Form Validation
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        resetForm() {
            this.subnet = {
                ou_name: ''
            };

            this.$nextTick(() => {
                this.$refs.observer.reset();
            });
        }, 

        //Add Subnet
        addSubnet() { 
            this.saving = true
            axios
            .post(base_url + "policy/add-policy-subnet/" + this.policy.policy_id, this.subnet)

            .then(response => {
                $("#add-policy-subnet").modal("hide");
                EventBus.$emit("policy-subnets-added");
                this.saving = false
                this.showMessage(response.data);
                this.subnet = {
                    rule_name: ''
                };
            })
            .catch(err => {
                if (err.response) {
                    this.saving = false
                    this.showMessage(err.response.data)
                }
            });
        },

        getSubnets() {
            axios.get(base_url + 'policy/get-subnets').then(response => {
                this.subnets = response.data
                var allSubnets=[];
                this.subnets.forEach(element => {
                    var valueToPush = {};
                    valueToPush["label"] = element.name;
                    
                    valueToPush["code"] = element.ts_id;
                    
                    allSubnets.push(valueToPush);
                });
                this.options = allSubnets;
            })
        },

        showMessage(data) {
            if (data.status  == "success") {
                this.$toast.success(data.message, 'Success Alert', this.notificationSystem.options.success );
            } else {
                this.$toast.error(data.message, "Error Alert", this.notificationSystem.options.error);
            }
        },
    },

    computed: {
        disableSubmitButton() {
            return this.saving ? true: false
        }
    },
};
</script>