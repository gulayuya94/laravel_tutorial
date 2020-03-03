@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            <div class="card">
                <div class="card-header">MENU</div>

                <div class="card-body">
                    <a href="/home" class="btn btn-info btn-md btn-block" role="button" style="color: white;">Home</a>
                </div>
                <div class="card-body">
                    <a href="/tasklist" class="btn btn-info btn-md btn-block" role="button" style="color: white;">Todo list</a>
                </div>
                <div class="card-body">
                    <a href="/create" class="btn btn-primary btn-md btn-block" role="button">Add New Todo</a>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Todo List</div>

                <div class="card-body" style="border-bottom: solid 1px #333">
                    <div class="row">
                        <div class="col-2 text-center">status</div>
                        <div class="col-2">title</div>
                        <div class="col-4">content</div>
                        <div class="col-4">due_date</div>
                    </div>
                </div>

                @foreach ($tasks as $task)
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-1">
                                    @if ( $task->status === 1 )
                                        <p class="btn btn-warning btn-sm" style="color: #333;">waiting</p>
                                    @elseif ( $task->status === 2 )
                                        <p class="btn btn-primary btn-sm">working</p>
                                    @else
                                        <p class="btn btn-secondary btn-sm">done</p>
                                    @endif
                                </div>
                                <div class="col-3">
                                    <p class="text-center">{{ $task->title }}</p>
                                </div>
                                <div class="col-4">
                                    <p>{{ $task->content }}</p>
                                </div>
                                <div class="col-2">
                                    <p>{{ $task->due_date }}</p>
                                </div>
                                <div class="col-1">
                                    <a href="/edit/{{ $task->id }}" class="btn btn-outline-success btn-sm" role="button">edit</a>
                                </div>
                                <div class="col-1">
                                    <a href="/delete/{{ $task->id }}" class="btn btn-outline-danger btn-sm" role="button">delete</a>
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
