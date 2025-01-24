<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'name',
        'description',
        'minutes',
    ];

    public function questions(){
        return $this->hasMany(Question::class);
    }

    public function storeQuiz($data){
        return Quiz::create($data);
    }

    public function allQuiz(){
        return Quiz::get();
    }

    public function getQuizById($id){
        return Quiz::find($id);
    }

    public function updateQuiz($id,$data){
        return Quiz::find($id)->update($data);
    }

    public function deleteQuiz($id){
        return Quiz::destroy($id);
    }

}
