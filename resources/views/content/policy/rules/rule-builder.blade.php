
@extends('layouts/contentLayoutMaster')

@section('title', 'Rule Builder')

@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.5.0/chosen.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/awesome-bootstrap-checkbox/0.3.7/awesome-bootstrap-checkbox.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.0/css/bootstrap-slider.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.4/css/selectize.bootstrap3.min.css">
    <link rel="stylesheet" href="{{ asset('vendors/css/forms/select/select2.min.css') }}">
    <link rel="stylesheet" href="http://mistic100.github.io/jQuery-QueryBuilder/assets/flags/flags.css">
    <link rel="stylesheet" href="{{ asset('css/querybuilder/css/query-builder.default.css') }}">
@endsection
<style>
    .query-builder, .query-builder * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .rule-value-container select {
        width: 200px !important;
    }

    .rules-group-container {
        width: -webkit-fill-available;
        border: none !important;
    }

    .chosen-container {
        width: 200px !important;
    }
    
    .query-builder {
        font-family: sans-serif;
    }

    .query-builder .hide {
        display: none;
    }

    .query-builder .pull-right {
        float: right !important;
    }

    .query-builder .btn {
        text-transform: none;
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0px;
        font-size: 14px;
        font-weight: 400;
        line-height: 1.42857;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        touch-action: manipulation;
        cursor: pointer;
        user-select: none;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
        margin: 4px;
    }

    .query-builder .btn.focus, .query-builder .btn:focus, .query-builder .btn:hover {
        color: #333;
        text-decoration: none;
    }

    .query-builder .btn.active, .query-builder .btn:active {
        background-image: none;
        outline: 0px none;
        box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.125) inset;
    }

    .query-builder .btn-success {
        color: #FFF;
        background-color: #5CB85C;
        border-color: #4CAE4C;
    }

    .query-builder .btn-primary {
        color: #FFF;
        background-color: #337AB7;
        border-color: #2E6DA4;
    }

    .query-builder .btn-danger {
        color: #FFF;
        background-color: #D9534F;
        border-color: #D43F3A;
    }

    .query-builder .btn-success.active, .query-builder .btn-success.focus,
    .query-builder .btn-success:active, .query-builder .btn-success:focus,
    .query-builder .btn-success:hover {
        color: #FFF;
        background-color: #449D44;
        border-color: #398439;
    }

    .query-builder .btn-primary.active, .query-builder .btn-primary.focus,
    .query-builder .btn-primary:active, .query-builder .btn-primary:focus,
    .query-builder .btn-primary:hover {
        color: #FFF;
        background-color: #286090;
        border-color: #204D74;
    }

    .query-builder .btn-danger.active, .query-builder .btn-danger.focus,
    .query-builder .btn-danger:active, .query-builder .btn-danger:focus,
    .query-builder .btn-danger:hover {
        color: #FFF;
        background-color: #C9302C;
        border-color: #AC2925;
    }

    .query-builder .btn-group {
        position: relative;
        display: inline-block;
        vertical-align: middle;
    }

    .query-builder .btn-group > .btn {
        position: relative;
        float: left;
    }

    .query-builder .btn-group > .btn:first-child {
        margin-left: 0px;
    }

    .query-builder .btn-group > .btn:first-child:not(:last-child) {
        border-top-right-radius: 0px;
        border-bottom-right-radius: 0px;
    }

    .query-builder .btn-group > .btn:last-child:not(:first-child) {
        border-top-left-radius: 0px;
        border-bottom-left-radius: 0px;
    }

    .query-builder .btn-group .btn + .btn, .query-builder .btn-group .btn + .btn-group,
    .query-builder .btn-group .btn-group + .btn, .query-builder .btn-group .btn-group + .btn-group {
        margin-left: -1px;
    }

    .query-builder .btn-xs, .query-builder .btn-group-xs > .btn {
        padding: 1px 5px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
    }


</style>
@section('content')
<section id="policy">
    <rule-builder></rule-builder>
</section>
<section>
    
</section>

@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.5.0/chosen.jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.0/bootstrap-slider.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/microplugin/0.0.3/microplugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.4/js/selectize.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-extendext@1.0.0/jquery-extendext.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dot/1.1.3/doT.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/interactjs@1.3.3/dist/interact.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-sql-parser@1.4.1/dist/parser/sqlParser.min.js"></script>
    
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
    <script type="text/javascript">
        var base_url = "{{ url('/').'/' }}";
        $(function () {
            $('.rule-value-container').selectpicker();
        });
    </script>
    <script type="text/javascript" src="{{ url('js/policy.js') }}"></script>
@endsection
