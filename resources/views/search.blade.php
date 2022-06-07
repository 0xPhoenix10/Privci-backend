@extends('layouts.app')

@section('content')

<div class="container-fluid main-container pt-7 pb-7 bg-darker">
    <div class="text-center text-light">
        <h1 class="text-light search-page-title">Search Our Database</h1>
        <h4 class="text-muted">Is your email or the email of a colleague at risk?</h4>
        <h4 class="text-muted">Have you submitted personal data to a site that has a data breach history?</h4>
        <h2 class="text-light">Enter an email address or the top level domain of a website to find out!</h2>
        <select class="btn mt-4 mb-3" id="search_option">
            <option value="1">Search email address</option>
            <option value="2">Search website domain</option>
        </select>
        <div class="container d-flex bg-white rounded search-form">
            <input type="email" class="form-control border-0" id="search-form" placeholder="Enter an email address..."
                name="email">
            <div class="d-flex align-items-center text-center">
                <button class="btn btn-dark p-0 border-0" id="search-btn">
                    <h4 class="p-1 border border-primary rounded text-light">Search</h4>
                </button>
            </div>
        </div>
    </div>
    <div class="search-content">
        @php
        if(!isset($email_list)):
        @endphp
        <div class="search-intro text-light mt-6">Search results will be appeared here.</div>
        <div class="no-result mt-5" style="display: none;">
            <div class="col-xl-12 mb-4">
                <div class="card-header rounded text-center theme-background-color">
                    <h2 class="m-0 mr-2 text-light">No results found! </h3>
                </div>
            </div>
        </div>
        @php
        else:
        if(is_null($data)):
        @endphp
        <div class="no-result mt-5">
            <div class="col-xl-12 mb-4">
                <div class="card-header rounded text-center theme-background-color">
                    <h2 class="m-0 mr-2 text-light">No results found! </h3>
                </div>
            </div>
        </div>
        @php
        else:
        foreach($data->result as $result):
        @endphp
        <div class="text-center mt-5 search-title" style="display: block">
            <h2 class="text-light">Search result for: <span class="search-value">{{$result->account}}</span></h2>
        </div>
        @php
        if($result->source == ''):
        @endphp
        <div class="no-result">
            <div class="col-xl-12 mb-4">
                <div class="card-header rounded text-center theme-background-color">
                    <h2 class="m-0 mr-2 text-light">No results found! </h3>
                </div>
            </div>
        </div>
        @php
        else:
        @endphp

        <div class="col-xl-12 mb-4">
            <div class="card-header rounded bg-darkred">
                <div class="d-flex">
                    <h3 class="m-0 mr-2 text-white">Breach date: </h3>
                    <p class="m-0 text-white">{{$result->date}}</p>
                </div>
                <div class="d-flex">
                    <h3 class="m-0 mr-2 text-white">Source: </h3>
                    @php
                    preg_match('#\((.*?)\)#', $result->source, $url);
                    preg_match("/\[[^\]]*\]/", $result->source, $text);
                    $href = 'http://' . $url[1];
                    @endphp
                    <a href="{{$href}}" target="_blank" class="m-0 text-white">{{$text[0]}}</a>
                </div>
                <div class="d-flex">
                    <h3 class="m-0 mr-2 text-white">Summary: </h3>
                    <p class="m-0 text-white summary-show">{{$result->description}}</p>
                </div>
                <div class="d-flex">
                    <h3 class="m-0 mr-2 text-white">Compromised data: </h3>
                    <p class="m-0 text-white">Email addresses, Passwords</p>
                </div>
                <div class="d-flex">
                    <h3 class="m-0 mr-2 p-0 text-white">Recommended action: </h3>
                    <p class="m-0 text-white">{{$result->recommendation}}</p>
                </div>
            </div>
        </div>

        @php
        endif;
        endforeach;
        @endphp

        <div class="no-result" style="display:none;">
            <div class="col-xl-12 mb-4">
                <div class="card-header rounded text-center theme-background-color">
                    <h2 class="m-0 mr-2 text-light">No results found! </h3>
                </div>
            </div>
        </div>
        @php
        endif;
        endif;
        @endphp
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
<script src="{{ asset('argon') }}/js/search.js"></script>
@endpush