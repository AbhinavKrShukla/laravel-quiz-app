<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\TypeResolver;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = (new Question)->getQuestions();
        return view('backend.question.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.question.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validateForm($request);
        $question = (new Question)->storeQuestion($data);
        (new Answer)->storeAnswer($data, $question);
        return redirect()->back()->with('message', 'Question created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $question = (new Question)->getQuestion($id);
        $answers = (new Answer)->getAnswers($id);
        return view('backend.question.show', compact('question', 'answers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $question = (new Question)->getQuestion($id);
        $answers = (new Answer)->getAnswers($id);
        return view('backend.question.edit', compact('question', 'answers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $this->validateForm($request);
        $question = (new Question)->updateQuestion($id, $data);
        (new Answer)->updateAnswer($data, $question);
        return redirect()->route('question.show', $id)->with('message', 'Question updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        (new Question)->deleteQuestion($id);
        (new Answer)->deleteAnswer($id);
        return redirect()->route('question.index')->with('message', 'Question deleted successfully');
    }

    /**
     * Validates the user input in the form
     */
    public function validateForm($data)
    {
        $validatedData = $this->validate($data, [
            'quiz' => 'required',
            'question' => 'required|min:3',
            'options' => 'bail|required|array',
            'options.*' => 'bail|required|string|distinct',
            'correct_answer' => 'required',
        ]);
        return $validatedData;
    }

}
