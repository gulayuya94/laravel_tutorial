@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            @include('layouts.nav')
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">New Todo</div>

                @if (isset($title))
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
                                    <p class="text-center">{{ $title }}</p>
                                </div>
                                <div class="col-4">
                                    <p>{{ $content }}</p>
                                </div>
                                <div class="col-2">
                                    <p>{{ $due_date }}</p>
                                </div>
                                <div class="col-1">
                                    <a href="{{ route('edit', $id) }}" class="btn btn-outline-success btn-sm" role="button">edit</a>
                                </div>
                                <div class="col-1">
                                    <form action="{{ route('delete', $id) }}" method="POST">
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
        </div>
    </div>
</div>
@endsection
