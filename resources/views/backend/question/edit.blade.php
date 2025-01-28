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
                    <h3>Update Question</h3>
                </div>
                <div class="module-body">

                    <form class="form-horizontal row-fluid" method="post" action="{{route('question.update', $question->id)}}">
                        @csrf
                        @method("PUT")

                        <div class="control-group @error('quiz') alert alert-error @enderror">
                            <label class="control-label" for="quiz">Choose Quiz</label>
                            <div class="controls">
                                <select id="quiz" tabindex="1" data-placeholder="Select..." class="span4" name="quiz">
                                    <option value="">Select....</option>
                                    @foreach(App\Models\Quiz::all() as $quiz)
                                        <option value="{{$quiz->id}}" @if($quiz->id==$question->quiz_id) selected @endif >{{$quiz->name}}</option>
                                    @endforeach
                                </select>
                                <br>
                                @error('quiz')
                                <span class="text-error">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="control-group @error('question') alert alert-error @enderror">
                            <label class="control-label" for="basicinput">Question</label>
                            <div class="controls">
                                <input name="question" type="text" id="basicinput" placeholder="Question..." class="span10" value="{{$question->question}}" required><br>
                                @error('question')
                                <span class="text-error">{{$message}}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="control-group">
                            <label class="control-label" for="options">Options</label>
                            <div class="controls">
                                @for($i=0; $i<4; $i++)
                                    <input name="options[]" id="options" type="text" placeholder="Option {{$i+1}}..." class="span8 border-red" value="{{$answers[$i]->answer}}" style="margin-top:10px;">
                                    <input type="radio" name="correct_answer" value="{{$i}}" @if($answers[$i]->is_correct==1) checked @endif><span class="help-inline">Is Correct</span><br>
                                    @error('option'.$i)
                                    <span class="text-error">{{$message}}</span><br>
                                    @enderror
                                @endfor
                            </div>
                        </div>


                        <div class="control-group" style="text-align:center">
                            <div class="">
                                <button type="submit" class="btn btn-success">Update Question</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
