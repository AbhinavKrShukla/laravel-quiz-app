@extends('backend.layouts.master')

@section('content')
    <div class="span9">
        <div class="content">

            @if(Session::has('message'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{Session::get('message')}}</strong>
                </div>
            @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            <div class="module">
                <div class="module-head">
                    <h3>All Users</h3>
                </div>
                <div class="module-body">


                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Occupation</th>
                                <th>View</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($users) == 0)
                                <tr>
                                    No user to display!
                                </tr>
                            @else
                                @foreach($users as $key=>$user)

                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->phone}}</td>
                                        <td>{{$user->occupation}}</td>
                                        <td>
                                            <a href="{{route('user.show', $user->id)}}">
                                                <button class="btn btn-info">View</button>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{route('user.edit', $user->id)}}">
                                                <button class="btn btn-primary">Edit</button>
                                            </a>
                                        </td>
                                        <td>
                                            <form id="delete-form-{{$user->id}}"
                                                  method="post" action="{{route('user.destroy', $user->id)}}">
                                                @csrf @method('DELETE')
                                            </form>
                                            <a href="#" onclick="
                                                if(confirm('Do you want to delete?')){
                                                    event.preventDefault();
                                                    document.getElementById('delete-form-{{$user->id}}').submit()
                                                } else {
                                                    event.preventDefault();
                                                }
                                            ">
                                                <input type="submit" value="Delete" class="btn btn-danger">
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif


                        </tbody>
                    </table>

                    <div class="pagination pagination-centered">
                        {{$users->links('pagination::bootstrap-5')}}
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
