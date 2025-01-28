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
                    <h3>All Questions</h3>
                </div>
                <div class="module-body">


                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Question</th>
                                <th>Quiz</th>
                                <th>Created on</th>
                                <th>View</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($questions) == 0)
                                <tr>
                                    No questions to display!
                                </tr>
                            @else
                                @foreach($questions as $key=>$question)

                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$question->question}}</td>
                                        <td>{{$question->quiz->name}}</td>
                                        <td>{{date('F d, Y', strtotime($question->created_at))}}</td>
                                        <td>
                                            <a href="{{route('question.show', $question->id)}}">
                                                <button class="btn btn-info">View</button>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{route('question.edit', $question->id)}}">
                                                <button class="btn btn-primary">Edit</button>
                                            </a>
                                        </td>
                                        <td>
                                            <form id="delete-form-{{$question->id}}"
                                                  method="post" action="{{route('question.destroy', $question->id)}}">
                                                @csrf @method('DELETE')
                                            </form>
                                            <a href="#" onclick="
                                                if(confirm('Do you want to delete?')){
                                                    event.preventDefault();
                                                    document.getElementById('delete-form-{{$question->id}}').submit()
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
                        {{$questions->links('pagination::bootstrap-5')}}
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
