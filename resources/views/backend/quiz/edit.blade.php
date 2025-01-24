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
                    <h3>Update Quiz</h3>
                </div>
                <div class="module-body">

                    <form class="form-horizontal row-fluid" method="post" action="{{route('quiz.update', $quiz->id)}}"> @csrf @method('PATCH')
                        <div class="control-group @error('name') alert alert-error @enderror">
                            <label class="control-label" for="basicinput">Quiz Name</label>
                            <div class="controls">
                                <input name="name" type="text" id="basicinput" placeholder="Quiz Name..." class="span8" value="{{$quiz->name}}" ><br>
                                @error('name')
                                    <span class="text-error">{{$message}}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="control-group @error('description') alert alert-error @enderror">
                            <label class="control-label" for="description">Description</label>
                            <div class="controls">
                                <input name="description" id="description" type="text" placeholder="Description..." class="span8" value="{{$quiz->description}}"><br>
                                @error('description')
                                    <span class="text-error">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="control-group @error('minutes') alert alert-error @enderror">
                            <label class="control-label" for="minutes">Time </label>
                            <div class="controls">
                                <select tabindex="1" data-placeholder="Select..." class="span2" name="minutes">
                                    <option value="">Select....</option>
                                    @for($i=5; $i<=60; $i+=5)
                                        <option value="{{$i}}" @if($quiz->minutes==$i) selected @endif>{{$i}}</option>
                                    @endfor
                                </select>
                                <span class="help-inline">minutes</span>
                                <br>
                                @error('minutes')
                                    <span class="text-error">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="control-group" style="text-align:center">
                            <div class="">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
