<?php

namespace App\Http\Controllers;

use App\Services\QuizService;  // Ensure correct service is imported
use App\Helpers\Helper;
use App\Http\Requests\GetQuizQuestionsRequest;
use App\DTO\GetQuizQuestionsDTO;
use App\Models\Quiz;

class QuizController extends Controller
{
    protected $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    // Admin route to fetch quiz details
    public function getQuizDetailsForAdmin(GetQuizQuestionsRequest $request, $quizId)
    {
        $dto = GetQuizQuestionsDTO::fromRequest(['quizId' => $quizId]);
        $questions = $this->quizService->getQuizQuestions($dto, true);
        return Helper::success($questions, 'Quiz details fetched successfully.');
    }

    public function getQuizDetailsForStudent(GetQuizQuestionsRequest $request, $quizId)
{
    $dto = GetQuizQuestionsDTO::fromRequest(['quizId' => $quizId]);

    // Fetch quiz questions via service (forAdmin = false)
    $questions = $this->quizService->getQuizQuestions($dto, false);

    if (!$questions) {
        return Helper::error('Quiz is not available or has not been started yet', 403);  // Forbidden if quiz is not accessible
    }

    return Helper::success($questions, 'Quiz questions fetched successfully');
}
public function getQuizzes()
{
    // Fetch all quizzes with their IDs, titles, and other relevant data
    $quizzes = Quiz::select('id', 'title', 'description')->get();

    return response()->json(['data' => $quizzes], 200);
}

}
