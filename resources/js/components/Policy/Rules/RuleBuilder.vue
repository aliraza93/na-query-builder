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
                        <b-form @submit.stop.prevent="handleSubmit(addRule)">
                            <validation-provider
                                name="Name"
                                :rules="{ required: true, min: 3 }"
                                v-slot="validationContext"
                                >
                                <b-form-group id="example-input-group-1" label="Rule Name" label-for="example-input-1">
                                    <b-form-input
                                        id="example-input-1"
                                        name="example-input-1"
                                        v-model="rule.rule_name"
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
                                    v-model="rule.match_action" 
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
                                    <input class="custom-control-input" v-model="rule.immediate_flag" type="checkbox" value="" id="checkboxSelectAll">
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
    data() {
        return {
            rule: {
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
            allOperators: [],
            filterOperators: [],
            db_all_operators: [],
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
        this.show = true
        this.getAllOperators()
        this.getOperators()
    },

    created() {
            
    },

    methods: {
        loadQueryBuilder() {
            $('[data-toggle="tooltip"]').tooltip();
            var $b = $('#builder');

            // var options = {
            //     allow_empty: false,

            //     // default_filter: 'name',
            //     sort_filters: true,
            //     display_errors: true,
            //     display_empty_filter: false,

            //     optgroups: {
            //         core: {
            //             en: 'Core',
            //             fr: 'Coeur'
            //         }
            //     },

            //     icons: {
            //         add_group: 'fas fa-plus-square',
            //         add_rule: 'fas fa-plus-circle',
            //         remove_group: 'fas fa-minus-square',
            //         remove_rule: 'fas fa-minus-circle',
            //         error: 'fas fa-exclamation-triangle'
            //     },

            //     plugins: {
            //         'bt-tooltip-errors': {
            //             delay: 100
            //         },
            //         'sortable': null,
            //         'filter-description': {
            //             mode: 'bootbox'
            //         },
            //         // 'bt-selectpicker': null,
            //         'chosen-selectpicker': null,
            //         'unique-filter': null,
            //         'bt-checkbox': {
            //             color: 'primary'
            //         },
            //         // 'invert': null,
            //         // 'not-group': null
            //     },
            //     // plugins: [
            //     //     'sortable',
            //     //     // 'filter-description',
            //     //     'unique-filter',
            //     //     'bt-tooltip-errors',
            //     //     'chosen-selectpicker',
            //     //     'bt-checkbox'
            //     //     // 'invert',
            //     //     // 'not-group'
            //     // ],

            //     // standard operators in custom optgroups
                
            //     // operators: this.operator_filters,

            //     filters: this.filters
            // };

            var options = {
                allow_empty: true,

                //default_filter: 'name',
                sort_filters: true,

                optgroups: {
                core: {
                    en: 'Core',
                    fr: 'Coeur'
                }
                },

                plugins: {
                'bt-tooltip-errors': { delay: 100 },
                'sortable': null,
                'filter-description': { mode: 'bootbox' },
                // 'bt-selectpicker': null,
                'chosen-selectpicker': null,
                'unique-filter': null,
                'bt-checkbox': { color: 'primary' },
                // 'invert': null,
                // 'not-group': null
                },

                // standard operators in custom optgroups
                // operators: this.allOperators,

                operators: [{
                        type: 'equal',
                        optgroup: 'basic'
                    },
                    {
                        type: 'not_equal',
                        optgroup: 'basic'
                    },
                    {
                        type: 'in',
                        optgroup: 'basic'
                    },
                    {
                        type: 'not_in',
                        optgroup: 'basic'
                    },
                    {
                        type: 'less',
                        optgroup: 'numbers'
                    },
                    {
                        type: 'less_or_equal',
                        optgroup: 'numbers'
                    },
                    {
                        type: 'greater',
                        optgroup: 'numbers'
                    },
                    {
                        type: 'greater_or_equal',
                        optgroup: 'numbers'
                    },
                    {
                        type: 'between',
                        optgroup: 'numbers'
                    },
                    {
                        type: 'not_between',
                        optgroup: 'numbers'
                    },
                    {
                        type: 'begins_with',
                        optgroup: 'strings'
                    },
                    {
                        type: 'not_begins_with',
                        optgroup: 'strings'
                    },
                    {
                        type: 'contains',
                        optgroup: 'strings'
                    },
                    {
                        type: 'not_contains',
                        optgroup: 'strings'
                    },
                    {
                        type: 'ends_with',
                        optgroup: 'strings'
                    },
                    {
                        type: 'not_ends_with',
                        optgroup: 'strings'
                    },
                    {
                        type: 'is_empty'
                    },
                    {
                        type: 'is_not_empty'
                    },
                    {
                        type: 'is_null'
                    },
                    {
                        type: 'is_not_null'
                    }
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
                        icon: 'glyphicon glyphicon-user',
                        value_separator: ',',
                        type: 'string',
                        optgroup: 'core',
                        default_value: 'Mistic',
                        size: 30,
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
                        optgroup: 'core',
                        description: function(rule) {
                        if (rule.operator && ['in', 'not_in'].indexOf(rule.operator.type) !== -1) {
                            return 'Use a pipe (|) to separate multiple values with "in" and "not in" operators';
                        }
                        }
                    },
                    /*
                    * textarea
                    */
                    {
                        id: 'bson',
                        label: 'BSON',
                        icon: 'glyphicon glyphicon-qrcode',
                        type: 'string',
                        input: 'textarea',
                        operators: ['equal'],
                        size: 30,
                        rows: 3
                    },
                    /*
                    * checkbox
                    */
                    {
                        id: 'category',
                        label: 'Category',
                        icon: 'glyphicon glyphicon-th-list',
                        type: 'integer',
                        input: 'checkbox',
                        optgroup: 'core',
                        values: {
                        1: 'Books',
                        2: 'Movies',
                        3: 'Music',
                        4: 'Tools',
                        5: 'Goodies',
                        6: 'Clothes'
                        },
                        colors: {
                        1: 'foo',
                        2: 'warning',
                        5: 'success'
                        },
                        operators: ['equal', 'not_equal', 'in', 'not_in', 'is_null', 'is_not_null'],
                        default_operator: 'in'
                    },
                    /*
                    * select
                    */
                    {
                        id: 'continent',
                        label: 'Continent',
                        icon: 'glyphicon glyphicon-globe',
                        type: 'string',
                        input: 'select',
                        optgroup: 'core',
                        placeholder: 'Select something',
                        values: [
                        {
                            label: 'Europe',
                            value: 'eur',
                            optgroup: 'North'
                        },
                        {
                            label: 'Asia',
                            value: 'asia',
                            optgroup: 'North'
                        },
                        {
                            label: 'Oceania',
                            value: 'oce',
                            optgroup: 'South'
                        },
                        {
                            label: 'Africa',
                            value: 'afr',
                            optgroup: 'South'
                        },
                        {
                            label: 'North America',
                            value: 'na',
                            optgroup: 'North'
                        },
                        {
                            label: 'South America',
                            value: 'sa',
                            optgroup: 'South'
                        },
                        {
                            label: 'Mordor',
                            value: 'mrd'
                        }
                        ],
                        operators: ['equal', 'not_equal', 'is_null', 'is_not_null']
                    },
                    /*
                    * Selectize
                    */
                    {
                        id: 'state',
                        label: 'State',
                        icon: 'glyphicon glyphicon-globe',
                        type: 'string',
                        input: 'select',
                        multiple: false,
                        plugin: 'selectize',
                        values: ['Value 1', 'Value 2', 'Value 3', 'Value 4'],
                        plugin_config: {
                        valueField: 'id',
                        labelField: 'name',
                        searchField: 'name',
                        sortField: 'name',
                        options: [
                            { id: "AL", name: "Alabama" },
                            { id: "AK", name: "Alaska" },
                            { id: "AZ", name: "Arizona" },
                            { id: "AR", name: "Arkansas" },
                            { id: "CA", name: "California" },
                            { id: "CO", name: "Colorado" },
                            { id: "CT", name: "Connecticut" },
                            { id: "DE", name: "Delaware" },
                            { id: "DC", name: "District of Columbia" },
                            { id: "FL", name: "Florida" },
                            { id: "GA", name: "Georgia" },
                            { id: "HI", name: "Hawaii" },
                            { id: "ID", name: "Idaho" }
                        ]
                        },
                        valueSetter: function(rule, value) {
                            rule.$el.target.find('.rule-value-container select')[0].selectize.setValue(value);
                        }
                    },
                    /*
                    * radio
                    */
                    {
                        id: 'in_stock',
                        label: 'In stock',
                        icon: 'glyphicon glyphicon-log-in',
                        type: 'integer',
                        input: 'radio',
                        optgroup: 'plugin',
                        values: {
                        1: 'Yes',
                        0: 'No'
                        },
                        operators: ['equal']
                    },
                    /*
                    * double
                    */
                    {
                        id: 'price',
                        label: 'Price',
                        icon: 'glyphicon glyphicon-usd',
                        type: 'double',
                        size: 5,
                        validation: {
                        min: 0,
                        step: 0.01
                        },
                        data: {
                        class: 'com.example.PriceTag'
                        }
                    },
                    /*
                    * slider
                    */
                    {
                        id: 'rate',
                        label: 'Rate',
                        icon: 'glyphicon glyphicon-flash',
                        type: 'integer',
                        validation: {
                        min: 0,
                        max: 100
                        },
                        plugin: 'slider',
                        plugin_config: {
                        min: 0,
                        max: 100,
                        value: 0
                        },
                        onAfterSetValue: function(rule, value) {
                        var input = rule.$el.find('.rule-value-container input');
                        input.slider('setValue', value);
                        input.val(value); // don't know why I need it
                        }
                    },
                    /*
                    * placeholder and regex validation
                    */
                    {
                        id: 'id',
                        label: 'Identifier',
                        icon: 'glyphicon glyphicon-sunglasses',
                        type: 'string',
                        optgroup: 'plugin',
                        placeholder: '____-____-____',
                        size: 14,
                        operators: ['equal', 'not_equal'],
                        validation: {
                        format: /^.{4}-.{4}-.{4}$/,
                        messages: {
                            format: 'Invalid format, expected: AAAA-AAAA-AAAA'
                        }
                        }
                    },
                    /*
                    * custom input
                    */
                    {
                        id: 'coord',
                        label: 'Coordinates',
                        icon: 'glyphicon glyphicon-star-empty',
                        type: 'string',
                        default_value: 'C.5',
                        description: 'The letter is the cadran identifier:\
                            <ul>\
                            <li><b>A</b>: alpha</li>\
                            <li><b>B</b>: beta</li>\
                            <li><b>C</b>: gamma</li>\
                            </ul>',
                        validation: {
                        format: /^[A-C]{1}.[1-6]{1}$/
                        },
                        input: function(rule, name) {
                        var $container = rule.$el.find('.rule-value-container');

                        $container.on('change', '[name=' + name + '_1]', function() {
                            var h = '';

                            switch ($(this).val()) {
                            case 'A':
                                h = '<option value="-1">-</option> <option value="1">1</option> <option value="2">2</option>';
                                break;
                            case 'B':
                                h = '<option value="-1">-</option> <option value="3">3</option> <option value="4">4</option>';
                                break;
                            case 'C':
                                h = '<option value="-1">-</option> <option value="5">5</option> <option value="6">6</option>';
                                break;
                            }

                            $container.find('[name$=_2]')
                            .html(h).toggle(!!h)
                            .val('-1').trigger('change');
                        });

                        return '\
                            <select name="' + name + '_1"> \
                            <option value="-1">-</option> \
                            <option value="A">A</option> \
                            <option value="B">B</option> \
                            <option value="C">C</option> \
                            </select> \
                            <select name="' + name + '_2" style="display:none;"></select>';
                        },
                        valueGetter: function(rule) {
                            return rule.$el.find('.rule-value-container [name$=_1]').val()
                                + '.' + rule.$el.find('.rule-value-container [name$=_2]').val();
                            },
                        valueSetter: function(rule, value) {
                        if (rule.operator.nb_inputs > 0) {
                            var val = value.split('.');

                            rule.$el.find('.rule-value-container [name$=_1]').val(val[0]).trigger('change');
                            rule.$el.find('.rule-value-container [name$=_2]').val(val[1]).trigger('change');
                        }
                    }
                }]
            };

            // init
            $('#builder').queryBuilder(options);
            // $('#builder').queryBuilder('setFilters', true, this.filters);
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
            this.rule = {
                rule_name: ''
            };

            this.$nextTick(() => {
                this.$refs.observer.reset();
            });
        }, 

        getOperators() {
            axios.get(base_url + 'policy/get-distinct-operators').then(response => {
                this.operators = response.data
                this.setOperators()
                this.getTriggers()     
            })
        },

        getAllOperators() {
            axios.get(base_url + 'policy/get-operators').then(response => {
                this.db_all_operators = response.data  
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
                var arrayAllOperators = [];
                this.db_all_operators.forEach(element1 => {
                    if(element1.input_code == element.input_code) {
                        var valueToPush = {};
                        valueToPush = element1.operator_label;
                        arrayAllOperators.push(valueToPush);
                    }
                })
                // console.log(arrayAllOperators)
                var valueToPush = {};
                
                valueToPush["id"]               = element.trigger_label;
                valueToPush["field"]            = element.trigger_label;
                valueToPush["label"]            = element.trigger_label;
                valueToPush['value_separator']  =  ',';
                valueToPush['type']             = 'string';
                valueToPush['input']            = 'select';
                valueToPush['placeholder']      = 'Select Something'
                valueToPush['values']           = ['Test Value', 'Test Value 2']
                valueToPush['size']             = 50;
                valueToPush['validation']       = {
                                                    allow_empty_value: true
                                                };
                valueToPush['unique']           = true
                // valueToPush['operators']        = this.showOperators(element.input_code);
                valueToPush['operators']        = arrayAllOperators
                
                arrayAllTriggers.push(valueToPush);
            });
            this.filters = arrayAllTriggers;
            
            this.loadQueryBuilder()
        },

        setOperators() {
            var arrayAllUsers = [];
            var arrayAllOperators = [];
            this.operators.forEach(element => {
                var valueToPush = {};
                valueToPush['type']     = element.operator_label;
                valueToPush['optgroup'] = 'custom';
                valueToPush['nb_inputs']= 1;
                valueToPush['multiple'] = false;
                valueToPush['apply_to'] = ['string']
                // type: 'geo', optgroup: 'custom', nb_inputs: 3, multiple: false, apply_to: ['number']
                arrayAllOperators.push(valueToPush);
            })
            // console.log(arrayAllOperators)
            this.allOperators =  arrayAllOperators;
        },

        showOperators(input_code) {
            console.log('test')
            var arrayAllOperators = [];
            this.operators.forEach(element => {
                if(element.input_code == input_code) {
                    var valueToPush = {};
                    valueToPush = element.operator_label;
                    arrayAllOperators.push(valueToPush);
                }
            })
            return arrayAllOperators
        },

        //Add Rule
        addRule() {
            var result = $('#builder').queryBuilder('getRules');
            
            var myJson = {
                if: {
                    result,
                    action: this.if_action
                },
                else: {
                    action: this.else_action
                }
            }

            if (!$.isEmptyObject(result)) {
                this.rule.match_conditions = JSON.stringify(myJson, null, 2)
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