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


# Planning of the WebApp

## Description of the App


## Structure

### Directory structure

- views
  - admin
    - ...
  - backend
    - ...
  - layouts
    - ...


## Migrations


## Models

- 

## Relationships between Models

- 

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

# Integrate Admin Template

We are going to use `edmin` bootstrap template for our admin panel.
The template is located in `public/edmin_template`.

## Create js and Css links 


### Create directory for admin

- Go to `resources/views` and create this directory structure:
  - views
    - admin
      - index.blade.php


### Create route

- Make this `admin.index` the default route.

```php
Route::get('/', function () {
    return view('admin.index');
});
```

### Copy from template/index

- From the template, copy the code from `index.html` file and 
paste it in `admin/index.blade.php`.

- To link css and js file, modify the links as:
  - `href="css/theme.css"` to `href="{{asset('edmin_template/css/theme.css')}}"`


## Create Different pages and Master file

### Create backend folder

- views
  - admin
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

### quizzes migration 

- `id`
- `name` - string
- `description` - text
- `minutes` - integer

### questions migration

- `id`
- `questions` - string
- `quiz_id` - integer

### answers migration

- `id`
- `question_id` - integer
- `answer` - string
- `is_correct` - boolean

### results migration

- `id`
- `user_id` - integer
- `quiz_id` - integer
- `question_id` - integer
- `answer_id` - integer

### users migration

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
- ... other fields continues

### quiz_user migration - pivot table

- `id`
- `quiz_id`
- `user_id`

### Refresh the migration

`php artisan migrate:refresh`


## Create fillables and Relationships
 
Fillables are the column names that can be used to manually store
the data into it. It is an array defined in the Model file.

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



