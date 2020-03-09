@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            @include('layouts.nav')
        </div>
        <div class="col-md-10">
            <div class="card mb-3">
                <div class="card-header">Search Your Todo</div>
                <div class="card-body">
                    @include('layouts.error')
                    <form action="/todos/search" method="GET">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="title">title</label>
                                    <input type="text" id="title" name="title" class="form-control">                            
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="content">content</label>
                                    <input type="text" id="content" name="content" class="form-control">                            
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="status">status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="" selected></option>
                                        <option value="1">waiting</option>
                                        <option value="2">working</option>
                                        <option value="3">done</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="startDate">due-date</label>
                                    <input type="date" id="startDate" name="startDate" class="form-control">
                                </div>
                            </div>
                            <div class="col-1" style="margin-top: 25px;">
                                <p style="font-size: 30px;">   ~</p>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="endDate"></label>
                                    <input type="date" id="endDate" name="endDate" class="form-control mt-2">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary float-right">Search</button>
                    </form>
                </div>
                <div class="card-body" style="border-bottom: solid 1px #333; border-top: solid 1px #333;">
                    <div class="row">
                        <div class="col-2 text-center">status</div>
                        <div class="col-2">title</div>
                        <div class="col-4">content</div>
                        <div class="col-4">due_date</div>
                    </div>
                </div>

                @if (isset($searchResults))
                @if ( $searchResults->isEmpty() )
                    <div class="card-body">
                        N/A
                    </div>
                @else
                    @foreach ($searchResults as $searchResult)
                        <div class="card-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col-1">
                                        @if ( $searchResult->todo_status === 'waiting' )
                                            <p class="btn btn-warning btn-sm" style="color: #333;">{{$searchResult->status}}</p>
                                        @elseif ( $searchResult->status === 'working' )
                                            <p class="btn btn-primary btn-sm">{{$searchResult->status}}</p>
                                        @else
                                            <p class="btn btn-secondary btn-sm">{{$searchResult->status}}</p>
                                        @endif
                                    </div>
                                    <div class="col-3">
                                        <p class="text-center">{{ $searchResult->title }}</p>
                                    </div>
                                    <div class="col-4">
                                        <p>{{ $searchResult->content }}</p>
                                    </div>
                                    <div class="col-2">
                                        <p>{{ $searchResult->due_date }}</p>
                                    </div>
                                    <div class="col-1">
                                        <a href="{{ route('edit', [$searchResult->id]) }}" class="btn btn-outline-success btn-sm" role="button">edit</a>
                                    </div>
                                    <div class="col-1">
                                        <form action="{{ route('delete', [$searchResult->id]) }}" method="POST">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-outline-danger btn-sm">delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
                @endif

            </div>

            <div class="card">
                <div class="card-header">Todo List</div>

                <div class="card-body" style="border-bottom: solid 1px #333;">
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
                                    @if ( $task->todo_status === 'waiting' )
                                        <p class="btn btn-warning btn-sm" style="color: #333; cursor: default">{{$task->todo_status}}</p>
                                    @elseif ( $task->todo_status === 'working' )
                                        <p class="btn btn-primary btn-sm" style="cursor: default">{{$task->todo_status}}</p>
                                    @else
                                        <p class="btn btn-secondary btn-sm" style="cursor: default">{{$task->todo_status}}</p>
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
                                    <a href="{{ route('edit', [$task->id]) }}" class="btn btn-outline-success btn-sm" role="button">edit</a>
                                </div>
                                <div class="col-1">
                                    <form action="{{ route('delete', [$task->id]) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-outline-danger btn-sm">delete</button>
                                    </form>
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
