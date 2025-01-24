<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quizzes = (new Quiz)->allQuiz();
        return view('backend.quiz.index', compact('quizzes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.quiz.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $quiz = (new Quiz)->getQuizById($id);
        return view('backend.quiz.edit', compact('quiz'));
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        (new Quiz)->deleteQuiz($id);
        return redirect()->back()->with('message', 'Quiz deleted successfully');
    }
}
