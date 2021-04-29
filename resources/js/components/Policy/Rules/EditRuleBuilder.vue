<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Rule Builder</h4>
                </div>
                <b-overlay
                    id="overlay-background"
                    :show="show"
                    :variant="variant"
                    :opacity="opacity"
                    :blur="blur"
                    rounded="sm"
                    >
                    <div class="card-body" style="background: rgba(250, 240, 210, 0.5);" id="builder-header">
                        <ul class="tree mt-2">
                            <li>
                                <button style="margin-left: -6px;" class="btn btn-sm btn-primary">If</button>
                                <ul>
                                    <li style="margin-left: 18px;">
                                        <div id="builder" style="margin-top: -20px; margin-left: 11px;"></div>
                                    </li>
                                    <li id="leftclass">
                                        <select v-model="if_action" class="form-control" style="width: 30%; margin-left: 31px; margin-top: -20px;">
                                            <option value="Test Action">Test Action</option>
                                        </select>
                                    </li>
                                </ul>
                            </li>
                            <li><button style="margin-left: -6px;" class="btn btn-sm btn-primary">Else</button>
                                <ul>
                                    <li id="btnclass2">
                                        <select v-model="else_action" class="form-control" style="width: 30%; margin-left: 31px; margin-top: -20px;">
                                            <option value="Test Action">Test Action</option>
                                        </select>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </b-overlay>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <validation-observer ref="observer" v-slot="{ handleSubmit }">
                    <b-form @submit.stop.prevent="handleSubmit(updateRule)">
                        <validation-provider
                            name="Name"
                            :rules="{ required: true, min: 3 }"
                            v-slot="validationContext"
                            >
                            <b-form-group id="example-input-group-1" label="Rule Name" label-for="example-input-1">
                                <b-form-input
                                    id="example-input-1"
                                    name="example-input-1"
                                    v-model="rule_data.rule_name"
                                    :state="getValidationState(validationContext)"
                                    aria-describedby="input-1-live-feedback"
                                ></b-form-input>

                                <b-form-invalid-feedback id="input-1-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>

                        <validation-provider
                            name="Action"
                            :rules="{ required: true, min: 3 }"
                            v-slot="validationContext"
                            >
                            <b-form-group id="example-input-group-2" label="Rule Action" label-for="example-input-2">
                                <b-form-select 
                                v-model="rule_data.match_action" 
                                :options="options"
                                id="example-input-2"
                                name="example-input-2"
                                :state="getValidationState(validationContext)"
                                aria-describedby="input-2-live-feedback"
                                >
                                </b-form-select>

                                <b-form-invalid-feedback id="input-2-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>
                        
                        <div class="form-group">
                            <div class="custom-control custom-checkbox"> 
                                <input class="custom-control-input" v-model="rule_data.immediate_flag" type="checkbox" value="" id="checkboxSelectAll">
                                <label class="custom-control-label" for="checkboxSelectAll">Exit On First Match</label>
                            </div>
                        </div>

                       <div style="text-align: end;">
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
    </div>
</template>

<script>
export default {
    props: ['rule'],
    data() {
        return {
            rule_data: {
                rule_name: '',
                match_action: '',
                match_conditions: null,
                immediate_flag: ''
            },
            if_action: '',
            else_action: '',
            triggers: [],
            operators: [],
            options: [
                    { value: null, text: 'Please select an option' },
                    { value: 'Allow', text: 'Allow' },
                    { value: 'Deny', text: 'Deny' },
                    { value: 'Deny Page', text: 'Deny Page' },
                    { value: 'Filter', text: 'Filter' }
            ],
            filters: [
                    /*
                    * string with separator
                    */
                    {
                        id: 'name',
                        field: 'username',
                        label: {
                            en: 'Name',
                            fr: 'Nom'
                        },
                        icon: 'fa fa-user',
                        value_separator: ',',
                        type: 'string',
                        size: 50,
                        validation: {
                            allow_empty_value: true
                        },
                        unique: true
                    },
                    /*
                    * integer with separator for 'in' and 'not_in'
                    */
                    {
                        id: 'age',
                        label: 'Age',
                        icon: 'glyphicon glyphicon-calendar',
                        type: 'integer',
                        input: 'text',
                        value_separator: '|',
                        description: function(rule) {
                            if (rule.operator && ['in', 'not_in'].indexOf(rule.operator.type) !== -1) {
                                return 'Use a pipe (|) to separate multiple values with "in" and "not in" operators';
                            }
                        }
                    },    
            ],
            operator_filters: [],
            saving: false,

            variant: 'light',
            opacity: 0.85,
            blur: '2px',
            variants: [
                'transparent',
                'white',
                'light',
                'dark',
                'primary',
                'secondary',
                'success',
                'danger',
                'warning',
                'info',
            ],
            blurs: [
                { text: 'None', value: '' },
                '1px',
                '2px',
                '5px',
                '0.5em',
                '1rem'
            ],
            show: true,

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
        this.rule_data = {
            rule_name: this.rule.rule_name,
            match_action: this.rule.match_action,
            immediate_flag: this.rule.immediate_flag
        }
        this.if_action = JSON.parse(this.rule.match_conditions).if.action
        this.else_action = JSON.parse(this.rule.match_conditions).else.action
        this.getOperators()
        // console.log(JSON.parse(this.rule.match_conditions).if.action)
    },

    created() {
            
    },

    methods: {

        loadQueryBuilder() {
            $('[data-toggle="tooltip"]').tooltip();
            var $b = $('#builder');

            var options = {
                allow_empty: false,

                // default_filter: 'name',
                sort_filters: true,
                display_errors: true,
                display_empty_filter: false,

                optgroups: {
                    core: {
                        en: 'Core',
                        fr: 'Coeur'
                    }
                },

                icons: {
                    add_group: 'fas fa-plus-square',
                    add_rule: 'fas fa-plus-circle',
                    remove_group: 'fas fa-minus-square',
                    remove_rule: 'fas fa-minus-circle',
                    error: 'fas fa-exclamation-triangle'
                },

                plugins: {
                    'bt-tooltip-errors': {
                        delay: 100
                    },
                    'sortable': null,
                    'filter-description': {
                        mode: 'bootbox'
                    },
                    // 'bt-selectpicker': {liveSearch:true},
                    'chosen-selectpicker': null,
                    'unique-filter': null,
                    'bt-checkbox': {
                        color: 'primary'
                    },
                    // 'invert': null,
                    // 'not-group': null
                },
                // plugins: [
                //     'sortable',
                //     // 'filter-description',
                //     'unique-filter',
                //     'bt-tooltip-errors',
                //     'chosen-selectpicker',
                //     'bt-checkbox'
                //     // 'invert',
                //     // 'not-group'
                // ],

                // standard operators in custom optgroups
                
                // operators: this.operator_filters,

                filters: this.filters
            };

            // init
            $('#builder').queryBuilder(options);
            $('#builder').queryBuilder('setFilters', true, this.filters);
            $('#builder').queryBuilder('setRules', JSON.parse(this.rule.match_conditions).if.result);
            $('select').addClass('form-control')
            this.show = false
            $('#btn-get').on('click', function() {
                var result = $('#builder').queryBuilder('getRules');

                if (!$.isEmptyObject(result)) {
                    console.log(JSON.stringify(result, null, 2))
                }
            });

            $('#btn-get-sql').on('click', function() {
                var result = $('#builder').queryBuilder('getSQL', 'question_mark');

                if (result.sql.length) {
                    alert(result.sql + '\n\n' + JSON.stringify(result.params, null, 2));
                }
            });
        },

        //Form Validation
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        resetForm() {
            this.rule_data = {
                rule_name: ''
            };

            this.$nextTick(() => {
                this.$refs.observer.reset();
            });
        }, 

        getOperators() {
            axios.get(base_url + 'policy/get-operators').then(response => {
                this.operators = response.data
                this.getTriggers()     
            })
        },

        getTriggers() {
            axios.get(base_url + 'policy/get-triggers').then(response => {
                this.triggers = response.data
                this.setTriggers()
            })
        },

        setTriggers() {
            var arrayAllTriggers = [];
            this.triggers.forEach(element => {
                var valueToPush = {};
                
                valueToPush["id"]               = element.trigger_label;
                valueToPush["field"]            = element.trigger_label;
                valueToPush["label"]            = element.trigger_label;
                valueToPush['value_separator']  =  ',';
                valueToPush['type']             = 'string';
                valueToPush['size']             = 50;
                valueToPush['validation']       = {
                                                    allow_empty_value: true
                                                };
                valueToPush['unique']           = true
                valueToPush['operators']        = ['equal', 'not_equal', 'in', 'not_in', 'is_null', 'is_not_null']
                
                arrayAllTriggers.push(valueToPush);
            });
            this.filters = arrayAllTriggers;

            var arrayAllOperators = [];
            this.operators.forEach(element => {
                var valueToPush = {};
                
                valueToPush['type']     = element.operator_label;
                valueToPush['optgroup'] = 'custom';
                valueToPush['nb_inputs']= 1;
                
                arrayAllOperators.push(valueToPush);
            });
            // this.operator_filters = arrayAllOperators;
            this.loadQueryBuilder()
        },

        //Add Rule
        updateRule() {
            alert('still working on it...')
            var result = $('#builder').queryBuilder('getRules');
            if (!$.isEmptyObject(result)) {
                this.rule.match_conditions = JSON.stringify(result, null, 2)
            }
            this.saving = true
            axios
            .post(base_url + "policy/rule", this.rule)

            .then(response => {
                this.saving = false
                this.showMessage(response.data);
                this.rule = {
                    rule_name: ''
                };
                window.location.href = base_url + 'policy/rules'
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

<style scoped>
    ul.tree, ul.tree ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    ul.tree ul {
        margin-left: 10px;
    }

    ul.tree li {
        margin: 0;
        padding: 0 7px;
        line-height: 20px;
        color: #369;
        font-weight: bold;
        border-left: 1px solid rgb(100, 100, 100);

    }

    ul.tree li:last-child {
        border-left: none;
    }

    ul.tree li:before {
        position: relative;
        top: -0.3em;
        height: 2em;
        width: 20px;
        color: white;
        border-bottom: 1px solid rgb(100, 100, 100);
        content: "";
        display: inline-block;
        left: -7px;
    }

    #leftclass::before {
        left: 11px;    
    }

    #btnclass2::before {
        left: 11px;    
    }

    ul.tree li:last-child:before {
        border-left: 1px solid rgb(100, 100, 100);
    }
    
</style>