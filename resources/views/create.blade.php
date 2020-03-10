@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            @include('layouts.nav')
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Add Your Todo</div>

                <div class="card-body">
                    @include('layouts.error')

                    <form action="{{ route('create') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="title">title</label>
                            <input type="text" id="title" name="title" class="form-control">                            
                        </div>
                        <div class="form-group">
                            <label for="content">content</label>
                            <textarea id="content" name="content" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="date">due-date</label>
                            <input type="date" id="date" name="date" class="form-control">
                        </div>
                        <label>This todo is</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="private" id="radio1a" value="public" checked>
                            <label class="form-check-label" for="radio1a">public</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="private" id="radio1b" value="private">
                            <label class="form-check-label" for="radio1b">private</label>
                          </div>
                        <button type="submit" class="btn btn-primary float-right">Add</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
