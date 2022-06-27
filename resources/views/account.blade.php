@extends('layouts.app')

@section('content')

<div class="container-fluid main-container pt-7 pb-7 bg-darker">
    <div class="col-xl-12 mb-4">
        <div class="card-header bg-dark" style="border-radius: 5px">
            <h1 class="m-0 mr-2 text-light">Account</h1>
            <div class="d-flex">
                <h3 class="m-0 mr-2 text-light">Company Name: </h3>
                <p class="m-0 text-light">Privci Ltd</p>
            </div>
            <div class="d-flex">
                <h3 class="m-0 mr-2 text-light">Email address: </h3>
                <p class="m-0 text-light">{{auth()->user()->email}}</p>
            </div>
            <div class="d-flex">
                <h3 class="m-0 mr-2 text-light">Account Type: </h3>
                <p class="m-0 text-light">Trail</p>
            </div>
            <div class="d-flex">
                <h3 class="m-0 mr-2 p-0 text-light">Renewal Date: </h3>
                <p class="col-9 m-0 p-0 text-light">{{auth()->user()->updated_at}}</p>
            </div>
        </div>
    </div>
    <div class="col-xl-12 mb-4">
        <div class="card-header bg-dark" style="border-radius: 5px">
            <h1 class="m-0 mr-2 text-light">Tracking Company Email</h1>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="tracking-company-email" id="account_radio_1"
                    value="1" {{($setting->tracking == 1) ? "checked" : ""}}>
                <label class="form-check-label text-light" for="account_radio_1">
                    Automatically track sites where user may have provided or used their company email address
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="tracking-company-email" id="account_radio_2"
                    value="2" {{($setting->tracking == 2) ? "checked" : ""}}>
                <label class="form-check-label text-light" for="account_radio_2">
                    Allow users to approve which sites tomonitor after providing or using their company email address
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input color-red" type="radio" name="tracking-company-email"
                    id="account_radio_3" value="0" {{($setting->tracking == 0) ? "checked" : ""}}>
                <label class="form-check-label text-red" for="account_radio_3">
                    Stop tracking
                </label>
            </div>
        </div>
    </div>
    <div class="col-xl-12 mb-4">
        <div class="card-header bg-dark" style="border-radius: 5px">
            <h1 class="m-0 mr-2 text-light">Notifications</h1>
            <div class="form-check">
                @php
                $check = $setting->notification == "True" ? 'checked' : '';
                @endphp
                <input type="checkbox" class="form-check-input" id="account_notification"
                    onchange="save_notification_status('{{$setting->notification}}')" name="notification" {{$check}}>
                <label class="form-check-label" for="account_notification">
                    I want to be notified in the event where my company email address is found in a data breach in the
                    future.
                </label>
            </div>
            <div class="d-flex align-items-center">
                <h3 class="m-0 mr-2 p-0">Send email notifications to: </h3>
                <div class="d-flex align-items-center" id="notification_p">
                    <p>{{$setting->notification_email}}</p>
                    <i class="fa fa-edit ml-2" onClick="
                        document.getElementById('notification_p').setAttribute('style', 'display:none !important');
                        document.getElementById('notification_input').style.display = '';
                    "></i>
                </div>
                <div class="d-flex align-items-center" id="notification_input" style="display:none !important">
                    <input type="text" class="pl-2 pr-2 rounded" value="{{$setting->notification_email}}">
                    <i class="fa fa-save ml-2" onClick="
                        document.getElementById('notification_p').children[0].innerHTML = document.getElementById('notification_input').children[0].value;
                        document.getElementById('notification_p').style.display = '';
                        document.getElementById('notification_input').setAttribute('style', 'display:none !important');
                        save_notification_email($(this).prev().val());
                    "></i>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="col-xl-12 mb-4">
        <div class="card-header bg-dark" style="border-radius: 5px">
            <h1 class="m-0 mr-2 text-light">Change Password</h1>
            <div class="d-flex">
                <h3 class="m-0 mr-2 text-light">Account: </h3>
                <p class="m-0 text-light">{{auth()->user()->email}}</p>
            </div>
            <input type="text" placeholder="Current Password" class="form-control mb-3">
            <input type="text" placeholder="New Password" class="form-control mb-3">
            <button class="btn theme-background-color">Change Password</button>
        </div>
    </div> -->
    <div class="col-xl-12 mb-4">
        <div class="card-header bg-dark" style="border-radius: 5px">
            <form method="post" action="{{ route('profile.password') }}" autocomplete="off">
                @csrf
                @method('put')

                <h1 class="m-0 mr-2 text-light">{{ __('Change Password') }}</h1>
                <div class="d-flex">
                    <h3 class="m-0 mr-2 text-light">Account: </h3>
                    <p class="m-0 text-light">{{auth()->user()->email}}</p>
                </div>

                @if (session('password_status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('password_status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                <div>
                    <div class="form-group{{ $errors->has('old_password') ? ' has-danger' : '' }}">
                        <label class="form-control-label"
                            for="input-current-password">{{ __('Current Password') }}</label>
                        <input type="password" name="old_password" id="input-current-password"
                            class="form-control form-control-alternative{{ $errors->has('old_password') ? ' is-invalid' : '' }}"
                            placeholder="{{ __('Current Password') }}" value="" required>

                        @if ($errors->has('old_password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('old_password') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                        <label class="form-control-label" for="input-password">{{ __('New Password') }}</label>
                        <input type="password" name="password" id="input-password"
                            class="form-control form-control-alternative{{ $errors->has('password') ? ' is-invalid' : '' }}"
                            placeholder="{{ __('New Password') }}" value="" required>

                        @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-control-label"
                            for="input-password-confirmation">{{ __('Confirm New Password') }}</label>
                        <input type="password" name="password_confirmation" id="input-password-confirmation"
                            class="form-control form-control-alternative" placeholder="{{ __('Confirm New Password') }}"
                            value="" required>
                    </div>

                    <div class="text-left">
                        <button type="submit" class="btn btn-success mt-4">{{ __('Change password') }}</button>
                    </div>
                </div>
            </form>
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