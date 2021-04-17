@extends('layouts/contentLayoutMaster')

@section('title', 'List Details')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
@endsection

@section('content')
<section id="policy">
    <url-list-view :url="{{ $url }}"></url-list-view>
</section>
@endsection

@section('page-script')
<script type="text/javascript">
    var base_url = "{{ url('/').'/' }}";
</script>
<script type="text/javascript" src="{{ url('js/policy.js') }}"></script>
@endsection