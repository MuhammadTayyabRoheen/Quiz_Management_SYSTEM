<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\QuestionService;
use App\Helpers\Helper;
use App\Http\Requests\AddQuestionRequest;
use App\DTO\AddQuestionDTO;

class QuestionController extends Controller
{
    protected $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function store(AddQuestionRequest $request, $quizId)
    {
        $dto = AddQuestionDTO::fromRequest($request->validated());
        $question = $this->questionService->addQuestion($quizId, $dto);
        return Helper::success($question, 'Question added successfully');
    }
}
