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





