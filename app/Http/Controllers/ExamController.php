<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Result;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function create()
    {
        return view('backend.exam.assign');
    }

    public function assignExam(Request $request)
    {
        $this->validate($request, [
            'quiz' => 'required',
            'user' => 'required',
        ]);

        $quiz = (new Quiz)->assignExam($request->all());
        return redirect()->back()->with('message', 'Exam assigned successfully');
    }

    public function userExam()
    {
        $quizzes = Quiz::get();
        return view('backend.exam.index', compact('quizzes'));
    }

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

}
