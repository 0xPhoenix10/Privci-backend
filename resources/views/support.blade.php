@extends('layouts.app')

@section('content')

<div class="container-fluid pt-7 pb-7 bg-darker">
    <div class="col-xl-12 mb-4">
        <div class="card">
            <div class="card-header bg-dark">
                <h1 class="m-0 mr-2 text-white">Support</h1>
                <div class="d-flex">
                    <h3 class="m-0 mr-2 text-white">Please contact the relevant email below if you need assistance and
                        we will respond as soon as possible: </h3>
                </div>
                <div class="d-flex">
                    <h3 class="m-0 mr-2 text-white">General Inquiries: </h3>
                    <a class="m-0 text-white" href="http://support@Privci.io" target="_blank">support@Privci.io</a>
                </div>
                <div class="d-flex">
                    <h3 class="m-0 mr-2 p-0 text-white">Techinical Issues / Questions: </h3>
                    <a class="col-6 m-0 p-0 text-white" href="http://admin@privci.com"
                        target="_blank">admin@privci.com</a>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footers.auth')

<style>
.no-result-card {
    background-color: blue;
}
</style>

@endsection

@push('js')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush