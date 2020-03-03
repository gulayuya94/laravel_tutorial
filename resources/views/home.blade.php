@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">MENU</div>

                <div class="card-body">
                    <a href="/home" class="btn btn-info btn-md btn-block" role="button" style="color: white;">Home</a>
                </div>
                <div class="card-body">
                    <a href="/tasklist" class="btn btn-info btn-md btn-block" role="button" style="color: white;">Todo list</a>
                </div>
                <div class="card-body">
                    <a href="#" class="btn btn-primary btn-md btn-block" role="button">Add new Todo</a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">New Todo</div>
                <div class="card-body" style="border-bottom: solid 1px #333">
                    <div class="row">
                        <div class="col-2 text-center">status</div>
                        <div class="col-2">title</div>
                        <div class="col-8">content</div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-1">
                                <p class="btn btn-warning btn-sm">waiting</p>
                            </div>
                            <div class="col-3">
                                <p class="text-center">{{ $title }}</p>
                            </div>
                            <div class="col-6">
                                <p>{{ $content }}</p>
                            </div>
                            <div class="col-1">
                                <a href="#" class="btn btn-outline-success btn-sm" role="button">edittask</a>
                            </div>
                            <div class="col-1">
                                <a href="#" class="btn btn-outline-danger btn-sm" role="button">delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
