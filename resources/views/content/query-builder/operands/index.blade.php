
@extends('layouts/contentLayoutMaster')

@section('title', 'Triggers')

@section('vendor-style')
  {{-- vendor css files --}}
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css') }}">
@endsection

@section('content')

<!-- Basic table -->
<section id="query-builder">
  <querybuilder-operands></querybuilder-operands>
</section>
@endsection

@section('page-script')
  {{-- Page js files --}}
  
  <script type="text/javascript">
    var base_url = "{{ url('/').'/' }}";
</script>
<script type="text/javascript" src="{{ url('js/query-builder.js') }}"></script>
@endsection
