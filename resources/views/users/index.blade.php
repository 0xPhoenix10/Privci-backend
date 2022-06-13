@extends('layouts.app')

@section('content')

<div class="container-fluid main-container pt-7 pb-7 bg-darker">
    <div class="row">
        <div class="col">
            <div class="card bg-dark shadow">
                <div class="card-header bg-transparent border-0">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Users</h3>
                        </div>
                        <div class="col-4 text-right">
                            <a href="/user/edit" class="btn btn-sm btn-default text-white">Add user</a>
                        </div>
                    </div>
                    @if(isset($status))
                    @if($status == 'success')
                    <div class="mt-3 alert alert-success" role="alert">
                        {{$msg}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @else
                    <div class="mt-3 alert alert-danger" role="alert">
                        {{$msg}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    @endif
                </div>

                <div class="col-12">
                </div>

                <div class="table-responsive">
                    <table class="table table-dark align-items-center table-flush">
                        <thead class="thead-darker">
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Creation Date</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            foreach($users as $user):
                            @endphp
                            <tr>
                                <td>{{$user->name}}</td>
                                <td>
                                    <a href="mailto:{{$user->email}}">{{$user->email}}</a>
                                </td>
                                <td>{{$user->created_at}}</td>
                                <td class="text-right">
                                    <button class="btn btn-sm btn-default"
                                        onclick="edit_user({{$user->id}})">Edit</button>
                                    <button class="btn btn-sm btn-danger"
                                        onclick="delete_user({{$user->id}})">Delete</button>
                                </td>
                            </tr>
                            @php
                            endforeach;
                            @endphp
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-transparent py-4">
                    <nav class="d-flex justify-content-end" aria-label="...">

                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.footers.auth')

@endsection

@push('js')
<script src="../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('argon') }}/js/user.js"></script>
@endpush