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
                        <div class="col-10 text-center">name</div>
                        <div class="col-1"></div>
                        <div class="col-1"></div>
                    </div>
                </div>

                @foreach ($users as $user)
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-10">
                                    <p class="text-center">{{ $user->name }}</p>
                                </div>
                                <div class="col-1">
                                    <a href="{{ route('show', [$user->account_name]) }}" class="btn btn-outline-success btn-sm" role="button">todo list</a>
                                </div>
                                <div class="col-1">
                                    @if ($user->is_follow)
                                        <form action="{{ route('unfollow', [$user->account_name]) }}" method="POST">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-info btn-sm">-follow</button>
                                        </form>
                                    @else
                                        <form action="{{ route('follow', [$user->account_name]) }}" method="POST">
                                        @csrf
                                            <button class="btn btn-outline-info btn-sm">+follow</button>
                                        </form>
                                    @endif
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
