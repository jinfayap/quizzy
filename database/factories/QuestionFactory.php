<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'quiz_id' => Quiz::factory(),
            'user_id' => User::factory(),
            'question_text' => $this->faker->sentences(3, true),
            'answer' => $this->faker->sentences(3, true),
            'options' => null,
            'question_type' => 'text',
            'answer_explanation' => $this->faker->sentences(3, true),
            'more_info_link' => $this->faker->url(),
        ];
    }

    public function textarea()
    {
        return $this->state(function () {
            return [
                'question_type' => 'textarea',
                'question_text' => $this->faker->sentences(3, true),
                'answer' => $this->faker->sentences(3, true),
            ];
        });
    }

    public function select()
    {
        return $this->state(function () {
            $options = array(
                [ 'option' => $this->faker->word() ],
                [ 'option' => $this->faker->word() ],
                [ 'option' => $this->faker->word() ],
                [ 'option' => $this->faker->word() ],
            );

            return [
                'question_type' => 'select',
                'options' => $options,
                'answer' => json_encode([Arr::random($options)['option']])

            ];
        });
    }

    public function radio()
    {
        return $this->state(function () {
            $options = array(
                [ 'option' => $this->faker->word() ],
                [ 'option' => $this->faker->word() ],
                [ 'option' => $this->faker->word() ],
                [ 'option' => $this->faker->word() ],
            );

            return [
                'question_type' => 'radio',
                'options' => $options,
                'answer' => json_encode([Arr::random($options)['option']])

            ];
        });
    }

    public function checkbox()
    {
        return $this->state(function () {
            $options = array(
                [ 'option' => $this->faker->word() ],
                [ 'option' => $this->faker->word() ],
                [ 'option' => $this->faker->word() ],
                [ 'option' => $this->faker->word() ],
            );

            return [
                'question_type' => 'checkbox',
                'options' => $options,
                'answer' => json_encode(Arr::flatten(Arr::random($options, 3)))

            ];
        });
    }
}