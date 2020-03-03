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
                    <a href="/create" class="btn btn-primary btn-md btn-block" role="button">Add New Todo</a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Add Your Todo</div>

                <div class="card-body">
                    <form action="/create" method="POST">
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
