@isset($pageConfigs)
    {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset

<!DOCTYPE html>
{{-- {!! Helper::applClasses() !!} --}}
@php
    $configData = Helper::applClasses();
@endphp

<html lang="@if(session()->has('locale')){{session()->get('locale')}}@else{{$configData['defaultLanguage']}}@endif" data-textdirection="{{ env('MIX_CONTENT_DIRECTION') === 'rtl' ? 'rtl' : 'ltr' }}" class="{{ ($configData['theme'] === 'light') ? '' : $configData['layoutTheme'] }}">
<head>
    <!-- ========== Meta Tags ========== -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sasoft - Software Landing Page">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- ========== Page Title ========== -->
    <title>Rule Builder - NA Query Builder</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('images/logo/favicon.ico')}}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">
    {{-- {!! Helper::applClasses() !!} --}}
    @php $configData = Helper::applClasses(); @endphp

    {{-- Page Styles --}}
    @if($configData['mainLayoutType'] === 'horizontal')
    <link rel="stylesheet" href="{{ asset('css/base/core/menu/menu-types/horizontal-menu.css') }}" />
    @endif
    <link rel="stylesheet" href="{{ asset('css/base/core/menu/menu-types/vertical-menu.css') }}" />
    {{-- vendor css files --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css"yy>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/awesome-bootstrap-checkbox/1.0.2/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/css/bootstrap-slider.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.bootstrap3.css">
    <link rel="stylesheet" href="{{ asset('vendors/css/forms/select/select2.min.css') }}">
    <link rel="stylesheet" href="http://mistic100.github.io/jQuery-QueryBuilder/assets/flags/flags.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jQuery-QueryBuilder/dist/css/query-builder.default.min.css">

</head>
<html>
<body class="horizontal-layout horizontal-menu {{$configData['horizontalMenuType']}} {{ $configData['showMenu'] === true ? '' : '1-column' }}
{{ $configData['blankPageClass'] }} {{ $configData['bodyClass'] }}
{{ $configData['footerType'] }}" data-menu="horizontal-menu" data-col="{{ $configData['showMenu'] === true ? '' : '1-column' }}" data-open="hover" data-layout="{{ ($configData['theme'] === 'light') ? '' : $configData['layoutTheme'] }}" style="{{ $configData['bodyStyle'] }}" data-framework="laravel" data-asset-path="{{ asset('/')}}">
    <!-- BEGIN: Content-->
    <div style="margin-top: 10px;">
        @if(($configData['contentLayout']!=='default') && isset($configData['contentLayout']))
        <div class="content-area-wrapper {{ $configData['layoutWidth'] === 'boxed' ? 'container p-0' : '' }}">
            <div class="{{ $configData['sidebarPositionClass'] }}">
                <div class="sidebar">
                    {{-- Include Sidebar Content --}}
                    @yield('content-sidebar')
                </div>
            </div>
            <div class="{{ $configData['contentsidebarClass'] }}">
                <div class="content-wrapper">
                    <div class="content-body">
                        {{-- Include Page Content --}}
                        @include('content.policy.rules._content')
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="content-wrapper {{ $configData['layoutWidth'] === 'boxed' ? 'container p-0' : '' }}">
            {{-- Include Breadcrumb --}}
            @if($configData['pageHeader'] == true)
                @include('panels.breadcrumb')
            @endif

            <div class="content-body">

                {{-- Include Page Content --}}
                @include('content.policy.rules._content')

            </div>
        </div>
        @endif

    </div>
    <!-- End: Content-->

    {{-- Page js files --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/microplugin/0.0.3/microplugin.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-extendext@1.0.0/jquery-extendext.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dot/1.1.3/doT.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/selectize.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jQuery-QueryBuilder/dist/js/query-builder.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/selectize.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-extendext@1.0.0/jquery-extendext.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-sql-parser@1.4.1/dist/parser/sqlParser.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dot/1.1.3/doT.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/interactjs@1.10.11/dist/interact.min.js"></script>
    
    <script src="{{ asset('js/scripts/forms/form-select2.js') }}"></script>
    <!-- injector:js -->
    <script src="{{ asset('css/querybuilder/main.js') }}"></script>
    <script src="{{ asset('css/querybuilder/defaults.js') }}"></script>
    <script src="{{ asset('css/querybuilder/plugins.js') }}"></script>
    <script src="{{ asset('css/querybuilder/core.js') }}"></script>
    <script src="{{ asset('css/querybuilder/public.js') }}"></script>
    <script src="{{ asset('css/querybuilder/data.js') }}"></script>
    <script src="{{ asset('css/querybuilder/template.js') }}"></script>
    <script src="{{ asset('css/querybuilder/utils.js') }}"></script>
    <script src="{{ asset('css/querybuilder/model.js') }}"></script>
    <script src="{{ asset('css/querybuilder/jquery.js') }}"></script>
    <script src="{{ asset('css/querybuilder/plugins/bt-checkbox/plugin.js') }}"></script>
    <script src="{{ asset('css/querybuilder/plugins/bt-selectpicker/plugin.js') }}"></script>
    <script src="{{ asset('css/querybuilder/plugins/bt-tooltip-errors/plugin.js') }}"></script>
    <script src="{{ asset('css/querybuilder/plugins/change-filters/plugin.js') }}"></script>
    <script src="{{ asset('css/querybuilder/plugins/chosen-selectpicker/plugin.js') }}"></script>
    <script src="{{ asset('css/querybuilder/plugins/filter-description/plugin.js') }}"></script>
    <script src="{{ asset('css/querybuilder/plugins/invert/plugin.js') }}"></script>
    <script src="{{ asset('css/querybuilder/plugins/mongodb-support/plugin.js') }}"></script>
    <script src="{{ asset('css/querybuilder/plugins/not-group/plugin.js') }}"></script>
    <script src="{{ asset('css/querybuilder/plugins/sortable/plugin.js') }}"></script>
    <script src="{{ asset('css/querybuilder/plugins/sql-support/plugin.js') }}"></script>
    <script src="{{ asset('css/querybuilder/plugins/unique-filter/plugin.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jQuery-QueryBuilder@2.6.0/dist/i18n/query-builder.en.js"></script>
    <!-- endinjector -->

    <script>
        $(document).ready(function(){
            var rules_plugins = {
                condition: 'AND',
                rules: [{
                    id: 'name',
                    operator: 'equal',
                    value: 'Mistic'
                }, {
                    condition: 'OR',
                    rules: [{
                    id: 'category',
                    operator: 'in',
                    value: [1, 2]
                    }, {
                    id: 'in_stock',
                    operator: 'equal',
                    value: 0
                    }]
                }]
                };

                $('#builder').queryBuilder({
                plugins: [
                    'sortable',
                    // 'filter-description',
                    'unique-filter',
                    'bt-tooltip-errors',
                    'bt-selectpicker',
                    'bt-checkbox'
                    // 'invert',
                    // 'not-group'
                ],

                filters: [{
                    id: 'name',
                    label: 'Name',
                    type: 'string',
                    unique: true,
                    description: 'This filter is "unique", it can be used only once'
                }, {
                    id: 'category',
                    label: 'Category',
                    type: 'integer',
                    input: 'checkbox',
                    values: {
                    1: 'Books',
                    2: 'Movies',
                    3: 'Music',
                    4: 'Goodies'
                    },
                    color: 'primary',
                    description: 'This filter uses Awesome Bootstrap Checkboxes',
                    operators: ['equal', 'not_equal', 'in', 'not_in', 'is_null', 'is_not_null']
                }, {
                    id: 'in_stock',
                    label: 'In stock',
                    type: 'integer',
                    input: 'radio',
                    values: {
                    1: 'Yes',
                    0: 'No'
                    },
                    colors: {
                    1: 'success',
                    0: 'danger'
                    },
                    description: 'This filter also uses Awesome Bootstrap Checkboxes',
                    operators: ['equal']
                }, {
                    id: 'price',
                    label: 'Price',
                    type: 'double',
                    validation: {
                    min: 0,
                    step: 0.01
                    }
                }],

                rules: rules_plugins
                });

                $('#btn-reset').on('click', function() {
                $('#builder-plugins').queryBuilder('reset');
                });

                $('#btn-set').on('click', function() {
                $('#builder-plugins').queryBuilder('setRules', rules_plugins);
                });

                $('#btn-get').on('click', function() {
                    var result = $('#builder-plugins').queryBuilder('getRules');

                    if (!$.isEmptyObject(result)) {
                        alert(JSON.stringify(result, null, 2));
                    }
                });
            
        })
    </script>
</body>
</html>