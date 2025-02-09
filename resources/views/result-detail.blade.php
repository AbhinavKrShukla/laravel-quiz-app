@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="text-center">
                    <h2>Your Result</h2>
                </div>


                @foreach($results as $key=>$result)
                    <div class="card mb-2">

                        <div class="card-header">
                            <strong>{{$key+1}}. </strong>
                            {{$result->question->question}}
                        </div>

                        <div class="card-body">

                            @foreach($result->question->answers as $i=>$answer)
                                <div>

                                    <strong>{{$i+1}}.</strong>
                                    {{$answer->answer}}


                                </div>
                            @endforeach

                            <hr width="550">
                            <div>
                                <mark>Your Answer: {{$yourAnswer = $result->answer->answer}}</mark>
                            </div>
                            <div>
                                Correct Answer:
                                @foreach($result->question->answers as $answer)
                                    @if($answer->is_correct)
                                        {{$correctAnswer = $answer->answer}}
                                    @endif
                                @endforeach
                            </div>
                            <div>
                                Result:
                                @if($yourAnswer == $correctAnswer)
                                    <span class="badge bg-success">Correct</span>
                                @else
                                    <span class="badge bg-danger">Incorrect</span>
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach


            </div>
        </div>
    </div>
@endsection
