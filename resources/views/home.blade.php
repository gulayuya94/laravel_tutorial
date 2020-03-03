@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">MENU</div>

                <div class="card-body">
                    <a href="#" class="btn btn-info btn-md btn-block" role="button" style="color: white;">Todo list</a>
                </div>
                <div class="card-body">
                    <a href="#" class="btn btn-primary btn-md btn-block" role="button">Add new Todo</a>
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
            
                    <div class="container">
                        <div class="row">
                            <div class="col-3">
                                <p>{{ $title }}</p>
                            </div>
                            <div class="col-6">
                                <p>{{ $content }}</p>
                            </div>
                            <div class="col-1">
                                <p class="btn btn-secondary btn-sm">waiting</p>
                            </div>
                            <div class="col-1">
                                <a href="#" class="btn btn-success btn-sm" role="button">edittask</a>
                            </div>
                            <div class="col-1">
                                <a href="#" class="btn btn-danger btn-sm" role="button">delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
