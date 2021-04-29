
@extends('layouts/contentLayoutMaster')

@section('title', 'Block Page')

@section('vendor-style')
  {{-- vendor css files --}}
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css') }}">
@endsection

@section('content')

<!-- Basic table -->
<section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom p-1">
                    <div class="head-label">
                        <h6 class="mb-0">{{ $page->title }}</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5" style="border: 1px solid black;">
                            {{ $page->html_content }}
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-md-6" style="border: 1px solid black;">
                            {!! $page->html_content !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

