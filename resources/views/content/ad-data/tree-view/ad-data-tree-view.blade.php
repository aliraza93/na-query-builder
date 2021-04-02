@extends('layouts/contentLayoutMaster')

@section('title', 'Tree')

@section('vendor-style')
<!-- vendor css files -->
<link rel="stylesheet" href="{{ asset('fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection

@section('content')
<!-- Tree section -->
<section class="basic-custom-icons-tree" id="ad-data">
    <ad-data-tree-view></ad-data-tree-view>
</section>
@endsection

@section('page-script')
<script type="text/javascript">
    var base_url = "{{ url('/').'/' }}";
</script>
<script type="text/javascript" src="{{ url('js/ad-data.js') }}"></script>
@endsection