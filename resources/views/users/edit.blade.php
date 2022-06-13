@extends('layouts.app')

@section('content')

<div class="container-fluid main-container pt-7 pb-7 bg-darker">
    <div class="col-xl-6 col-md-8 col-sm-12 mb-4">
        <div class="card-header bg-dark" style="border-radius: 5px">
            <form method="post" action="/user/edit" autocomplete="off">
                @csrf
                @method('put')

                @php
                $title = isset($user) ? "Edit User" : 'Add User';
                $button_text = isset($user) ? "Update User" : 'Create New User';
                $username = isset($user) ? $user->name : '';
                $email = isset($user) ? $user->email : '';
                $userid = isset($user) ? $user->id : 0;
                @endphp
                <h1 class="m-0 mr-2 text-light">{{ __($title) }}</h1>

                @if($errors)
                @foreach($errors->all() as $error)
                <div class="alert alert-danger" role="alert">
                    {{ $error }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endforeach
                @endif

                @if($success)
                <div class="alert alert-success" role="alert">
                    Successfully saved!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                <!-- <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Hi, everyone!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> -->

                <div>
                    <input type="hidden" name="userid" value="{{$userid}}">
                    <div class="form-group">
                        <label class="form-control-label" for="input-username">{{ __('Name') }}</label>
                        <input type="text" name="username" id="input-username"
                            class="form-control form-control-alternative" placeholder="{{ __('Type Username') }}"
                            value="{{$username}}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="input-useremail">{{ __('Email') }}</label>
                        <input type="email" name="email" id="input-useremail"
                            class="form-control form-control-alternative" placeholder="{{ __('Type Email') }}"
                            value="{{$email}}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="input-password">{{ __('Password') }}</label>
                        <input type="password" name="password" id="input-password"
                            class="form-control form-control-alternative" placeholder="{{ __('Type Password') }}"
                            value="">
                    </div>

                    <div class="text-left">
                        <button type="submit" class="btn btn-success mt-4">{{ __($button_text) }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('layouts.footers.auth')

@endsection

@push('js')
<script src="../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('argon') }}/js/user.js"></script>
@endpush