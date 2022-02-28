<div>
    <h3 class="font-semibold text-lg text-gray-400">Your Answer</h3>
    {{ $answer->user_answer }}
</div>

<div>
    <h3 class="font-semibold text-lg text-gray-400">Model Answer</h3>
    {{ $answer->question->answer }}
</div>
