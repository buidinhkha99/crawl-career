<?php

namespace App\Imports;

use App\Models\Question;
use App\Models\QuestionType;
use App\Models\Topic;
use App\Rules\AnswerRule;
use App\Rules\CountAnswerRule;
use App\Rules\DuplicateQuestionRule;
use App\Rules\FormatAnswerRule;
use App\Rules\OnlyOneAnswerRule;
use App\Rules\RequiredAnswerRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Notifications\NovaNotification;
use Maatwebsite\Excel\Concerns\ToCollection;
use PHPUnit\Exception;

class QuestionsImport implements ToCollection
{
    /**
     * @param  array  $row
     *
     * @throws \Exception
     */
    public function collection(Collection $rows): void
    {
        $rows = $rows->filter(fn ($value) => $value->filter()->isNotEmpty());
        $question_types = QuestionType::all();
        $question_duplicates = $rows->duplicates(2)->values();

        foreach ($rows as $index => $row) {
            if ($index === 0) continue;

            $topic = trim($row[0]);
            $question_type = trim($row[1]);
            $question = trim($row[2]);
            $correct_answers = collect(explode(',', trim($row[3])))->map(fn ($correct_answer) => trim($correct_answer));
            $answers = $row->slice(4)->filter(fn($answer) => trim($answer) != "")->values();

            $validator = Validator::make([
                __('Topic') => $topic,
                __('Question Type') => $question_type,
                __('Question') => $question,
                __('Answers') => $correct_answers,
            ], [
                __('Topic') => 'nullable|max:100',
                __('Question Type') => 'required|in:'.implode(',', $question_types->pluck('name')->toArray()),
                __('Question') => ['required', new DuplicateQuestionRule($question_duplicates, $rows->slice(1)->pluck(2))],
                __('Answers') => [
                    new RequiredAnswerRule(),
                    new OnlyOneAnswerRule($question_types->pluck('type', 'name'), $question_type),
                    new FormatAnswerRule($answers),
                    new CountAnswerRule($answers),
                    new AnswerRule($answers),
                ], [
                    __('Topic') => __('Topic'),
                    __('Question Type') => __('Question Type'),
                    __('Question') => __('Question'),
                    __('Answers') => __('Answers'),
                ],
            ]);

            if ($validator->fails()) {
                collect($validator->errors())->each(function ($error, $field) use ($index) {
                    Auth::user()->notify(
                        NovaNotification::make()
                            ->message(__('Row :key add question error. Field: :field, error: :error', [
                                'key' => $index + 1,
                                'field' => $field,
                                'error' => collect($error)->first(),
                            ]))
                            ->type('error')
                    );
                });

                continue;
            }

            try {
                $question_name = htmlentities($question, ENT_QUOTES, 'UTF-8');
                $question_type_id = $question_types->firstWhere('name', $question_type)?->getAttribute('id');

                DB::transaction(function () use ($question_name, $correct_answers, $answers, $question_type_id, $topic) {
                    $question = Question::firstOrNew([
                        'name' => '<p>'.$question_name.'</p>',
                    ]);

                    $question->setAttribute('question_type_id', $question_type_id);
                    $question->topics()->detach();
                    $question->saveQuietly();

                    if ($topic) {
                        $topic = Topic::firstOrCreate([
                            'name' => $topic,
                        ]);
                        $topic->questions()->attach($question->getAttribute('id'));
                    }

                    $this->createAnswers($question, $answers, $correct_answers);
                });
            } catch (Exception $e) {
                NovaNotification::make()
                    ->message(__('Row :key add question error. Field: :field, error: :error', [
                        'key' => $index + 1,
                        'error' => $e->getMessage(),
                    ]))
                    ->type('error');

                continue;
            }
        }
    }

    protected function createAnswers(Model $question, Collection $answers, Collection $correct_answers)
    {
        $question->options()->delete();
        $answers->each(function ($answer, $index) use ($question, $correct_answers) {
            $answer = htmlentities(trim($answer), ENT_QUOTES, 'UTF-8');
            $question->options()->create([
                'name' => '<p>'.$answer.'</p>',
                'is_correct' => $correct_answers->contains($index + 1),
            ]);
        });
    }
}
