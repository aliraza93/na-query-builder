
@extends('layouts/contentLayoutMaster')

@section('title', 'Organizational Units')

@section('vendor-style')
  {{-- vendor css files --}}
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css') }}">
@endsection

@section('content')

<!-- Basic table -->
<section id="ad-data">
  <organizational-units-list></organizational-units-list>
</section>
@endsection

@section('page-script')
  {{-- Page js files --}}
  
  <script type="text/javascript">
    var base_url = "{{ url('/').'/' }}";
</script>
<script type="text/javascript" src="{{ url('js/ad-data.js') }}"></script>
@endsection
