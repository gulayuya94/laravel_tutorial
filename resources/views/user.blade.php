@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            @include('layouts.nav')
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ $user_name[0] }}'s Todo List</div>

                <div class="card-body" style="border-bottom: solid 1px #333;">
                    <div class="row">
                        <div class="col-3 text-center">status</div>
                        <div class="col-2">title</div>
                        <div class="col-5">content</div>
                        <div class="col-2">due_date</div>
                    </div>
                </div>

                @foreach ($tasks as $task)
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-1">
                                    @if ( $task->todo_status === 'waiting' )
                                        <p class="btn btn-warning btn-sm" style="color: #333; cursor: default">{{$task->todo_status}}</p>
                                    @elseif ( $task->todo_status === 'working' )
                                        <p class="btn btn-primary btn-sm" style="cursor: default">{{$task->todo_status}}</p>
                                    @else
                                        <p class="btn btn-dark btn-sm" style="cursor: default">{{$task->todo_status}}</p>
                                    @endif
                                </div>
                                <div class="col-1">
                                    @if ( $task->private_status === 'public' )
                                        <p class="btn btn-success btn-sm">public</p>
                                    @else
                                        <p class="btn btn-secondary btn-sm">private</p>
                                    @endif
                                </div>
                                <div class="col-3">
                                    <p class="text-center">{{ $task->title }}</p>
                                </div>
                                <div class="col-5">
                                    <p>{{ $task->content }}</p>
                                </div>
                                <div class="col-2">
                                    <p>{{ $task->due_date }}</p>
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
