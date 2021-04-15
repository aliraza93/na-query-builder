<template>
    <!-- Modal -->
    <div class="modal fade text-left" id="add-policy" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add Policy</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <validation-observer ref="observer" v-slot="{ handleSubmit }">
                    <b-form @submit.stop.prevent="handleSubmit(addPolicy)">
                        <div class="modal-body">
                            
                            <validation-provider
                                name="Policy Name"
                                :rules="{ required: true, min: 3 }"
                                v-slot="validationContext"
                                >
                                <b-form-group id="example-input-group-1" label="Policy Name" label-for="example-input-1">
                                    <b-form-input
                                    id="example-input-1"
                                    name="example-input-1"
                                    v-model="policy.policy_name"
                                    :state="getValidationState(validationContext)"
                                    aria-describedby="input-1-live-feedback"
                                    ></b-form-input>

                                    <b-form-invalid-feedback id="input-1-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                            <validation-provider
                                name="page"
                                :rules="{ required: true, min: 3 }"
                                v-slot="validationContext"
                                >
                                <b-form-group id="example-input-group-2" label="Block Page" label-for="example-input-2">
                                    <v-select :options="options"
                                        id="example-input-2"
                                        name="example-input-2"
                                        v-model="policy.block_pages"
                                        :state="getValidationState(validationContext)"
                                        aria-describedby="input-2-live-feedback">
                                    </v-select>
                                    <b-form-invalid-feedback id="input-2-live-feedback">{{ validationContext.errors[1] }}</b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </div>
                        <div class="modal-footer">
                            <b-button variant="primary" :disabled="disableSubmitButton" type="submit" value="Submit">
                                {{saving ? "Submitting..." : "Submit"}}
                                <b-spinner v-if="saving" small type="grow"></b-spinner>
                            </b-button>
                            <b-button class="ml-2" @click="resetForm()">Reset</b-button>
                        </div>
                    </b-form>
                </validation-observer>
            </div>
        </div>
    </div>
</template>
<script>
import { EventBus } from "../../../vue-asset";
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';

export default {
    components: {vSelect},
    data() {
        return {
            policy: {
                policy_name: '',
                block_page: '',
            },
            options: [],
            block_pages: [],
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
        this.getPages();
    },

    methods: {
        //Form Validation
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        resetForm() {
            this.policy = {
                policy_name: ''
            };

            this.$nextTick(() => {
                this.$refs.observer.reset();
            });
        }, 

        //Add Policy
        addPolicy() {
            this.saving = true
            axios
            .post(base_url + "policy/add-policy", this.policy)

            .then(response => {
                $("#add-policy").modal("hide");
                EventBus.$emit("policies-added");
                this.saving = false
                this.showMessage(response.data);
                this.policy = {
                    policy_name: ''
                };
            })
            .catch(err => {
                if (err.response) {
                    this.saving = false
                    this.showMessage(err.response.data)
                }
            });
        },

        getPages() {
            axios.get(base_url + 'policy/get-block-pages').then(response => {
                this.block_pages = response.data
                var arrayAllBusinessUsers=[];
                this.block_pages.forEach(element => {
                    var valueToPush = {};
                    valueToPush["label"] = element.title;
                    
                    valueToPush["code"] = element.block_page_id;
                    
                    arrayAllBusinessUsers.push(valueToPush);
                });
                this.options = arrayAllBusinessUsers;
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