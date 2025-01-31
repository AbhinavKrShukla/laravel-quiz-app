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

                    <h3>{{$user->name}}</h3>

                </div>
                <div class="module-body">

                    <table class="table table-striped">

                        <tr>
                            <td>Email</td>
                            <td>
                                <strong>
                                {{$user->email}}
                                </strong>
                            </td>
                        </tr>

                        <tr>
                            <td>Phone</td>
                            <td>
                                <strong>
                                    {{$user->phone}}
                                </strong>
                            </td>
                        </tr>

                        <tr>
                            <td>Occupation</td>
                            <td>
                                <strong>
                                    {{$user->occupation}}
                                </strong>
                            </td>
                        </tr>

                        <tr>
                            <td>Bio</td>
                            <td>
                                <strong>
                                    {{$user->bio}}
                                </strong>
                            </td>
                        </tr>

                        <tr>
                            <td>Address</td>
                            <td>
                                <strong>
                                    {{$user->address}}
                                </strong>
                            </td>
                        </tr>

                        @if($user->is_admin == 1)

                        <tr>
                            <td>Is Admin</td>
                            <td>
                                <strong class="badge badge-success">
                                    ADMIN
                                </strong>
                            </td>
                        </tr>
                        @endif


                    </table>

                </div>

                <div class="module-foot">
                    <a href="{{route('user.edit', $user->id)}}">
                        <button class="btn btn-primary">Edit</button>
                    </a>

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

                    <a href="{{route('user.index', $user->id)}}" class="pull-right">
                        <button class="btn btn-inverse">Back</button>
                    </a>

                    <form id="delete-form-{{$user->id}}"
                          method="post" action="{{route('user.destroy', $user->id)}}">
                        @csrf @method('DELETE')
                    </form>



                </div>
            </div>
        </div>
    </div>

@endsection
