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
                        <button type="submit" class="btn btn-primary float-right">Add</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
