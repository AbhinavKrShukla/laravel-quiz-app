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

            <div class="module">
                <div class="module-head">
                    <h3>All Quiz</h3>
                </div>
                <div class="module-body">


                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Quiz Name</th>
                                <th>Description</th>
                                <th>Time Allotted</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($quizzes as $key=>$quiz)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$quiz->name}}</td>
                                <td>{{$quiz->description}}</td>
                                <td>{{$quiz->minutes}} minutes</td>
                                <td>
                                    <a href="{{route('quiz.edit', $quiz->id)}}">
                                        <button class="btn btn-primary">Edit</button>
                                    </a>
                                </td>
                                <td>
                                    <form id="delete-form-{{$quiz->id}}"
                                        method="post" action="{{route('quiz.destroy', $quiz->id)}}">
                                        @csrf @method('DELETE')
                                    </form>
                                    <a href="#" onclick="
                                        if(confirm('Do you want to delete?')){
                                            event.preventDefault();
                                            document.getElementById('delete-form-{{$quiz->id}}').submit()
                                        } else {
                                            event.preventDefault();
                                        }
                                    ">
                                        <input type="submit" value="Delete" class="btn btn-danger">
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

@endsection
