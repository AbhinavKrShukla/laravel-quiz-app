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
                                <th>Name</th>
                                <th>Quiz</th>
                                <th>View Questions</th>
                                <th>Unassign Quiz</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(count($quizzes) > 0)

                            @php $key=0 @endphp
                            @foreach($quizzes as $quiz)
                                @foreach($quiz->users as $user)
                                    @php $key+=1 @endphp
                                    <tr>
                                        <td>{{$key}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$quiz->name}}</td>
                                        <td>
                                            <a href="{{route('quiz.questions', $quiz->id)}}">
                                                <button class="btn btn-inverse">View Questions</button>
                                            </a>
                                        </td>
                                        <td>
                                            <form action="{{route('exam.remove')}}" method="post"> @csrf
                                                <input type="hidden" name="user_id" value="{{$user->id}}"/>
                                                <input type="hidden" name="quiz_id" value="{{$quiz->id}}"/>
                                                <button type="submit" class="btn btn-danger">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach

                            @endforeach

                        @else

                            <div style="margin: 10px;">
                                No Quiz to Display!
                            </div>

                        @endif

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

@endsection
