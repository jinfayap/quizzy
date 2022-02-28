<div>
    <h3 class="font-semibold text-lg text-gray-400">Options</h3>

    @foreach ($answer->question->options as $option)
        <p>
            <span class="mr-2">{{ $loop->iteration }}.
                {{ $option['option'] }}</span>


            @if (in_array($option['option'], $answer->question->answer))
                <span class="mr-2 text-green-400">[Correct]</span>
            @else
                <span class="mr-2 text-red-400">[Incorrect]</span>
            @endif

            @if ($option['option'] == $answer->user_answer)
                <span class="text-blue-400">(selected)</span>
            @endif
        </p>
    @endforeach
</div>



