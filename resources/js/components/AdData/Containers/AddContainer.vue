<template>
    <!-- Modal -->
    <div class="modal fade text-left" id="add-container" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add Container</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <validation-observer ref="observer" v-slot="{ handleSubmit }">
                    <b-form @submit.stop.prevent="handleSubmit(addContainer)">
                        <div class="modal-body">
                            <validation-provider
                                name="Container Name"
                                :rules="{ required: true, min: 3 }"
                                v-slot="validationContext"
                                >
                                <b-form-group id="example-input-group-1" label="Container Name" label-for="example-input-1">
                                    <b-form-input
                                    id="example-input-1"
                                    name="example-input-1"
                                    v-model="container.common_name"
                                    :state="getValidationState(validationContext)"
                                    aria-describedby="input-1-live-feedback"
                                    ></b-form-input>

                                    <b-form-invalid-feedback id="input-1-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>

                        </div>
                        <div class="modal-footer">
                            <!-- <b-button  :disabled="disableSubmitButton" type="submit" variant="primary" value="Submit">{{saving ? "Submitting..." : "Submit"}}</b-button>     -->
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
import { EventBus } from "../../../vue-asset";

export default {
  data() {
    return {
        container: {
          common_name: ''
        },
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

  methods: {
        //Form Validation
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        resetForm() {
            this.container = {
                common_name: ''
            };

            this.$nextTick(() => {
                this.$refs.observer.reset();
            });
        }, 

        //Add Block Container
        addContainer() {
            this.saving = true
            axios
            .post(base_url + "ad-data/container", this.container)

            .then(response => {
                $("#add-container").modal("hide");
                EventBus.$emit("containers-added");
                this.saving = false
                this.showMessage(response.data);
                this.container = {
                    common_name: ''
                };
            })
            .catch(err => {
                if (err.response) {
                    this.saving = false
                    this.showMessage(err.response.data)
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

    computed: {
        disableSubmitButton() {
            return this.saving ? true: false
        }
    },
};
</script>