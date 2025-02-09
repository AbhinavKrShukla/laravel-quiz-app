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
    public function users(){
        return $this->belongsToMany(User::class, 'quiz_user');
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

- backend/quiz
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

## Setup

### Create Controller

`php artisan make:controller QuestionController -r`

### Create a resource Route

```php
Route::resource('question', QuestionController::class);
```

### Create directory structure for view

- backend/question
    - create.blade.php
    - index.blade.php
    - edit.blade.php


## CRUD

### Create question

#### QuestionController@create

  - Copy from `quiz/create.blade.php`.
  - Change the required names in the form.
  - Create four options in this way:
    - inside a for loop starting with `i=0` to `i<4`,
    - `<input name="options[]">`: options[] is an array as we are
  taking multiple inputs from the single from.
    - create another `<input type="radio" value="{{$i}}">` for each `option` and 
  its value is the $i variable of the loop.


```php
    public function create()
    {
        return view('backend.question.create');
    }
```

#### question/create.blade.php

[See the `question/create.blade.php` here](./resources/views/backend/question/create.blade.php)


### Store the Question

#### Validate

- Create a `validateForm()` method in the class, to separately validate 
the `$request`.
  - Validate the form:
    - `quiz`: required
    - `question`: required
    - `options`: bail|required|array|min:3
      - Here, `bail` only checks the input for conditions only if it is not empty.
  So, if any option is empty, it will not check for conditions like required, array and min:3.
    - `options.*`: bail|required|string|distinct
      - This is for the four different options.
    - `correct_answer`: required.
  - Return the $data

```php
    public function validateForm($data)
    {
        $this->validate($data, [
            'quiz' => 'required',
            'question' => 'required|min:3',
            'options' => 'bail|required|array|min:3',
            'options.*' => 'bail|required|string|distinct',
            'correct_answer' => 'required',
        ]);
        return $data;
    }
```

#### Write the `store()` method as:

```php
    public function store(Request $request)
    {
        $data = $this->validateForm($request);
        $question = (new Question)->storeQuestion($request);
        (new Answer)->storeAnswer($data, $question);
        return redirect()->back()->with('message', 'Question created successfully');
    }
```

Now create the `storeQuestion()` and `storeAnswer()` methods in their
respective models.

#### Create `storeQuestion()` method in `Question` model

```php
    public function storeQuestion($data){
        $data['quiz_id'] = $data['quiz'];
        return Question::create($data);

    }
```

#### Create `storeAnswer()` method in `Answer` model

```php
    public function storeAnswer($data, $question){
        foreach($data['options'] as $key=>$answer){
            $is_correct = false;
            if($key==$data['correct_answer']){
                $is_correct = true;
            }
            Answer::create([
                'question_id' => $question->id,
                'answer' => $data['answer'],
                'is_correct' => $data['is_correct'],
            ]);
        }
    }
```


### Get all the Questions

#### Update Question Model and create a method `to get all the questions`

```php

    private $limit = 10;
    private $order = 'DESC';
    
    public function getQuestions(){
        return Question::orderBy('created_at', $this->order)->with('quiz')->paginate($this->limit);
    }

```

#### Create QuestionController@index

```php
    public function index()
    {
        $questions = (new Question)->getQuestions();
        return view('backend.question.index', compact('questions'));
    }

```

#### Create view for index.blade.php

[See the `question/create.blade.php` here](./resources/views/backend/question/index.blade.php)

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
                <h3>All Questions</h3>
            </div>
            <div class="module-body">


                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Question</th>
                        <th>Quiz</th>
                        <th>Created on</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($questions) == 0)
                    <tr>
                        No questions to display!
                    </tr>
                    @else
                    @foreach($questions as $key=>$question)

                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$question->question}}</td>
                        <td>{{$question->quiz->name}}</td>
                        <td>{{date('F d, Y', strtotime($question->created_at))}}</td>
                        <td>
                            <a href="{{route('question.edit', $question->id)}}">
                                <button class="btn btn-primary">Edit</button>
                            </a>
                        </td>
                        <td>
                            <form id="delete-form-{{$question->id}}"
                                  method="post" action="{{route('question.destroy', $question->id)}}">
                                @csrf @method('DELETE')
                            </form>
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
                        </td>
                    </tr>
                    @endforeach
                    @endif


                    </tbody>
                </table>

                <div class="pagination pagination-centered">
                    {{$questions->links('pagination::bootstrap-5')}}
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

```


### Show a Particular Question

#### Add a new column after the `created on` in `index.blade.php`

#### Create `getquestion()` in Question Model `to get a question`

```php
    public function getQuestion($id){
        return Question::find($id);
    }
```

#### Create `getanswer()` in Answer Model `to get answers of a question`

```php
    public function getAnswers($question){
        return Answer::where('question_id', $question)->get();
    }
```

#### Create QuestionController@show

- This returns the view page for a single question.

```php
    public function show(string $id)
    {
        $question = (new Question)->getQuestion($id);
        $answers = (new Answer)->getAnswers($id);
        return view('backend.question.show', compact('question', 'answers'));
    }
```

#### `question/show.blade.php`

[See the `question/show.blade.php` here](./resources/views/backend/question/show.blade.php)


### Update a Question

Steps:
- Create Methods in Question Model
  - `getQuestion($id)` - to get a question by id
  - `updateQuestion($id, $data)` - to update a question
    - find the question by $id
    - get the `question` and `quiz_id` fields
    - save to database.
  - `deleteQuestion($id)` - to delete a particular question
- Create Methods in Answer Model
  - `getAnswers($question)` - to get all the answers to a particular question 
  - `updateAnswer($data, $question)` - to update all the answers
    - delete the answer using `deleteAnswer()`, then
    - use `storeAnswer()` of the same class to store new answer.
  - `deleteAnswer($question_id)` - to delete all the answers of a question 
- Create QuestionController@edit()
  - get the question
  - get its answers
  - return the view page of edit
- Create question/edit.blade.php
  - copy the `create.blade.php`
  - change the values where required
  - get the values from database for previously filled fields.
- Create QuestionController@update()
  - use `validateForm()` to validate the data
  - use `updateQuestion()` method of `Question` Model to update question. 
  - use `updateAnswer()` method of `Answer` Model to update the answer. 
  

#### Create Methods in `Question` Model

```php
    public function getQuestion($id){
        return Question::find($id);
    }

    public function updateQuestion($id, $data){
        $question = Question::find($id);
        $question->question = $data['question'];
        $question->quiz_id = $data['quiz'];
        $question->save();
        return $question;
    }

    public function deleteQuestion($id){
        $question = Question::find($id);
        $question->delete();

    }
```

#### Create Methods in `Answer` Model

```php
    public function getAnswers($question){
        return Answer::where('question_id', $question)->get();
    }

    public function updateAnswer($data, $question){
        $this->deleteAnswer($question->id);
        $this->storeAnswer($data, $question);
    }

    public function deleteAnswer($question_id){
        Answer::where('question_id', $question_id)->delete();
    }
```

#### QuestionController@edit

```php
    public function edit(string $id)
    {
        $question = (new Question)->getQuestion($id);
        $answers = (new Answer)->getAnswers($id);
        return view('backend.question.edit', compact('question', 'answers'));
    }
```

#### question/edit.blade.php

[See the `question/edit.blade.php` here](./resources/views/backend/question/edit.blade.php)

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

```

#### QuestionCotroller@update

```php
    public function update(Request $request, string $id)
    {
        $data = $this->validateForm($request);
        $question = (new Question)->updateQuestion($id, $data);
        (new Answer)->updateAnswer($data, $question);
        return redirect()->route('question.show', $id)->with('message', 'Question updated successfully');
    }
```


### Delete question and answer

Steps:
- Create delete button in `index` and `show` files which hits 
the delete route of the question.
- QuestionController@destroy() method
  - delete the question using `deleteQuestion($id)` method of the Question Model.
  - delete the answer using `deleteAnswer($id)` method of the Answer Model.
  - return to `index` route with a success message.
  
#### QuestionController@destroy

```php
    public function destroy(string $id)
    {
        (new Question)->deleteQuestion($id);
        (new Answer)->deleteAnswer($id);
        return redirect()->route('question.index')->with('message', 'Question deleted successfully');
    }
```

### Get questions by quiz

Steps:
- Create a route `quiz/{$id}/questions` to get quiz id (so that we can filter the questions).
- Update the `index` method of `quiz`: add a button against each `quiz` to hit the above route. 
- Create the `questions()` method in `QuizController`
  - get the quiz along with the questions
  - return to view page `backend.quiz.questions` along with the questions
- Create `backend/quiz/questions.blade.php`
  - copy from `show.blade.php`
  - iterate the `$quiz` returned by controller
  - get the questions
  - format the page as you like.

#### Create route

```php
Route::get('quiz/{id}/questions', [QuizController::class, 'questions'])->name('quiz.questions');
```

#### Create `question()` in QuizController

```php
    public function questions($id){
        $quizzes = Quiz::with('questions')->where('id', $id)->get();
        return view('backend.quiz.questions', compact('quizzes'));
    }
```

#### Create `backend/quiz/questions.blade.php`

[See the `quiz/questions.blade.php` here](./resources/views/backend/quiz/questions.blade.php)

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

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            @foreach($quizzes as $quiz)
                <div style="margin: 10px; text-align: center; font-size: 15px;" class="alert alert-success">
                    <strong>Quiz Name:</strong>
                    <span class="badge badge-info">
                        {{$quiz->name}}
                    </span>
                </div>

                @foreach($quiz->questions as $qno=>$question)

                    <div class="module">
                        <div class="module-head">
                            <h3>{{$qno+1}}. {{$question->question}}</h3>
                        </div>
                        <div class="module-body">
                            <table class="table">
                                @foreach($question->answers as $key=>$answer)
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
                @endforeach

            @endforeach
        </div>
    </div>

@endsection

```

## Update the Sidebar

Sidebar Structure (Till Now):
- Dashboard
- Create Quiz
- View All Quiz
- ++++++++++++++
- Create Question
- View All Question

[//]: # (Question Section Completed)

# User

Steps:
- Setup
  - Create UserController -r
  - Create resource route
  - Ensure $fillbles are defined in the User Model
- CRUD
  - Create User
    - Controller method to get view page of form
    - Write the view page of form
    - Controller method to store the data
  - Get All Users
    - Controller method to get all users
    - View Page to show all the users
    - Links to Edit and Delete User
  - Show a Single User
    - Controller method to get a user
    - View page to show the user
    - Links to Edit and Delete user
  - Update User
    - Controller method to get a user
    - View Page to edit its details
    - Controller method to update the data
  - Delete User
    - Controller method to delete the user
      - Logged in user cannot delete itself
      - Only Admin can delete it
- Configure the Sidebar
  - Sidebar
    - ...
    - ...
    - ++++++++
    - Create User
    - Get All Users

## Setup

### Create UserController 

`php artisan make:controller UserController -r`

### Create resource route

```php
Route::resource('user', UserController::class);
```

## CRUD 

### Create User

#### UserController@create

```php
    public function create()
    {
        return view('backend.user.create');
    }
```

#### user/create.blade.php

[user/create.blade.php](./resources/views/backend/user/create.blade.php)

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
                    <h3>Create User</h3>
                </div>
                <div class="module-body">

                    <form class="form-horizontal row-fluid" method="post" action="{{route('user.store')}}"> @csrf

                        <div class="control-group @error('name') alert alert-error @enderror">
                            <label class="control-label" for="name">Name*</label>
                            <div class="controls">
                                <input name="name" type="text" id="name" placeholder="Name..." class="span10" value="{{old('name')}}" required><br>
                                @error('name')
                                <span class="text-error">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="control-group @error('email') alert alert-error @enderror">
                            <label class="control-label" for="email">Email*</label>
                            <div class="controls">
                                <input name="email" type="email" id="email" placeholder="Email..." class="span10" value="{{old('email')}}" required><br>
                                @error('email')
                                <span class="text-error">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="control-group @error('password') alert alert-error @enderror">
                            <label class="control-label" for="password">Password*</label>
                            <div class="controls">
                                <input name="password" type="password" id="password" placeholder="Password..." class="span10" value="{{old('password')}}" required><br>
                                @error('password')
                                <span class="text-error">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="control-group @error('visible_password') alert alert-error @enderror">
                            <label class="control-label" for="visible_password">Visible Password*</label>
                            <div class="controls">
                                <input name="visible_password" type="password" id="visible_password" placeholder="Password..." class="span10" value="{{old('visible_password')}}" required><br>
                                @error('visible_password')
                                <span class="text-error">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="control-group @error('occupation') alert alert-error @enderror">
                            <label class="control-label" for="occupation">Occupation</label>
                            <div class="controls">
                                <input name="occupation" type="text" id="occupation" placeholder="Occupation..." class="span10" value="{{old('occupation')}}" ><br>
                                @error('occupation')
                                <span class="text-error">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="control-group @error('bio') alert alert-error @enderror">
                            <label class="control-label" for="bio">Bio</label>
                            <div class="controls">
                                <input name="bio" type="text" id="bio" placeholder="Bio..." class="span10" value="{{old('bio')}}" ><br>
                                @error('bio')
                                <span class="text-error">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="control-group @error('address') alert alert-error @enderror">
                            <label class="control-label" for="address">Address</label>
                            <div class="controls">
                                <textarea name="address" type="text" id="address" placeholder="Address..." class="span10" >{{old('address')}}</textarea><br>
                                @error('address')
                                <span class="text-error">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="control-group @error('phone') alert alert-error @enderror">
                            <label class="control-label" for="phone">Phone*</label>
                            <div class="controls">
                                <input name="phone" type="text" id="phone" placeholder="Phone Number..." class="span10" value="{{old('phone')}}" ><br>
                                @error('phone')
                                <span class="text-error">{{$message}}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="control-group" style="text-align:center">
                            <div class="">
                                <button type="submit" class="btn btn-success">Create User</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection

```

#### UserController@store

```php
    public function store(Request $request)
    {
        $this->validateUserDetails($request);
        (new User)->storeUser($request);
        return redirect()->back()->with('message', 'User created successfully');
    }
```

#### UserController@validateUserDetails()

```php
    public function validateUserDetails(Request $request){

        if ($request->isMethod('POST')) {
            $this->validate($request,[
                'name' => 'required|max:255|min:3',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6',
                'visible_password' => 'required|min:6|same:password',
                'occupation' => 'max:255',
                'address' => 'required|max:255',
                'phone' => 'min:5|max:20',
            ]);
        }

        if ($request->isMethod('PUT') || $request->isMethod('PATCH')) {
            $validatedData = $this->validate($request,[
                'name' => 'bail|required|max:255|min:3',
                'email' => 'bail|required|email|max:255|unique:users,id',
                'occupation' => 'max:255',
                'address' => 'max:255',
                'bio' => 'max:255',
                'phone' => 'min:5|max:20',
            ]);

            if (isset($request->password)){
                $this->validate($request, [
                    'password' => 'required|min:6',
                    'visible_password' => 'required|min:6|same:password',
                ]);
                $validatedData['password'] = $request->password;
                $validatedData['visible_password'] = $request->visible_password;
            }

            // Filter out null values from the validated data
            return $filteredData = array_filter($validatedData, function($value) {
                return !is_null($value);
            });
        }
    }
```

#### User@storeUser() - Model Method

```php
    public function storeUser(Request $request){
        User::create($request->all());
    }
```


### Get All Users

#### User@getAllUsers

```php
    public function getAllUsers(){
        return User::orderBy('created_at', $this->order)->paginate($this->limit);
    }
```

#### UserController@index

```php
    public function index()
    {
        $users = (new User)->getAllUsers();
        return view('backend.user.index', compact('users'));
    }
```

#### user/index.blade.php

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
                    <h3>All Users</h3>
                </div>
                <div class="module-body">


                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Occupation</th>
                                <th>View</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($users) == 0)
                                <tr>
                                    No user to display!
                                </tr>
                            @else
                                @foreach($users as $key=>$user)

                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->phone}}</td>
                                        <td>{{$user->occupation}}</td>
                                        <td>
                                            <a href="{{route('user.show', $user->id)}}">
                                                <button class="btn btn-info">View</button>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{route('user.edit', $user->id)}}">
                                                <button class="btn btn-primary">Edit</button>
                                            </a>
                                        </td>
                                        <td>
                                            <form id="delete-form-{{$user->id}}"
                                                  method="post" action="{{route('user.destroy', $user->id)}}">
                                                @csrf @method('DELETE')
                                            </form>
                                            <a href="#" onclick="
                                                if(confirm('Do you want to delete?')){
                                                    event.preventDefault();
                                                    document.getElementById('delete-form-{{$user->id}}').submit()
                                                } else {
                                                    event.preventDefault();
                                                }
                                            ">
                                                <input type="submit" value="Delete" class="btn btn-danger">
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif


                        </tbody>
                    </table>

                    <div class="pagination pagination-centered">
                        {{$users->links('pagination::bootstrap-5')}}
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

```


### Show a Single User

#### User@getUserById

```php
    public function getUserById($id){
        return User::find($id);
    }
```

#### UserController@show

```php
    public function show(string $id)
    {
        $user = (new User)->getUserById($id);
        return view('backend.user.show', compact('user'));
    }
```

#### user/show.blade.php

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

                    <h3>{{$user->name}}</h3>

                </div>
                <div class="module-body">

                    <table class="table table-striped">

                        <tr>
                            <td>Email</td>
                            <td>
                                <strong>
                                {{$user->email}}
                                </strong>
                            </td>
                        </tr>

                        <tr>
                            <td>Phone</td>
                            <td>
                                <strong>
                                    {{$user->phone}}
                                </strong>
                            </td>
                        </tr>

                        <tr>
                            <td>Occupation</td>
                            <td>
                                <strong>
                                    {{$user->occupation}}
                                </strong>
                            </td>
                        </tr>

                        <tr>
                            <td>Bio</td>
                            <td>
                                <strong>
                                    {{$user->bio}}
                                </strong>
                            </td>
                        </tr>

                        <tr>
                            <td>Address</td>
                            <td>
                                <strong>
                                    {{$user->address}}
                                </strong>
                            </td>
                        </tr>


                    </table>

                </div>

                <div class="module-foot">
                    <a href="{{route('user.edit', $user->id)}}">
                        <button class="btn btn-primary">Edit</button>
                    </a>

                    <a href="#" onclick="
                        if(confirm('Do you want to delete?')){
                            event.preventDefault();
                            document.getElementById('delete-form-{{$user->id}}').submit()
                        } else {
                            event.preventDefault();
                        }
                    ">
                        <input type="submit" value="Delete" class="btn btn-danger">
                    </a>

                    <a href="{{route('user.index', $user->id)}}" class="pull-right">
                        <button class="btn btn-inverse">Back</button>
                    </a>

                    <form id="delete-form-{{$user->id}}"
                          method="post" action="{{route('user.destroy', $user->id)}}">
                        @csrf @method('DELETE')
                    </form>



                </div>
            </div>
        </div>
    </div>

@endsection

```


### Update User

#### UserController@edit

```php
    public function edit(string $id)
    {
        $user = (new User)->getUserById($id);
        return view('backend.user.edit', compact('user'));
    }
```

#### user/edit.blade.php

[user/edit.blade.php](./resources/views/backend/user/edit.blade.php)

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

```

#### UserController@update

```php
    public function update(Request $request, string $id)
    {
        $filteredData = $this->validateUserDetails($request);
        (new User)->updateUser($filteredData, $id);
        return redirect()->route('user.show', $id)->with('message', 'User updated successfully');
    }
```

#### User@updateUser - Model method

```php
    public function updateUser($filteredData, $id){
        User::find($id)->update($filteredData);
    }
```

### Delete User

#### UserController@destroy

```php
    public function destroy(string $id)
    {
        // Prevent logged in user to delete itself
//        if(auth()->user()->id == $id){
//            return redirect()->back()->with('error', 'You cannot delete yourself!');
//        }

        // Admin can only delete a user
        (new User)->deleteUserById($id);
        return redirect()->route('user.index')->with('message', 'User deleted successfully!');
    }
```

The commented section will be implemented later, when the login feature will be implemented.

[//]: # (User Completed)

## Configure Sidebar

- Sidebar
  - ...
  - ...
  - ++++++++
  - Create User
  - Get All Users

# Admin

## Create Seeder file

- Go to [database/seeders/DatabaseSeeder.php](database/seeders/DatabaseSeeder.php)
- Create a User Instance and fill the fields, and then save it in database.
```php
    public function run(): void
    {
        $admin = new User();
        $admin->name = "admin";
        $admin->email = "admin@gmail.com";
        $admin->email_verified_at = NOW();
        $admin->password = bcrypt("password");
        $admin->visible_password = "password";
        $admin->occupation = "CEO";;
        $admin->address = "Ranchi";
        $admin->phone = "321321321";
        $admin->is_admin = 1;
        $admin->save();
    }
```
- Run the db seeder via artisan command:
`php artisan db:seed`

## Make and Implement Middleware

Steps:
1. Create a middleware file
2. Write the middleware logic
    - Here, if the user is admin then continue the 
execution `$next($request)`, else abort as unauthorized 401.
3. Put it in the routes
4. No need to register the middleware anywhere in laravel 11.3. This is all.

### Create Middleware

`php artisan make:middleware isAdmin`

### Write the middleware logic

```php
class isAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->is_admin == 1) {
            return $next($request);
        }
        abort(401);
    }
}
```

### Put it in the routes

```php
Route::middleware(['auth', isAdmin::class])->group( function () {
    Route::get('/', function () {
        return view('backend.layouts.dashboard');
    }); 

    Route::resource('quiz', QuizController::class);
    Route::resource('question', QuestionController::class);
    Route::get('quiz/{id}/questions', [QuizController::class, 'questions'])->name('quiz.questions');
    Route::resource('user', UserController::class);
});
```


## Implement logout feature

- Go to [resources/views/layouts/app.blade.php](resources/views/layouts/app.blade.php)
and copy the logout login (<a> and <form>) and paste it in the 
[resources/views/backend/layouts/sidebar.blade.php](resources/views/backend/layouts/sidebar.blade.php)
- Do the same in navbar.


## Setup home link

```php
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/', [HomeController::class, 'index'])->name('admin.dashboard');
```

```php
    public function index()
    {
        if(auth()->user()->is_admin == 1)
        {
            return view('backend.layouts.dashboard');
        }
        return view('home');
    }
```

[//]: # (Admin Section Completed)

<hr>

# Assign Exam

Steps:
- Setup
  - Create ExamController
  - Create folder: `view/backend/assign.blade.php`
  - Define relationship b/w quiz and users: `quiz belongsToMany users`.
- CRUD
  - Assign Exam
    - Create get route -> ExamController@create -> backend/assign.blade.php
    - Create post route -> ExamController@assign -> Quiz.assignExam() -> Store in `quiz_user` pivot in db.
  - Admin view Exam and User
    - 
  - Remove assigned exam
    - Ensure there is a delete button in the index page against each assigned user.
    - Condition: the assigned exam can be deleted only if it is not attempted by the user.
- Configure Sidebar
  - Sidebar
    - ...
    - ...
    - ++++++++
    - Assign Exam
    - View User Exam

## Setup

### Create ExamController

`php artisan make:controller ExamController`

### Create `view/backend/assign.blade.php`

### Ensure relationship between quiz and user

```php
// Quiz model
    public function users(){
        return $this->belongsToMany(User::class, 'quiz_user');
    }
```

## CRUD

### Assign Exam

#### Create get route

```php
// under auth middleware
    Route::get('exam/assign', [ExamController::class, 'create']);
```

#### ExamController@create

```php
    public function create()
    {
        return view('backend.exam.assign');
    }
```

#### Write the view page `view/backend/assign.blade.php`

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

```


#### Create post route

```php
// under auth middleware
    Route::post('exam/assign', [ExamController::class, 'assignExam'])->name(assign.exam);
```

#### ExamController@assignExam

```php
    public function assignExam(Request $request)
    {
        $this->validate($request, [
            'quiz' => 'required',
            'user' => 'required',
        ]);

        $quiz = (new Quiz)->assignExam($request->all());
        return redirect()->back()->with('message', 'Exam assigned successfully');
        
    }
```

#### Quiz@assignExam

```php
    public function assignExam($data){
        $quizId = $data['quiz'];
        $userId = $data['user'];
        return Quiz::find($quizId)->users()->syncWithoutDetaching($userId);
    }
```

### Admin view Exam and User

Steps
- Create get route `exam/user` -> ExamController@userExam -> `backend/exam/index.blade.php` with `all the quizzes`

#### Create get route

```php
Route::get('exam/user', [ExamController::class, 'userExam'])->name('view.exam');
```

#### ExamController@userExam

```php
    public function userExam()
    {
        $quizzes = Quiz::get();
        return view('backend.exam.index', compact('quizzes'));
    }
```

#### backend/exam/index.blade.php

Steps:
- Iterate all the quizzes
  - Iterate all the `$quiz->users`
    - Display the info
    - The `View Questions` button against each user redirects to 
    questions of particular quiz.

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
                            <th>Name</th>
                            <th>Quiz</th>
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
                                    </tr>
                                @endforeach

                            @endforeach

                        @else

                            <div style="margin: 10px;">
                                No Quiz to Dispaly!
                            </div>

                        @endif

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

@endsection

```

### Remove assigned exam

Steps
- Create post-route
- Add delete button in index page with hits this post-route
- Create ExamController@removeExam()
  
#### Create post-route

```php
    Route::post('exam/remove', [ExamController::class, 'removeExam'])->name('exam.remove');
```

#### Add delete button in index page

```bladehtml
<td>
    <form action="{{route('exam.remove')}}" method="post"> @csrf
        <input type="hidden" name="user_id" value="{{$user->id}}"/>
        <input type="hidden" name="quiz_id" value="{{$quiz->id}}"/>
        <button type="submit" class="btn btn-danger">Remove</button>
    </form>
</td>
```

#### Create ExamController@removeExam()

```php
    public function removeExam(Request $request)
    {
        $userId = $request->get('user_id');
        $quizId = $request->get('quiz_id');
        $quiz = Quiz::find($quizId);
        $result = Result::where('quiz_id', $quizId)->where('user_id', $userId)->exists();

        if ($result) {
            return redirect()->back()->with('message', 'Quiz already played by the user! , So can\'t delete it!');
        } else {
            $quiz->users()->detach($userId);
            return redirect()->back()->with('message', 'Quiz unassigned successfully!');

        }
    }
```

## Configure Sidebar

- Configure the Sidebar
  - Sidebar
    - ...
    - ...
    - ++++++++
    - Assign Exam
    - View User Exam

[//]: # (Assign Exam completed)

<hr><hr>

# Frontend

## Get Details of Quiz

### Create a method in Quiz model to get all the played quiz of the logged-in user

Purpose - get all the quiz attempted by the user
Steps-
- Create an empty array, which will store the attempted Quiz.
- Get the user id.
- Get the data from Result for this user id.
- Iterate along the data and push in the array.
- Return the array.


```php
    public function hasQuizAttempted()
    {
        $attemptQuiz = [];
        $authUser = auth()->user()->id;
        $user = Result::where('user_id', $authUser)->get();
        foreach ($user as $u) {
            array_push($attemptQuiz, $u->quiz_id);
        }
        return $attemptQuiz;
    }
```


### HomeController@index

Purpose:
- To get the quizzez played by the user; is any quiz assigned to it; which quiz he has completed.
Steps:
- If the user is admin, open the admin dashboard view. Else continue.
- Get the auth user id.
- Get quiz_id of all the assigned quizzes.
  - Create an empty array to store assigned quiz to this user.
  - Get all the data of this user from quiz_user pivot table.
  - Then, iterate along this data and save all the quiz_id in the array in each iteration.
- Get all the Quiz whereIn the id is this array containing quiz_id.
- Get a boolean variable to check if any exam is assigned to this user or not.
- Get the quiz_id of the quizzes this user has completed.

```php
    public function index()
    {
        if(auth()->user()->is_admin == 1)
        {
            return view('backend.layouts.dashboard');
        }

        $authUser = auth()->user()->id;
        $assignedQuizId = [];
        $user = DB::table('quiz_user')->where('user_id', $authUser)->get();
        foreach($user as $u){
            array_push($assignedQuizId, $u->quiz_id);
        }
        $quizzes = Quiz::whereIn('id', $assignedQuizId)->get();

        $isExamAssigned = DB::table('quiz_user')->where('user_id', $authUser)->exists();
        $wasQuizCompleted = Result::where('quiz_id', $authUser)->whereIn('quiz_id', (new Quiz)->hasQuizAttempted())->pluck('quiz_id')->toArray();

        return view('home', compact('quizzes', 'isExamAssigned', 'wasQuizCompleted'));
    }
```

### view/home.blade.php

Steps:
- If any exam is assigned to this user, then continue. Else, show "You are not assigned for any exam."
- Iterate along the $quizzes for each quiz.
- Display the info about each quiz.
- Display Start button if the quiz is not completed; else display Completed.
- Set the href of Start button to: `href="/quiz/{{$quiz->id}}"`
 
## Display each question

### Create route

```php
Route::get('quiz/{quizId}', [ExamController::class, 'getQuizQuestions'])->name('quiz')->middleware('auth');
```

### ExamController@getQuizQuestions

Steps:
- Get the logged-in user id.
- Get the quiz.
- Get the time allocated for the quiz.
- Get the questions of the quiz along with the answers.
- Get a variable for checking if the user has played the quiz successfully.
- Return to `view/quiz.blade.php` along with these variables.

```php
    public function getQuizQuestions($quiz_id)
    {
        $authUser = auth()->user()->id;
        $quiz = Quiz::find($quiz_id);
        $time = Quiz::where('id', $quiz_id)->value('minutes');
        $quizQuestions = $quiz->questions()->with('answers')->get();
        $authUserHasPlayedQuiz = Result::where('quiz_id', $quiz_id)->where('user_id', $authUser)->get();

        return view('quiz', compact('quiz', 'time', 'quizQuestions', 'authUserHasPlayedQuiz'));
    }
```

### Some vue.js

- Go to `resources/js/components/`.
- Create a duplicate of `ExampleComponent.vue` and name it as `QuizComponent.vue`.
- Then, go to `app.js` in the same directory.
- Import the QuizComponent and add as `app.component()` in this way:
```php
import QuizComponent from "@/components/QuizComponent.vue";
app.component('quiz-component', QuizComponent);
```
- Stop the vite server: `npm run dev`.
- Run this command: `npm run build --watch`.
- After success, restart the vite server: `npm run dev`.
- Now use the `<quiz-component>` in `view/quiz.blade.php>` file.


### view/quiz.blade.php

Create this file.

Code it as:

```bladehtml
@extends('layouts.app')

@section('content')
    <quiz-component
        :times = "{{$quiz->minutes}}"
        :quizId = "{{$quiz->id}}"
        :quiz-questions = "{{$quizQuestions}}"
        :has-quiz-played = "{{$authUserHasPlayedQuiz}}"
    >
        
    </quiz-component>
@endsection
```

Next step is to code the [`resources/js/components/QuizComponent.vue`](resources/js/components/QuizComponent.vue).
It is continued in the next section.


## Get one question on screen using vue js

```vue
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Online Examination
                        <span class="float-end">{{ questionIndex }}/{{ questions.length }}</span>
                    </div>

                    <div class="card-body">

                        <div v-for="(question, index) in questions">
                            <div v-show="index===questionIndex">

                                {{ question.question }}
                                <ol>
                                    <li v-for="choice in question.answers">
                                        <label>
                                            <input type="radio" name="">
                                            {{ choice.answer }}
                                        </label>

                                    </li>
                                </ol>

                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button class="btn btn-primary float-start" @click="prev()">Prev</button>
                        <button class="btn btn-primary float-end" @click="next()">Next</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['quizid', 'quizQuestions', 'hasQuizPlayed', 'times'],
    data() {
        return {
            questions: this.quizQuestions,
            questionIndex: 0,

        }
    },
    mounted() {
        console.log('Component mounted.')
    },

    methods: {
        next() {
            this.questionIndex++;
        },
        prev() {
            this.questionIndex--;
        }

    }
}
</script>

```

It is yet not completed. Continued in the next section.

## Get user responses using vue js

```bladehtml
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Online Examination
                        <span class="float-end">{{ questionIndex + 1 }}/{{ questions.length }}</span>
                    </div>

                    <div class="card-body">

                        <div v-for="(question, index) in questions">
                            <div v-show="index===questionIndex">

                                {{ question.question }}
                                <ol>
                                    <li v-for="choice in question.answers">
                                        <label>
                                            <input type="radio"
                                                :value="choice.is_correct==true?true:choice.answer"
                                                :name="index"
                                                v-model="userResponses[index]"
                                                   @click="choices(question.id, choice.id)"
                                            >
                                            {{ choice.answer }}
                                        </label>

                                    </li>
                                </ol>

                            </div>
                        </div>

                    </div>

                    <div class="card-footer" v-show="questionIndex < questions.length">
                        <div v-show="questionIndex>0">
                            <button class="btn btn-primary float-start" @click="prev()">Prev</button>
                        </div>
                        <div v-show="questionIndex !== questions.length-1">
                            <button class="btn btn-primary float-end" @click="next()">Next</button>
                        </div>
                        <div v-show="questionIndex === questions.length-1">
                            <button class="btn btn-danger float-end" @click="next()">Submit</button>
                        </div>
                    </div>

                    <div v-show="questionIndex === questions.length">
                        <p style="text-align: center">
                            You Got: {{score()}}/{{questions.length}}
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['quizid', 'quizQuestions', 'hasQuizPlayed', 'times'],
    data() {
        return {
            questions: this.quizQuestions,
            questionIndex: 0,
            userResponses: Array(this.quizQuestions.length).fill(false),
            currentQuestion: 0,
            currentAnswer: 0,
        }
    },
    mounted() {
        console.log('Component mounted.')
    },

    methods: {
        next() {
            this.questionIndex++;
        },
        prev() {
            this.questionIndex--;
        },
        choices(question, answer){
            this.currentQuestion = question
            this.currentAnswer = answer
        },
        score(){
            return this.userResponses.filter((val)=>{
                return val===true;
            }).length
        }

    }
}
</script>
```

## Store User Responses in the database

### Create route

```php
Route::post('quiz/create', [ExamController::class, 'postQuiz'])->middleware('auth');
```

### ExamController@postQuiz

Steps:
- Get the `questionId`, `answerId` and `quizId` from `$request`.
- Get the auth `userId`.
- Use `updateOrCreate` method so that if the answer is already there it will just update it;
and if the answer is not there, it will create it.

```php
    public function postQuiz(Request $request)
    {
        $questionId = $request['questionId'];
        $answerId = $request['answerId'];
        $quizId = $request['quizId'];

        $authUser = auth()->user()->id;

        return $userQuestionAnswer = Result::updateOrCreate(
            ['user_id' => $authUser, 'quiz_id' => $quizId, 'question_id' => $questionId],
            ['answer_id' => $answerId]
        );
    }
```

## Handling situation

- If the user is not assigned for an exam, redirect to homepage with an error message.
- If the user has already played an exam, redirect to homepage with an error message.

Just modify the `ExamController@getQuizQuestions()`

```php
    public function getQuizQuestions($quiz_id)
    {
        $authUser = auth()->user()->id;

        //added this code now
        
        // check if user has been assigned for this quiz
        $assignedQuizzes = DB::table('quiz_user')->where('user_id', $authUser)->pluck('quiz_id')->toArray();
        if(!in_array($quiz_id, $assignedQuizzes)){
            return redirect()->to('/home')->with('error', 'You do not have access to this quiz!');
        }

        // has user played particular quis
        $wasCompleted = Result::where('user_id', $authUser)->whereIn('quiz_id', (new Quiz)->hasQuizAttempted())->pluck('quiz_id')->toArray();
        if(in_array($quiz_id, $wasCompleted)){
            return redirect()->to('/home')->with('error', 'Quiz already played!');
        }
        /////////////////////

        $quiz = Quiz::find($quiz_id);
        $time = Quiz::where('id', $quiz_id)->value('minutes');
        $quizQuestions = $quiz->questions()->with('answers')->get();
        $authUserHasPlayedQuiz = Result::where('quiz_id', $quiz_id)->where('user_id', $authUser)->get();

        return view('quiz', compact('quiz', 'time', 'quizQuestions', 'authUserHasPlayedQuiz'));
    }
```


## Display User Results

### Create a route

```php
Route::get('result/user/{userId}/quiz/{quizId}', [ExamController::class, 'viewResult'])->middleware('auth');
```

### ExamController@viewResult

```php
    public function viewResult($userId, $quizId)
    {
        $results = Result::where('user_id', $userId)->where('quiz_id', $quizId)->get();
        return view('result-detail', compact('results'));
    }
```

### view/result-detail.blade.php
[resuources/views/result-detail.blade.php](resources/views/result-detail.blade.php)

```bladehtml
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

```


## Implement Timer

### Get moment package

#### Install npm package
`npm install moment`

#### Import it in `resources/js/app.js`
```js
// var moment = require('moment'); // require   // for older version
import moment from 'moment';                    // for newer version
```

#### Update QuizComponent.vue

```vue
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Online Examination
                        <span class="float-end">{{ questionIndex + 1 }}/{{ questions.length }}</span>
                    </div>

                    <div class="card-body">
                        <span class="float-end" style="color: red;">{{ formattedTime }}</span>
                        <div v-for="(question, index) in questions">
                            <div v-show="index === questionIndex">
                                {{ question.question }}
                                <ol>
                                    <li v-for="choice in question.answers">
                                        <label>
                                            <input type="radio"
                                                   :value="choice.is_correct === true ? true : choice.answer"
                                                   :name="index"
                                                   v-model="userResponses[index]"
                                                   @click="choices(question.id, choice.id)"
                                            >
                                            {{ choice.answer }}
                                        </label>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer" v-show="questionIndex < questions.length">
                        <div v-show="questionIndex > 0">
                            <button class="btn btn-primary float-start" @click="prev()">Prev</button>
                        </div>
                        <div v-show="questionIndex !== questions.length - 1">
                            <button class="btn btn-primary float-end" @click="next(); postUserChoice()">
                                Next
                            </button>
                        </div>
                        <div v-show="questionIndex === questions.length - 1">
                            <button class="btn btn-danger float-end" @click="next(); postUserChoice()">Submit</button>
                        </div>
                    </div>

                    <div v-show="questionIndex === questions.length">
                        <p style="text-align: center">
                            You Got: {{ score() }}/{{ questions.length }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";

export default {
    props: ['quizid', 'quizQuestions', 'hasQuizPlayed', 'times'],
    data() {
        return {
            questions: this.quizQuestions,
            questionIndex: 0,
            userResponses: Array(this.quizQuestions.length).fill(false),
            currentQuestion: 0,
            currentAnswer: 0,
            clock: moment.duration(this.times, 'minutes'),
        };
    },
    mounted() {
        this.startTimer();
    },
    computed: {
        formattedTime() {
            return moment.utc(this.clock.asMilliseconds()).format("mm:ss");
        }
    },
    methods: {
        startTimer() {
            this.interval = setInterval(() => {
                if (this.clock.asSeconds() > 0) {
                    this.clock = moment.duration(this.clock.asSeconds() - 10, 'seconds');
                } else {
                    clearInterval(this.interval);
                    alert('Timeout!');
                    window.location.reload();
                }
            }, 1000);
        },
        next() {
            this.questionIndex++;
        },
        prev() {
            this.questionIndex--;
        },
        choices(question, answer) {
            this.currentQuestion = question;
            this.currentAnswer = answer;
        },
        score() {
            return this.userResponses.filter(val => val === true).length;
        },
        postUserChoice() {
            axios.post('/quiz/create', {
                answerId: this.currentAnswer,
                questionId: this.currentQuestion,
                quizId: this.quizid,
            }).then(response => {
                console.log(response);
            }).catch(error => {
                alert(error);
            });
        }
    }
};
</script>

```


## User Profile

- Modify the `home.blade.php` to to display the user details in the right column and the
quiz assigned and completed in the left column in a ratio of: left:right :: 8:3.

[//]: # (Frontend Completed)
<hr>

# Result




