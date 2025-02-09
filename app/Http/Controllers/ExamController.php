<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Result;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function getQuizQuestions($quiz_id)
    {
        $authUser = auth()->user()->id;

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

        $quiz = Quiz::find($quiz_id);
        $time = Quiz::where('id', $quiz_id)->value('minutes');
        $quizQuestions = $quiz->questions()->with('answers')->get();
        $authUserHasPlayedQuiz = Result::where('quiz_id', $quiz_id)->where('user_id', $authUser)->get();

        return view('quiz', compact('quiz', 'time', 'quizQuestions', 'authUserHasPlayedQuiz'));
    }

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

    public function viewResult($userId, $quizId)
    {
        $results = Result::where('user_id', $userId)->where('quiz_id', $quizId)->get();
        return view('result-detail', compact('results'));
    }

    public function result(){
        $quizzes = Quiz::get();
        return view('backend.result.index', compact('quizzes'));
    }

    public function userQuizResult($userId, $quizId){
        $user = User::find($userId);
        $results = Result::where('user_id', $userId)->where('quiz_id', $quizId)->get();
        $totalQuestions = Question::where('quiz_id', $quizId)->count();
        $attemptQuestions = Result::where('quiz_id', $quizId)->where('user_id', $userId)->count();
        $quiz = Quiz::where('id', $quizId)->first();

        $ans = [];
        foreach ($results as $answer){
            array_push($ans, $answer->answer_id);
        }
        $userCorrectAnswer = Answer::whereIn('id', $ans)->where('is_correct', 1)->count();
        $userWrongAnswer = $totalQuestions - $userCorrectAnswer;
        $percentage = ($userCorrectAnswer/$totalQuestions)*100;

        return view('backend.result.result', compact(
            'results', 'totalQuestions', 'attemptQuestions', 'userCorrectAnswer',
            'userWrongAnswer', 'percentage', 'quiz', 'user'
        ));
    }
}
