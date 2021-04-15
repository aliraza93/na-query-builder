<template>
    <!-- Modal -->
    <div class="modal fade text-left" id="edit-page" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Edit Page</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <validation-observer ref="observer" v-slot="{ handleSubmit }">
                    <b-form @submit.stop.prevent="handleSubmit(updateBlockPage)">
                        <div class="modal-body">
                            <validation-provider
                                name="Name"
                                :rules="{ required: true, min: 3 }"
                                v-slot="validationContext"
                                >
                                <b-form-group id="example-input-group-1" label="Page Name" label-for="example-input-1">
                                    <b-form-input
                                    id="example-input-1"
                                    name="example-input-1"
                                    v-model="block_page.title"
                                    :state="getValidationState(validationContext)"
                                    aria-describedby="input-1-live-feedback"
                                    ></b-form-input>

                                    <b-form-invalid-feedback id="input-1-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox"> 
                                    <input class="custom-control-input" v-model="block_page.default_page" type="checkbox" value="" id="checkboxSelectAll">
                                    <label class="custom-control-label" for="checkboxSelectAll">Default Page</label>
                                </div>
                            </div>

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

export default {

    data() {
        return {
            block_page: {
                id: '',
                title: '',
                default_page: '',
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
                        overlay: true,
                        position: "center",
                        zindex: 999,
                        timeout: 3000,
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
        // console.log('component called')
    },

    created() {
        var _this = this;
       
        EventBus.$on('edit-page',function(id){
            _this.block_page.id = id;
            _this.getEditData(id);
            $('#edit-page').modal('show');
        });
    },

    methods: {
        //Form Validation
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        resetForm() {
            this.block_page = {
                title: ''
            };

            this.$nextTick(() => {
                this.$refs.observer.reset();
            });
        }, 

        getEditData(id){
            axios.get(base_url+'policy/block-page/'+id+'/edit')
            .then(response => {
                this.block_page = {
                    id : response.data.block_page_id,
                    title: response.data.title,
                    default_page: response.data.default_page_flag
                };
            })
        },

        //Add Block Page
        updateBlockPage() {
            this.saving = true
            axios
            .post(base_url + "policy/block-page/" + this.block_page.id + "/update", this.block_page)

            .then(response => {
                $("#edit-page").modal("hide");
                EventBus.$emit("block-pages-added");
                this.saving = false
                this.showMessage(response.data);
                this.block_page = {
                    title: '',
                    default_page: ''
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
}
</script>