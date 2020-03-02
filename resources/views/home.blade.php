@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">MENU</div>

                <div class="card-body">
                    <a href="#" class="btn btn-info btn-lg" role="button" style="color: white;">Todo list</a>
                </div>
                <div class="card-body">
                    <a href="#" class="btn btn-primary btn-lg" role="button">Create new Todo</a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">New Todo</div>

                <div class="card-body">
                    {{-- @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif --}}

                    ここに一番新しい未達成todoを表示する
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
