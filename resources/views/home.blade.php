@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            @include('layouts.nav')
        </div>
        <div class="col-md-10">
            <div class="card mb-5">
                <div class="card-header">New Todo</div>

                @if (isset($task_data))
                    <div class="card-body" style="border-bottom: solid 1px #333">
                        <div class="row">
                            <div class="col-2 text-center">status</div>
                            <div class="col-2">title</div>
                            <div class="col-4">content</div>
                            <div class="col-4">due_date</div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-1">
                                    <p class="btn btn-warning btn-sm" style="cursor: default">waiting</p>
                                </div>
                                <div class="col-3">
                                    <p class="text-center">{{ $task_data['title'] }}</p>
                                </div>
                                <div class="col-4">
                                    <p>{{ $task_data['content'] }}</p>
                                </div>
                                <div class="col-2">
                                    <p>{{ $task_data['due_date'] }}</p>
                                </div>
                                <div class="col-1">
                                    <a href="{{ route('edit', $task_data['id']) }}" class="btn btn-outline-success btn-sm" role="button">edit</a>
                                </div>
                                <div class="col-1">
                                    <form action="{{ route('delete', $task_data['id']) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-outline-danger btn-sm">delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card-body">
                        You have no todo. <br>
                        Please click [Add New Todo] button and add your todo.
                    </div>
                @endif

            </div>
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            Following
                        </div>
                        <div class="card-body">
                            @foreach ($followings as $following)
                                <div class="row">
                                    <div class="col-8">
                                        <p class="text-center">{{ $following->name }}</p>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('show', [$following->account_name]) }}" class="btn btn-outline-success btn-sm" role="button">todo list</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            Follower
                        </div>
                        <div class="card-body">
                            @foreach ($followers as $follower)
                                <div class="row">
                                    <div class="col-8">
                                        <p class="text-center">{{ $follower->name }}</p>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('show', [$follower->account_name]) }}" class="btn btn-outline-success btn-sm" role="button">todo list</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
