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
                            <h3>Assign Exam</h3>
                        </div>
                        <div class="module-body">

                            <form class="form-horizontal row-fluid" method="post" action="{{route('assign.exam')}}"> @csrf @method('POST')

                                <div class="control-group @error('quiz') alert alert-error @enderror">
                                    <label class="control-label" for="quiz">Select Quiz</label>
                                    <div class="controls">
                                        <select id="quiz" tabindex="1" data-placeholder="Select..." class="span4" name="quiz">
                                            <option value="">Select....</option>
                                            @foreach(App\Models\Quiz::all() as $quiz)
                                                <option value="{{$quiz->id}}" @if($quiz->id==old('quiz')) selected @endif >{{$quiz->name}}</option>
                                            @endforeach
                                        </select>
                                        <br>
                                        @error('quiz')
                                        <span class="text-error">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="control-group @error('user') alert alert-error @enderror">
                                    <label class="control-label" for="quiz">Select User</label>
                                    <div class="controls">
                                        <select id="user" tabindex="2" data-placeholder="Select..." class="span4" name="user">
                                            <option value="">Select....</option>
                                            @foreach(App\Models\User::all() as $user)
                                                <option value="{{$user->id}}" @if($user->id==old('user')) selected @endif >{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                        <br>
                                        @error('user')
                                        <span class="text-error">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>



                        </div>

                        <div class="module-foot">
                                <button tabindex="3" type="submit" class="btn btn-primary">Assign</button>


                            </form>


                        </div>
                    </div>


        </div>
    </div>

@endsection
