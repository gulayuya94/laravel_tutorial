<div class="card">
    <div class="card-header">MENU</div>

    <div class="card-body">
        <a href="{{ route('top') }}" class="btn btn-info btn-md btn-block" role="button" style="color: white;">Home</a>
    </div>
    <div class="card-body">
        <a href="{{ route('todoList') }}" class="btn btn-info btn-md btn-block" role="button" style="color: white;">My Todo list</a>
    </div>
    <div class="card-body">
        <a href="{{ route('userList') }}" class="btn btn-info btn-md btn-block" role="button" style="color: white;">User list</a>
    </div>
    <div class="card-body">
        <a href="{{ route('create') }}" class="btn btn-primary btn-md btn-block" role="button">Add New Todo</a>
    </div>
</div>