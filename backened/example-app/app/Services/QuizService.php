<?php

namespace App\Services;

use App\DTO\GetQuizQuestionsDTO;
use App\Models\Quiz;
use App\Models\QuizAttempt;

class QuizService
{ // Fetch quiz questions for student (only if the status is active)
    // Fetch quiz questions for student (only if the status is active)
public function getQuizQuestions(GetQuizQuestionsDTO $dto, $forAdmin = false)
{
    $quiz = Quiz::with('questions')->findOrFail($dto->quizId);

    // Admin can always view the quiz
    if ($forAdmin) {
        return [
            'title' => $quiz->title,
            'description' => $quiz->description,
            'questions' => $quiz->questions->map(function ($question) {
                return [
                    'id' => $question->id,
                    'question_text' => $question->question_text,
                    'options' => json_decode($question->options),  // Decode JSON options
                ];
            })
        ];
    }

    // Student logic
    if ($quiz->status === 'pending') {
        // If the quiz is still pending, student cannot view the quiz questions
        return null;
    }

    return [
        'title' => $quiz->title,
        'description' => $quiz->description,
        'questions' => $quiz->questions->map(function ($question) {
            return [
                'id' => $question->id,
                'question_text' => $question->question_text,
                'options' => json_decode($question->options),
            ];
        })
    ];
}

 // Mark the quiz as active when the student presses "Start Quiz"
 public function startQuiz($quizId)
 {
     $quiz = Quiz::findOrFail($quizId);

     // Ensure the quiz is in pending state before marking it active
     if ($quiz->status === 'pending') {
         $quiz->update(['status' => 'active']);
     }

     return $quiz;
 }

  // Mark the quiz as done when the student finishes it
  public function finishQuiz($quizId)
  {
      $quiz = Quiz::findOrFail($quizId);

      // Only allow marking as done if it's active
      if ($quiz->status === 'active') {
          $quiz->update(['status' => 'done']);
      }

      return $quiz;
  }

  public function getQuizzesForStudent($studentId)
  {
      // Fetch assigned quizzes (where the user has not attempted)
      $assignedQuizzes = Quiz::whereDoesntHave('quizAttempts', function($query) use ($studentId) {
          $query->where('user_id', $studentId);  // Use user_id instead of student_id
      })->get();
  
      // Fetch attempted quizzes (where the user has attempted the quiz)
      $attemptedQuizzes = QuizAttempt::where('user_id', $studentId)  // Use user_id instead of student_id
                                      ->with('quiz') // Fetch related quiz data
                                      ->get();
  
      return [
          'assignedQuizzes' => $assignedQuizzes,
          'attemptedQuizzes' => $attemptedQuizzes,
      ];
  }
  
}
