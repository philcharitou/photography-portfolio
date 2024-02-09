@php use Carbon\Carbon; @endphp

@extends('system.layouts.sidebar')
@section('title', 'Administrative Dashboard')

@section('content')

    <div class="p-8">
        <div class="w-1/3 m-5 border-light">
            <div class="p-3 text-2xl bg-neutral-200">
                Mass Email Import Form
            </div>

            <div class="card-body-dark p-4">

            </div>
        </div>

        <div class="w-1/3 m-5 border-light">
            <div class="p-3 text-2xl bg-neutral-200">
                Queue Sizes
            </div>

            <div class="card-body-dark p-4">

            </div>
        </div>
    </div>

    <!-- White Space -->
    <div class="row mx-0" style="height: 200px">

    <script>
        function addRequestBanner()
        {

        }
    </script>

@endsection
