@extends('layouts.app')

@section('content')
    <div class="container" xmlns="http://www.w3.org/1999/html">
        <div class="row justify-content-center">
            <div class="col-md-8">

                {{--   Error   --}}
                @if(Session::has('error'))

                    <div class="alert alert-danger">
                        <strong>Error!</strong> {{Session::get('error')}}
                        <button type="button" class="close float-end" data-dismiss="alert">×</button>
                    </div>

                @endif

                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    @if ($isExamAssigned)
                        @foreach($quizzes as $quiz)

                            <div class="card-body">

                                <h3>{{$quiz->name}}</h3>
                                <p>About Exam: {{$quiz->description}}</p>
                                <p>Time Allocated: {{$quiz->minutes}} minutes</p>
                                <p>Number of Questions: {{$quiz->questions->count()}}</p>

                                @if(!in_array($quiz->id, $wasQuizCompleted))
                                    <a href="user/ vcx quiz/{{$quiz->id}}">
                                        <button class="btn btn-primary">Start Quiz</button>
                                    </a>
                                @else
                                    <a href="/result/user/{{auth()->user()->id}}/quiz/{{$quiz->id}}">
                                        <button class="btn btn-success">Completed! View Result</button>
                                    </a>
                                @endif
                                <hr>

                            </div>

                        @endforeach

                    @else
                        <p>You are not assigned for any quiz!</p>
                    @endif


                </div>
            </div>

            <div class="col-md-3">
                <div class="card">

                    <div class="card-header">
                        <strong>User Profile</strong>
                    </div>

                    <div class="card-body">
                        <div class="m-2">Email: <strong>{{auth()->user()->email}}</strong></div>
                        <div class="m-2">Occupation: <strong>{{auth()->user()->occupation}}</strong></div>
                        <div class="m-2">Address: <strong>{{auth()->user()->address}}</strong></div>
                        <div class="m-2">Phone: <strong>{{auth()->user()->phone}}</strong></div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
