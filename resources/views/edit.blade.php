@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            @include('layouts.nav')
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Edit Your Todo</div>

                <div class="card-body">
                    @include('layouts.error')

                    <form action="{{ route('update', $id) }}" method="POST">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="title">title</label>
                            <input type="text" id="title" name="title" class="form-control" value="{{ $title }}">                            
                        </div>
                        <div class="form-group">
                            <label for="content">content</label>
                            <textarea id="content" name="content" class="form-control">{{ $content }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                @if ($status === 'waiting')
                                    <option value="1">waiting</option>
                                    <option value="2">working</option>
                                    <option value="3">done</option>
                                @elseif ($status === 'working')
                                    <option value="1">waiting</option>
                                    <option value="2" selected>working</option>
                                    <option value="3">done</option>
                                @else
                                    <option value="1">waiting</option>
                                    <option value="2">working</option>
                                    <option value="3" selected>done</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date">due-date</label>
                            <input type="date" id="date" name="date" class="form-control" value="{{ $due_date }}">
                        </div>
                        <button type="submit" class="btn btn-primary float-right">Edit</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
