<?php

namespace App\Services;

use App\DTO\SubmitFormDTO;
use App\DTO\UploadQuizVideoDTO;
use App\DTO\AttemptQuizDTO;
use App\DTO\GetQuizQuestionsDTO;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\StudentSubmission;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudentSubmissionMail;
use App\Mail\QuizSubmittedMail;

class StudentService
{
    
    // Fetch quiz questions for student (only if the status is active)
    public function getQuizQuestions(GetQuizQuestionsDTO $dto, $forAdmin = false)
    {
        $quiz = Quiz::with('questions')->findOrFail($dto->quizId);

        // Admin can always view the quiz
        if ($forAdmin) {
            return $quiz->questions->map(function ($question) {
                return [
                    'id' => $question->id,
                    'question_text' => $question->question_text,
                    'options' => json_decode($question->options),  // Decode JSON options
                ];
            });
        }

        // Student logic
        if ($quiz->status === 'pending') {
            // If the quiz is still pending, student cannot view the quiz questions
            return null;
        }

        return $quiz->questions->map(function ($question) {
            return [
                'id' => $question->id,
                'question_text' => $question->question_text,
                'options' => json_decode($question->options),
            ];
        });
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

public function finishQuiz($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);

        // Only allow marking as done if it's active
        if ($quiz->status === 'active') {
            $quiz->update(['status' => 'done']);
        }

        return $quiz;
    }
    public function attemptQuiz($user, $quizId, AttemptQuizDTO $dto)
    {
        $quiz = Quiz::with('questions')->findOrFail($quizId);
    
        $existingAttempt = QuizAttempt::where('quiz_id', $quizId)
            ->where('user_id', $user->id)
            ->first();
    
        if ($existingAttempt) {
            return null;
        }
    
        $score = 0;
        $results = [];
        foreach ($dto->answers as $answer) {
            $question = $quiz->questions->where('id', $answer['question_id'])->first();
            $isCorrect = $question && $question->correct_answer === $answer['answer'];
            if ($isCorrect) {
                $score++;
            }
    
            $results[] = [
                'question_id' => $answer['question_id'],
                'submitted_answer' => $answer['answer'],
                'correct_answer' => $question->correct_answer, // Include correct answer
                'is_correct' => $isCorrect,
            ];
        }
    
        // Ensure videoPath is defined from DTO
        $videoPath = $dto->videoPath ?? null;  // This ensures $videoPath is correctly assigned from DTO
    
        // Save the quiz attempt
        QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            'score' => $score,
            'results' => json_encode($results),
            'video_path' => $videoPath,  // Store the video path
            'status' => 'attempted'
        ]);


        // After saving, send the confirmation email to the student via queue
        Mail::to($user->email)->queue(new QuizSubmittedMail($quiz->title, $user->name, $score));
    
    
        return [
            'score' => $score,
            'results' => $results
        ];
    }
    
    
public function getQuizResults($user, $quizId)
{
    $quizAttempt = QuizAttempt::where('quiz_id', $quizId)
        ->where('user_id', $user->id)
        ->first();

    if (!$quizAttempt) {
        return null;
    }

    $results = json_decode($quizAttempt->results, true);

    // Fetch the correct answers and add them to the results (if not already included)
    $questions = Quiz::with('questions')->find($quizId)->questions;

    foreach ($results as &$result) {
        $question = $questions->where('id', $result['question_id'])->first();
        if ($question && !isset($result['correct_answer'])) {
            $result['correct_answer'] = $question->correct_answer;
        }
    }

    return [
        'score' => $quizAttempt->score,
        'results' => $results,
        'video_path' => $quizAttempt->video_path
    ];
}

public function uploadQuizVideo($user, $quizId, UploadQuizVideoDTO $dto)
{
    // Find or create a quiz attempt for this user and quiz
    $quizAttempt = QuizAttempt::firstOrNew(
        ['quiz_id' => $quizId, 'user_id' => $user->id]
    );
    
    // Set the video path
    $quizAttempt->video_path = $dto->videoPath;
    
    // Update the status if needed (e.g., 'attempted')
    $quizAttempt->status = 'attempted';
    
    // Save the quiz attempt with the video path
    $quizAttempt->save();

    return $quizAttempt;
}


}
