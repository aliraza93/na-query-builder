
@extends('layouts/contentLayoutMaster')

@section('title', 'Reports')

@section('vendor-style')
  {{-- vendor css files --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jQuery-QueryBuilder/dist/css/query-builder.default.min.css">
  {{-- <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css') }}"> --}}
@endsection

@section('content')

<!-- Basic table -->
<section>
  <div class="row">
    <!-- Tabs with Icon starts -->
    <div class="col-xl-12 col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Reports</h4>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div id="builder"></div>
                </div>
              </div>
            </div>
        </div>
    </div>
    <!-- Tabs with Icon ends -->
</div>
</section>

@endsection

@section('page-script')
  {{-- Page js files --}}
  
  <script>
      $(document).ready(function(){
        var $b = $('#builder');

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

            // plugins: {
            //     'bt-tooltip-errors': {
            //         delay: 100
            //     },
            //     'sortable': null,
            //     'filter-description': {
            //         mode: 'bootbox'
            //     },
            //     'bt-selectpicker': null,
            //     //      'chosen-selectpicker': null,
            //     'unique-filter': null,
            //     'bt-checkbox': {
            //         color: 'primary'
            //     },
            //     'invert': null,
            //     'not-group': null
            // },

            // standard operators in custom optgroups
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
                    values: [{
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
                    multiple: true,
                    plugin: 'selectize',
                    plugin_config: {
                        valueField: 'id',
                        labelField: 'name',
                        searchField: 'name',
                        sortField: 'name',
                        options: [{
                                id: "AL",
                                name: "Alabama"
                            },
                            {
                                id: "AK",
                                name: "Alaska"
                            },
                            {
                                id: "AZ",
                                name: "Arizona"
                            },
                            {
                                id: "AR",
                                name: "Arkansas"
                            },
                            {
                                id: "CA",
                                name: "California"
                            },
                            {
                                id: "CO",
                                name: "Colorado"
                            },
                            {
                                id: "CT",
                                name: "Connecticut"
                            },
                            {
                                id: "DE",
                                name: "Delaware"
                            },
                            {
                                id: "DC",
                                name: "District of Columbia"
                            },
                            {
                                id: "FL",
                                name: "Florida"
                            },
                            {
                                id: "GA",
                                name: "Georgia"
                            },
                            {
                                id: "HI",
                                name: "Hawaii"
                            },
                            {
                                id: "ID",
                                name: "Idaho"
                            }
                        ]
                    },
                    valueSetter: function(rule, value) {
                        rule.$el.find('.rule-value-container select')[0].selectize.setValue(value);
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
                        return rule.$el.find('.rule-value-container [name$=_1]').val() +
                            '.' + rule.$el.find('.rule-value-container [name$=_2]').val();
                    },
                    valueSetter: function(rule, value) {
                        if (rule.operator.nb_inputs > 0) {
                            var val = value.split('.');

                            rule.$el.find('.rule-value-container [name$=_1]').val(val[0]).trigger('change');
                            rule.$el.find('.rule-value-container [name$=_2]').val(val[1]).trigger('change');
                        }
                    }
                }
            ]
        };

        // init
        $('#builder').queryBuilder(options);
      })

  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/microplugin/0.0.3/microplugin.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-extendext@1.0.0/jquery-extendext.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/dot/1.1.3/doT.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/selectize.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jQuery-QueryBuilder/dist/js/query-builder.min.js"></script>
  {{-- <script type="text/javascript">
    var base_url = "{{ url('/').'/' }}";
</script>
<script type="text/javascript" src="{{ url('js/policy.js') }}"></script> --}}
@endsection
