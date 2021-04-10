<template>
    <!-- Modal -->
    <div class="modal fade text-left" id="add-operand" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add Operand</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="form-group">
                            <!-- <ValidationProvider name="field" rules="required" v-slot="{ errors }">
                                <input type="text" class="form-control" v-model="name">
                                <span>{{ errors[0] }}</span>
                            </ValidationProvider> -->
                            <label for="operator_type">Type: </label>
                            <input type="text" v-model="operator.type" id="operator_type" placeholder="Enter Type" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label for="operator_optGroup">OptGroup: </label>
                            <v-select :options="options" v-model="operator.optGroup"></v-select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button :disabled="disableSubmitButton" type="submit" class="btn btn-primary" value="Submit">{{saving ? "Submitting..." : "Submit"}}</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </form>
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
            name: "",
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
        }
    },

    methods: {
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

        resetForm(){
            this.operator = {
                type: "",
                optGroup: ""
            };
            this.errors = null; 
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