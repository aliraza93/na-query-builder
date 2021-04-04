
@extends('layouts/contentLayoutMaster')

@section('title', 'Policies')

@section('vendor-style')
  {{-- vendor css files --}}
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css') }}">
@endsection

@section('content')

<!-- Basic table -->
<section id="proxy">
  <listeners></listeners>
</section>
@endsection

@section('page-script')
  {{-- Page js files --}}
  {{-- <script src="{{ asset('js/scripts/tables/table-datatables-basic.js') }}"></script> --}}
  <script type="text/javascript">
    var base_url = "{{ url('/').'/' }}";
</script>
<script type="text/javascript" src="{{ url('js/proxy.js') }}"></script>
@endsection
