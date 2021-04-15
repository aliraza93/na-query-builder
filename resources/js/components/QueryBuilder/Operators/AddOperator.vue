<template>
    <!-- Modal -->
    <div class="modal fade text-left" id="add-operator" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add Operator</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <validation-observer ref="observer" v-slot="{ handleSubmit }">
                    <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
                        <div class="modal-body">
                            <validation-provider
                            name="Name"
                            :rules="{ required: true, min: 3 }"
                            v-slot="validationContext"
                            >
                            <b-form-group id="example-input-group-1" label="Name" label-for="example-input-1">
                                <b-form-input
                                id="example-input-1"
                                name="example-input-1"
                                v-model="operator.type"
                                :state="getValidationState(validationContext)"
                                aria-describedby="input-1-live-feedback"
                                ></b-form-input>

                                <b-form-invalid-feedback id="input-1-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                            </b-form-group>
                            </validation-provider>

                            <validation-provider name="optGroup" :rules="{ required: true }" v-slot="validationContext">
                            <b-form-group id="example-input-group-2" label="optGroup" label-for="example-input-2">
                                <b-form-select
                                id="example-input-2"
                                name="example-input-2"
                                v-model="operator.optGroup"
                                :options="options"
                                :state="getValidationState(validationContext)"
                                aria-describedby="input-2-live-feedback"
                                ></b-form-select>
                                <b-form-invalid-feedback id="input-2-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                            </b-form-group>
                            </validation-provider>
                        </div>
                        <div class="modal-footer">
                            <b-button  :disabled="disableSubmitButton" type="submit" variant="primary" value="Submit">{{saving ? "Submitting..." : "Submit"}}</b-button>    
                            <b-button class="ml-2" @click="resetForm()">Reset</b-button>
                        </div>
                    </b-form>
                </validation-observer>
            </div>
        </div>
    </div>
</template>
<script>
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';
import "vue-select/src/scss/vue-select.scss";
import { EventBus } from "../../../vue-asset";

export default {
    components: {
        vSelect
    },
    data() {
        return {
            operator: {
                type: '',
                optGroup: ''
            },
            options: [
                'basic',
                'strings',
                'numbers'
            ],
            saving: false,
            errors: null,
            notificationSystem: {
                options: {
                    success: {
                        overlay: true,
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
        }
    },

    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        resetForm() {
            this.operator = {
                type: null,
                optGroup: null
            };

            this.$nextTick(() => {
                this.$refs.observer.reset();
            });
        },
        onSubmit() {
            this.$toast.success("Form Submitted Successfully!", 'Success Alert', this.notificationSystem.options.success );
        },
        addOperator() {
            this.saving = true
            axios
            .post(base_url + "query-builder/operato", this.operator)
            .then(response => {
                $("#add-operator").modal("hide");
                EventBus.$emit("operator-added");
                this.showMessage(response.data)
                this.resetForm();
                this.errors = null;
                this.saving = false
            })
            .catch(err => {
                if (err.response) {
                    this.showMessage(err.response.data)
                    this.saving = false
                }
            });
        },
        showMessage(data) {
            if (data.status  == "success") {
                this.$toast.success(data.message, 'Success Alert', this.notificationSystem.options.success );
            } else {
                this.$toast.error(data.message, "Error Alert", this.notificationSystem.options.error);
            }
        },

    },
    // end of method section
    computed: {
        disableSubmitButton() {
            return this.saving ? true: false
        }

    },
}
</script>