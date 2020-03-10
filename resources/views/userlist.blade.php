@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            @include('layouts.nav')
        </div>
        <div class="col-md-10">

            <div class="card">
                <div class="card-header">User List</div>

                <div class="card-body" style="border-bottom: solid 1px #333;">
                    <div class="row">
                        <div class="col-8 text-center">name</div>
                        <div class="col-4">todo list</div>
                    </div>
                </div>

                @foreach ($users as $user)
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-8">
                                    <p class="text-center">{{ $user->name }}</p>
                                </div>
                                <div class="col-4">
                                    <a href="{{ route('show', [$user->account_name]) }}" class="btn btn-outline-success btn-sm" role="button">todo list</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
