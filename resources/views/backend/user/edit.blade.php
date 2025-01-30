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
                    <h3>Update User</h3>
                </div>
                <div class="module-body">

                    <form class="form-horizontal row-fluid" method="post" action="{{route('user.update', $user->id)}}"> @csrf
                        @method('PUT')

                        <div class="control-group @error('name') alert alert-error @enderror">
                            <label class="control-label" for="name">Name*</label>
                            <div class="controls">
                                <input name="name" type="text" id="name" placeholder="Name..." class="span10" value="{{$user->name}}" ><br>
                                @error('name')
                                <span class="text-error">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="control-group @error('email') alert alert-error @enderror">
                            <label class="control-label" for="email">Email*</label>
                            <div class="controls">
                                <input name="email" type="email" id="email" placeholder="Email..." class="span10" value="{{$user->email}}" ><br>
                                @error('email')
                                <span class="text-error">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="control-group @error('password') alert alert-error @enderror">
                            <label class="control-label" for="password">Password*</label>
                            <div class="controls">
                                <input name="password" type="password" id="password" placeholder="Password..." class="span10" ><br>
                                @error('password')
                                <span class="text-error">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="control-group @error('visible_password') alert alert-error @enderror">
                            <label class="control-label" for="visible_password">Visible Password*</label>
                            <div class="controls">
                                <input name="visible_password" type="password" id="visible_password" placeholder="Password..." class="span10" ><br>
                                @error('visible_password')
                                <span class="text-error">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="control-group @error('occupation') alert alert-error @enderror">
                            <label class="control-label" for="occupation">Occupation</label>
                            <div class="controls">
                                <input name="occupation" type="text" id="occupation" placeholder="Occupation..." class="span10" value="{{$user->occupation}}" ><br>
                                @error('occupation')
                                <span class="text-error">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="control-group @error('bio') alert alert-error @enderror">
                            <label class="control-label" for="bio">Bio</label>
                            <div class="controls">
                                <input name="bio" type="text" id="bio" placeholder="Bio..." class="span10" value="{{$user->bio}}" ><br>
                                @error('bio')
                                <span class="text-error">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="control-group @error('address') alert alert-error @enderror">
                            <label class="control-label" for="address">Address</label>
                            <div class="controls">
                                <textarea name="address" type="text" id="address" placeholder="Address..." class="span10">{{$user->address}}</textarea><br>
                                @error('address')
                                <span class="text-error">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="control-group @error('phone') alert alert-error @enderror">
                            <label class="control-label" for="phone">Phone*</label>
                            <div class="controls">
                                <input name="phone" type="text" id="phone" placeholder="Phone Number..." class="span10" value="{{$user->phone}}" ><br>
                                @error('phone')
                                <span class="text-error">{{$message}}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="control-group" style="text-align:center">
                            <div class="">
                                <button type="submit" class="btn btn-success">Update User</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
