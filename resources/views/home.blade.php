@extends('layouts.app')

@section('content')

<div class="container-fluid main-container pt-7 pb-7 bg-darker">
    <input type="hidden" id="selected_domain" value="{{$selected}}">
    <div class="filter-row">
        <h3 class="text-white mb-0 mr-2">Filter:</h3>
        <button class="btn d-flex justify-content-between" id="sort_az">
            A to Z
            <div class="d-flex flex-column ml-2">
                <i class="fa-solid fa fa-angle-up m-0"></i>
                <i class="fa-solid fa fa-angle-down m-0"></i>
            </div>
        </button>
        <button class="btn d-flex justify-content-between" id="sort_breach">
            With a breach history
            <div class="d-flex flex-column ml-2">
                <i class="fa-solid fa fa-angle-up m-0"></i>
                <i class="fa-solid fa fa-angle-down m-0"></i>
            </div>
        </button>
        <button class="btn d-flex justify-content-between" id="sort_email">
            With the most email addresses
            <div class="d-flex flex-column ml-2">
                <i class="fa-solid fa fa-angle-up m-0"></i>
                <i class="fa-solid fa fa-angle-down m-0"></i>
            </div>
        </button>
    </div>
    <div class="main-card rounded bg-dark">
        <div class="card-col col-lg-2 col-md-12 domain-list">
            <div class="row m-0 mb-2 align-items-center">
                <h2 class="text-white p-0 pl-2 m-0">Domain</h2>
            </div>
            <div class="pt-3 pl-2 pb-3 pr-2 inner-card rounded justify-content-between">
                @foreach($domains as $domain)
                <div class="form-check d-flex align-items-center">
                    <input class="form-check-input" type="radio" name="domain_name"
                        value="{{$domain->monitoring_domain}}" />
                    <label class="form-check-label text-white ellipsis">
                        {{$domain->monitoring_domain}}
                    </label>
                </div>
                @endforeach
            </div>
        </div>
        <div class="card-col col-lg-10 col-md-12 breach-panel">
            <div class="row m-0 mb-2 align-items-center">
                <h2 class="text-white mr-1">Search</h2>
                <select class="rounded">
                    <option value="domain" selected>Domain</option>
                    <option value="email">Email</option>
                </select>
                <h2 class="text-white ml-1 mr-3">:</h2>
                <div class="col-7 p-0 d-flex align-items-center bg-white rounded">
                    <input type="url" class="col rounded border-0" placeholder="Enter domain">
                    <i class="col-1 fa fa-search"></i>
                </div>
            </div>
            <div class="p-2 inner-card rounded">
                <div class="p-2 medium-card rounded text-white">
                    <div class="d-flex mb-2 justify-content-between theme-color">
                        <div class="col-md-11 p-0 d-flex justify-content-between">
                            <div class="d-flex align-items-center">
                                <h4 class="m-0 mr-2 theme-color">Company Name:</h4>
                                <p class="m-0">{{$domain_detail->company_name}}</p>
                            </div>
                            <div class="d-flex align-items-center">
                                <h4 class="m-0 mr-2 theme-color">No of breaches:</h4>
                                <p class="m-0">{{$domain_detail->no_of_breaches}}</p>
                            </div>
                            <div class="d-flex align-items-center">
                                <h4 class="m-0 ml-3 mr-2 theme-color">Privacy policy:</h4>
                                <p class="m-0">
                                    <a href="{{$domain_detail->privacy_policy}}">{{$domain_detail->privacy_policy}}</a>
                                </p>
                            </div>
                        </div>
                        <p class="page-show">
                            {{$domain_detail->no_of_breaches > 1 ? '1 of ' . $domain_detail->no_of_breaches : ''}}</p>
                    </div>
                    @php
                    $breach_info = json_decode($domain_detail->breach_info);
                    $array = get_object_vars($breach_info);
                    if(empty($array)) {
                    @endphp
                    <div class="no-breach">
                        No breach infos!
                    </div>
                    @php
                    } else {
                    $breach_count = count($array);
                    $first_breach_array = get_object_vars($array[0]);
                    @endphp
                    <div class="breach-content">
                        <div class="search-result-info">
                            <h4 class="m-0 text-white">Breach Date:</h4>
                            <p class="col m-0">{{$first_breach_array['breach date']}}</p>
                        </div>
                        <div class="search-result-info">
                            <h4 class="m-0 text-white">No of Records:</h4>
                            <p class="col m-0">{{$first_breach_array['no of records']}}</p>
                        </div>
                        <div class="search-result-info">
                            <h4 class="m-0 p-0 text-white">Breach of Summary:</h4>
                            <p class="col-9 m-0">{{$first_breach_array['breach summary']}}</p>
                        </div>
                        <div class="search-result-info">
                            <h4 class="m-0 text-white">Reference:</h4>
                            <p class="col m-0">
                                {{$first_breach_array['reference']}}
                            </p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <p class="m-0 p-0 theme-color pagination-button">Previous</p>
                        <ul class="d-flex align-items-center ml-2 m-0 mr-2 p-0 pagination-button">
                            @php
                            for($i=0; $i<$breach_count; $i++) { @endphp <li
                                class="list-unstyled pl-2 pr-2 {{$i==0 ? 'page-selected' : ''}}" data-index="{{$i}}">
                                {{$i+1}}</li>
                                @php
                                }
                                @endphp
                        </ul>
                        <p class="m-0 p-0 theme-color pagination-button">Next</p>
                    </div>
                    @php
                    }
                    @endphp
                </div>
                <div class="accordian-container mb-5 mt-2">
                    <button class="accordion">Does the Internet company sell the personal data of its users?
                        @php
                        if($domain_detail->data_sell != "") {
                        @endphp
                        <i class="ni ni-bold-down sell-icon"></i>
                        @php
                        }
                        @endphp
                    </button>
                    <p class="panel">
                        @php
                        if($domain_detail->data_sell != "") {
                        echo $domain_detail->data_sell;
                        }
                        @endphp
                    </p>
                    <button class="accordion">Do they share personal data for marketing purposes?
                        @php
                        if($domain_detail->data_share != "") {
                        @endphp
                        <i class="ni ni-bold-down share-icon"></i>
                        @php
                        }
                        @endphp
                    </button>
                    <p class="panel">
                        @php
                        if($domain_detail->data_share != "") {
                        echo $domain_detail->data_share;
                        }
                        @endphp
                    </p>
                    <button class="accordion">Statement(s) related to opting out found in the Privacy Policy
                        @php
                        if($domain_detail->opt_out != "") {
                        @endphp
                        <i class="ni ni-bold-down opt-icon"></i>
                        @php
                        }
                        @endphp
                    </button>
                    <p class="panel">
                        @php
                        if($domain_detail->opt_out != "") {
                        echo $domain_detail->opt_out;
                        }
                        @endphp
                    </p>
                </div>

                <div class="p-0 mb-2 d-flex justify-content-between">
                    <div class="col-8 p-0 d-flex m-0 align-items-center">
                        <h4 class="text-white mr-3">Search email:</h4>
                        <div class="col pl-0 d-flex align-items-center bg-white rounded">
                            <input type="email" class="col rounded border-0" placeholder="Enter an email address...">
                            <i class="col-1 fa fa-search"></i>
                        </div>
                    </div>
                    <div class="form-check d-flex align-items-center">
                        <label class="form-check-label text-white" for="select-all"> Select all </label>
                        <input class="form-check-input" type="checkbox" name="" id="select-all" />
                    </div>
                </div>
                <div class="pl-3 pt-2 medium-card rounded text-white">
                    <h4 class="mb-2 text-white">Users that may have submit or used their company email on
                        <a class="theme-color" href="https://{{$domain_detail->monitoring_domain}}">
                            {{$domain_detail->monitoring_domain}}
                        </a>
                    </h4>

                    @php
                    $total_email_count = count($domain_emails) > 50 ? 50 : count($domain_emails);
                    $page_length = count($domain_emails) > 50 ? ceil(count($domain_emails) / 50) : 1;

                    if($total_email_count == 0) $page_length = 0;

                    if($total_email_count == 0) {
                    echo "<div class='no-emails'>No Emails!</div>";
                    } else {
                    echo "<div class='row'>";
                        for($i=0; $i < $total_email_count; $i++) { if(trim($domain_emails[$i]) !='' ) { @endphp <div
                            class="col-lg-3 col-md-6">
                            <div class="form-check">
                                <label class="form-check-label ellipsis" for="">
                                    <input type="checkbox" class="form-check-input" name="sel_email[]"
                                        value="{{$domain_emails[$i]}}">{{$domain_emails[$i]}}
                                </label>
                            </div>
                    </div>
                    @php
                    }}
                    echo "
                </div>";}
                @endphp

                <div class="row p-0 m-0 mt-3 justify-content-between">
                    @php
                    if($page_length > 0) {
                    @endphp
                    <div><span class="theme-color"> 1</span>-<span class="theme-color">
                            {{$page_length > 1 ? 50 : $total_email_count}}</span> of <span
                            class="theme-color">{{count($domain_emails)}}</span> records</div>
                    @php
                    if($page_length > 1) {
                    @endphp
                    <div class="col-2 m-0 mr-3 p-0 d-flex justify-content-between">
                        <span class="theme-color pagination-button">Previous</span>
                        <span class="theme-color pagination-button">Next</span>
                    </div>
                    @php
                    }
                    }
                    @endphp
                </div>
            </div>
        </div>
        <div class="row button-row m-0 mt-4 justify-content-between">
            <div>
                <button class="btn btn-danger btn-home-footer"> Check if email is in a data breach</button>
                <p class="bottom-note"><strong>Note: </strong> You must select an email to use this feature</p>
            </div>
            <div class="d-flex p-0">
                <button class="btn btn-home-footer theme-background-color mr-4">Send an email to selected
                    user(s)</button>
                <button class="btn btn-home-footer theme-background-color">Send a push notification</button>
            </div>
        </div>

    </div>
</div>

</div>

@include('layouts.footers.auth')

@endsection

@push('js')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
<script src="{{ asset('argon') }}/js/home.js"></script>
@endpush