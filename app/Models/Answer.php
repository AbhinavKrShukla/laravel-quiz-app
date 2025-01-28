<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'question_id',
        'answer',
        'is_correct',
    ];

    public function question(){
        return $this->belongsTo(Question::class);
    }

    public function storeAnswer($data, $question){
        foreach($data['options'] as $key=>$answer){
            $is_correct = false;
            if($key==$data['correct_answer']){
                $is_correct = true;
            }
            Answer::create([
                'question_id' => $question->id,
                'answer' => $answer,
                'is_correct' => $is_correct,
            ]);
        }
    }

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
}
