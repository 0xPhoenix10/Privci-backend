@extends('layouts.app')

@section('content')

<div class="container-fluid main-container pt-7 pb-7 bg-darker">
    <div class="mb-5">
        <div class="card">
            <div class="card-header bg-dark">
                <h1 class="m-0 mr-2 text-light">Support</h1>
                <div class="d-flex">
                    <h3 class="m-0 mr-2 text-light">Please contact the relevant email below if you need assistance and
                        we will respond as soon as possible: </h3>
                </div>
                <div class="d-flex">
                    <h3 class="m-0 mr-2 text-light">General Inquiries: </h3>
                    <a class="m-0 text-light" href="mailto:support@Privci.io">support@Privci.io</a>
                </div>
                <div class="d-flex">
                    <h3 class="m-0 mr-2 p-0 text-light">Techinical Issues / Questions: </h3>
                    <a class="col-6 m-0 p-0 text-light" href="mailto:admin@privci.com">admin@privci.com</a>
                </div>
            </div>
        </div>
    </div>

    <div class="text-light mb-7">
        <h1 class="text-light mb-4">Submit A Support Request</h1>
        <div class="row align-items-top mb-3 mr-0">
            <h4 class="col-xl-2 text-light">Subject: </h4>
            <input type="text" class="col-xl-10 form-control" id="subject">
            <span class="required">*</span>
        </div>

        <div class="row align-items-top mb-3 mr-0">
            <h4 class="col-xl-2 text-light">Request details: </h4>
            <textarea class="col-xl-10 form-control" rows=7 id="detail"></textarea>
            <span class="required">*</span>
        </div>

        <div class="row mb-3">
            <div class="col-xl-2"></div>
            <div class="col-xl-5 pl-0">
                <div class="custom-control custom-control-alternative custom-checkbox">
                    <input class="custom-control-input" name="send_copy" id="send_copy" type="checkbox">
                    <label class="custom-control-label" for="send_copy">
                        <span class="text-primary">Send a copy to my email</span>
                    </label>
                </div>
            </div>
            <div class="col-xl-5 text-right">
                <button class="btn theme-background-color" id="submit" onclick="send_support()">Submit</button>
            </div>
        </div>
    </div>

    <div class="text-light mb-2">Submitted Requests</div>
    <div class="table-responsive">
        <table class="table table-dark bg-darker align-items-center table-flush" id="support-table">
            <thead class="thead-darker">
                <tr class="text-primary">
                    <th scope="col">Date Submitted</th>
                    <th scope="col">Subject</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @php
                if(empty($supports)):
                @endphp
                <tr class="no-row">
                    <td colspan="3">No results found!</td>
                </tr>
                @php
                else:
                foreach($supports as $support):
                @endphp
                <tr class="{{($support->status == 'Y') ? 'text-muted' : '' }}">
                    <td>{{$support->reg_date}}</td>
                    <td>
                        {{$support->subject}}
                    </td>
                    <td class="text-right">
                        @if(Auth::user()->id == $support->user_id)
                        <button class="btn btn-sm btn-info" onclick="ping({{$support->id}})">Ping</button>
                        @if($support->status == 'N')
                        <button class="btn btn-sm btn-default" onclick="resolve({{$support->id}})">Resolve</button>
                        @endif
                        <a class="row-del" onclick="del_support({{$support->id}})"><i class="fa-solid fa-xmark"></i></a>
                        @endif
                    </td>
                </tr>
                @php
                endforeach;
                endif;
                @endphp
            </tbody>
        </table>
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
<script src="{{ asset('argon') }}/js/support.js"></script>
@endpush