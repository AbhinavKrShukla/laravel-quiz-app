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

                    <table class="table">
                        <thead>
                        <tr>
                            <th>
                                <h3>Q. {{$question->question}}</h3>
                            </th>
                            <th>
                                <div class="badge badge-warning pull-right">
                                        {{$question->quiz->name}}
                                </div>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="module-body">

                        <table class="table">
                            @foreach($answers as $key=>$answer)
                                    <tr>
                                        <td><strong>{{$key+1}}.</strong> {{$answer->answer}}</td>
                                        <td>
                                            <div class="badge badge-success pull-right">
                                            @if($answer->is_correct == 1)
                                                 Correct Answer
                                            @endif
                                            </div>
                                        </td>
                                    </tr>
                            @endforeach
                        </table>

                </div>

                <div class="module-foot">
                    <a href="{{route('question.edit', $question->id)}}">
                        <button class="btn btn-primary">Edit</button>
                    </a>

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
                    <form id="delete-form-{{$question->id}}"
                          method="post" action="{{route('question.destroy', $question->id)}}">
                        @csrf @method('DELETE')
                    </form>


                </div>
            </div>
        </div>
    </div>

@endsection
