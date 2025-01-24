<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<center>
<strong><h1>Quiz App</h1></strong>
</center>

<hr>

# Configuration of this Laravel Project

```
"php": "^8.2",
"laravel/framework": "^11.31",

```


# Planning

## Description of the App

## Structure

### Directory structure

- views
    - admin
    - auth
    - ...
    - backend
        - layouts
            - navbar `blade.php`
            - sidebar `blade.php`
            - master `blade.php`
            - dashboard `blade.php`
            - footer `blade.php`
        - quiz
            - create `blade.php`
            - edit `blade.php`
            - update `blade.php`


### UI Structures

Sidebar
- Dashboard
- Create Quiz
- View All Quiz

## Models, Migrations and Relationships

- [See Model, Migration, and Relationships Here](#model-migration-and-relationships)


<hr>

# Laravel Scaffolding

## Get a basic authentication system

`composer require laravel/ui`
`php artisan ui vue --auth`
`npm install`
`npm run dev`

`Note`: If the command `npm install` shows this error:
`unable to resolve dependency tree`, it means there is version
incompatibility.

To solve it:
- Go to `package.json` and modify the version of vite in
`devDependencies` section as:
  ```json
  "devDependencies": {
      ...
      ...
      "vite": "^5.4.14",
  }
  ```
  This should solve the problem.

<hr>

# Integrate Admin Template

We are going to use `edmin` bootstrap template for our admin panel.
The template is located in `public/edmin_template`.

## Create js and Css links 

### Create a directory for admin

- Go to `resources/views` and create this directory structure:
  - views
    - admin
      - index.blade.php


### Create route

- Make this `admin.index` the default route. (Just for initial testing)

```php
Route::get('/', function () {
    return view('admin.index');
});
```

### Copy from [`edmin_template/index.html`](./public/edmin_template/index.html)

- From the template, copy the code from `index.html` file and 
paste it in `admin/index.blade.php`.

- To link css and js file, modify the links as:
  - `href="css/theme.css"` to `href="{{asset('edmin_template/css/theme.css')}}"`


## Create Different pages and Master file

### Create backend folder

- views
  - admin
  - auth
  - ...
  - backend
    - layouts
      - navbar
      - sidebar
      - master
      - dashboard
      - footer


### Extract different sections from `admin/index.blade.php`

- Extract different sections from `admin/index.blade.php` and 
paste in respective files in `views/backend/layouts/*`.

- Modify the `views/home.blade.php` as:
    - It extends the `@extends('backend.layouts.master')`.

```bladehtml
<!--A basic demonstratration of backend.layouts.master-->
@extends('backend.layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                   
                    

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```


# Model, Migration and Relationships

## Create Models and Migration files

- `php artisan make:model Quiz -m`
- `php artisan make:model Question -m`
- `php artisan make:model Answer -m`
- `php artisan make:model Result -m`
- `php artisan make:migration create_quiz_user_table --create=quiz_user`. This is a pivot
table for `quiz` and `user` table.

## Create field names and migrate the files

### Configure the database 

### `quizzes` migration 

- `id`
- `name` - string
- `description` - text
- `minutes` - integer

### `questions` migration

- `id`
- `questions` - string
- `quiz_id` - integer

### `answers` migration

- `id`
- `question_id` - integer
- `answer` - string
- `is_correct` - boolean

### `results` migration

- `id`
- `user_id` - integer
- `quiz_id` - integer
- `question_id` - integer
- `answer_id` - integer

### `users` migration

- `id`
- `users` - string
- `email` - string - unique
- `password` - string
- `visible_password` - string
- `occupation` - string - nullable
- `bio` - string - nullable
- `address` - string - nullable
- `phone` - string - nullable
- `is_admin` - integer - default=0

### `quiz_user` migration - pivot table

- `id`
- `quiz_id`
- `user_id`

### Refresh the migration

`php artisan migrate:refresh`


## Create fillables and Relationships
 
Fillables are the column names that can be used to manually store
the data into it. It is an array defined in the Model file.

If `$fillables` is not defined, then we can't store any data in any
fields in the database. It would throw a nice error in this case.

### User 

```php
    protected $fillable = [
        'name',
        'email',
        'password',
        'visible_password',
        'occupation',
        'address',
        'phone',
        'bio',
        'is_admin'
    ];

```

### Quiz

```php
    protected $fillable = [
        'name',
        'description',
        'minutes',
    ];

    public function questions(){
        return $this->hasMany(Question::class);
    }

```

### Question

```php
    protected $fillable = [
        'question',
        'quiz_id',
    ];

    public function answers(){
        return $this->hasMany(Answer::class);
    }
    
    public function quiz(){
        return $this->belongsTo(Quiz::class);
    }

```

### Answer

```php
    protected $fillable = [
        'question_id',
        'answer',
        'is_correct',
    ];

    public function question(){
        return $this->belongsTo(Question::class);
    }

```

### Result

```php
class Result extends Model
{
    protected $fillable = [
        'user_id',
        'quiz_id',
        'question_id',
        'answer_id',
    ];
    
    public function question(){
        return $this->belongsTo(Question::class);
    }

    public function answer(){
        return $this->belongsTo(Answer::class);
    }
    
}
```

[//]: # (Model, Migration and Relationships Completed)
<hr>

# Quiz Section

## Setup

### Create Controller 

`php artisan make:controller QuizController -r`

### Create a resource route

```php
Route::group([], function () {
    Route::resource('quiz', QuizController::class);
});
```

### Create a directory for backend view

`resources/views/backend/quiz/`

Structure:

- quiz
  - create.blade.php
  - index.blade.php
  - edit.blade.php



## Create Quiz form

### QuizController@create

```php
    public function create()
    {
        return view('backend.quiz.create');
    }
```


### quiz/create.blade.php

```bladehtml
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
                <h3>Create Quiz</h3>
            </div>
            <div class="module-body">

                <form class="form-horizontal row-fluid" method="post" action="{{route('quiz.store')}}"> @csrf
                    <div class="control-group @error('name') alert alert-error @enderror">
                        <label class="control-label" for="basicinput">Quiz Name</label>
                        <div class="controls">
                            <input name="name" type="text" id="basicinput" placeholder="Quiz Name..." class="span8" value="{{old('name')}}" ><br>
                            @error('name')
                            <span class="text-error">{{$message}}</span>
                            @enderror
                        </div>

                    </div>

                    <div class="control-group @error('description') alert alert-error @enderror">
                        <label class="control-label" for="description">Description</label>
                        <div class="controls">
                            <input name="description" id="description" type="text" placeholder="Description..." class="span8" value="{{old('description')}}"><br>
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
                                <option value="{{$i}}" @if(old('minutes')==$i) selected @endif >{{$i}}</option>
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
                            <button type="submit" class="btn btn-success">Create</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection

```

### Create a method in Quiz Model `to store a new quiz`

```php
    public function storeQuiz($data){
        return Quiz::create($data);
    }
```

### QuizController@store

```php
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'description' => 'required',
            'minutes' => 'required'
        ]);

        (new Quiz)->storeQuiz($request->all());

        return redirect()->back()->with('message', 'Quiz created successfully');
    }
```

### Create a method in Quiz Model `to get all the quiz`

```php
    public function allQuiz(){
        return Quiz::get();
    }
```

### QuizController@index

```php
    public function index()
    {
        $quizzes = (new Quiz)->allQuiz();
        return view('backend.quiz.index', compact('quizzes'));
    }
```

### quiz/index.blade.php

```bladehtml
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
                                <th>Quiz Name</th>
                                <th>Description</th>
                                <th>Time Allotted</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($quizzes as $key=>$quiz)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$quiz->name}}</td>
                                <td>{{$quiz->description}}</td>
                                <td>{{$quiz->minutes}} minutes</td>
                                <td>
                                    <a href="{{route('quiz.edit', $quiz->id)}}">
                                        <button class="btn btn-primary">Edit</button>
                                    </a>
                                </td>
                                <td>
                                    <form id="delete-form-{{$quiz->id}}"
                                        method="post" action="{{route('quiz.destroy', $quiz->id)}}">
                                        @csrf @method('DELETE')
                                    </form>
                                    <a href="#" onclick="
                                        if(confirm('Do you want to delete?')){
                                            event.preventDefault();
                                            document.getElementById('delete-form-{{$quiz->id}}').submit()
                                        } else {
                                            event.preventDefault();
                                        }
                                    ">
                                        <input type="submit" value="Delete" class="btn btn-danger">
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

@endsection

```


### Create a method in Quiz Model `to get a quiz by id`

```php
    public function getQuizById($id){
        return Quiz::find($id);
    }
```

### QuizController@edit

```php
    public function edit(string $id)
    {
        $quiz = (new Quiz)->getQuizById($id);
        return view('backend.quiz.edit', compact('quiz'));
    }
```

### quiz/edit.blade.php

```bladehtml
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

```

### Create a method in Quiz Model `to update a quiz by id`

```php
    public function updateQuiz($id,$data){
        return Quiz::find($id)->update($data);
    }
```

### QuizController@update

```php
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'description' => 'required',
            'minutes' => 'required'
        ]);

        (new Quiz)->updateQuiz($id, $request->all());
        return redirect()->to(route('quiz.index'))->with('message', 'Quiz updated successfully');
    }
```

### Create a method in Quiz Model `to delete a quiz by id`

```php
    public function deleteQuiz($id){
        return Quiz::destroy($id);
    }
```

### QuizController@destroy

```php
    public function destroy(string $id)
    {
        (new Quiz)->deleteQuiz($id);
        return redirect()->back()->with('message', 'Quiz deleted successfully');
    }
```

## Update the SideBar

Sidebar Structure (Till Now):
- Dashboard
- Create Quiz
- View All Quiz

### Note

Still, we have not configured the Dashboard. Let's configure it.
- It should extend the `backend.layouts.master`.
- All the things should be inside the `content` section.
  ```
    @extends('backend.layouts.master')
    @section('content')
        <div>...
        ...</div>
    @endsection
  ```
  
[//]: # (Quiz Section Completed)
<hr>

# Question Section
