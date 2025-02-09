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
                    <h2>Result</h2>
                    <div style="font-size: 20px;">{{$user->name}}</div>
                </div>
                <div class="module-body">

                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Test</th>
                            <th>Total Question</th>
                            <th>Attempt Questions</th>
                            <th>Correct Answer</th>
                            <th>Wrong Answer</th>
                            <th>Percentage</th>
                        </tr>
                        </thead>
                        <tbody>

                        @php $key=0 @endphp

                        @php $key+=1 @endphp
                        <tr>
                            <td>{{$key}}</td>
                            <td>{{$quiz->name}}</td>
                            <td>{{$totalQuestions}}</td>
                            <td>{{$attemptQuestions}}</td>
                            <td>{{$userCorrectAnswer}}</td>
                            <td>{{$userWrongAnswer}}</td>
                            <td>{{round($percentage, 2)}}</td>
                        </tr>

                        </tbody>
                    </table>

                    <br>

                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Question</th>
                            <th>Answer Given</th>
                            <th>Result</th>
                        </tr>
                        </thead>
                        <tbody>

                        <hr>

                        @foreach($results as $key=>$result)

                            <tr>
                                <td>{{$key}}</td>
                                <td>{{$result->question->question}}</td>
                                <td>{{$result->answer->answer}}</td>
                                @if($result->answer->is_correct)
                                    <td>
                                        <div class="badge badge-success">Correct</div>
                                    </td>
                                @else
                                    <td>
                                        <div class="badge ">Wrong</div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach

                        </tbody>
                    </table>


                </div>
            </div>
            <div class="float-end" style="text-align: right; margin-top: 10px;">
                <a href="{{url('result')}}">
                    <button class="btn btn-primary">Back</button>
                </a>
            </div>
        </div>
    </div>

@endsection
