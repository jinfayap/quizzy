<x-app-layout>
    <div class="max-w-5xl mx-auto px-4">

        <div class="border px-4 py-2 rounded-md space-y-4">
            <div>
                <h3 class="font-semibold text-2xl text-gray-500">Title:</h3>
                <p class="text-base">{{ $test->quiz->title }}</p>
            </div>

            <div>
                <h3 class="font-semibold text-2xl text-gray-500">Description:</h3>
                <p class="text-base leading-6">{{ $test->quiz->description }}</p>
            </div>

            <div>
                <h3 class="font-semibold text-2xl text-gray-500">Duration:</h3>
                <p class="text-base">{{ $test->quiz->duration }} Mins</p>
            </div>

            <div>
                <h3 class="font-semibold text-2xl text-gray-500">Score:</h3>
                <p class="text-base">{{ $test->score() }} / {{ $test->testAnswers->count() }} marks</p>
            </div>
        </div>

        <div class="shadow-sm rounded-md">
            <h3 class="text-center mt-4 font-semibold text-2xl text-gray-500">Questions</h3>

            @foreach ($test->testAnswers as $answer)
                <div class="grid grid-cols-2 border p-2">
                    <div class="border-r px-3 flex flex-col">
                        <h3 class="font-semibold text-lg text-gray-400 flex items-center ">Question
                            <span class="ml-2">
                                @if ($answer->correct)
                                    <svg class="h-4 w-4 text-green-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <svg class="h-4 w-4 text-red-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                @endif
                            </span>

                        </h3>

                        <div>{{ $answer->question->question_text }}</div>

                        @can('mark question')
                            <div class="mt-auto">
                                @if (!$answer->correct)
                                    <form method="POST" action="{{ route('answer.update', $answer) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="px-2 py-1 bg-green-400 hover:bg-green-500 text-white rounded">
                                            Mark as correct
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('answer.destroy', $answer) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-2 py-1 bg-red-400 hover:bg-red-500 text-white rounded">
                                            Mark as wrong
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endcan

                    </div>

                    <div class="border-l px-3">
                        <div class="space-y-2">
                            @includeIf('question.'.$answer->question->question_type)

                            @if (isset($answer->question->answer_explanation) && !empty($answer->question->answer_explanation) && !is_null($answer->question->answer_explanation))
                                <div>
                                    <h3 class="font-semibold text-lg text-gray-400">Answer Explanation</h3>
                                    {{ $answer->question->answer_explanation }}
                                </div>
                            @endif

                            @if (isset($answer->question->more_info_link) && !empty($answer->question->more_info_link) && !is_null($answer->question->more_info_link))
                                <div>
                                    <h3 class="font-semibold text-lg text-gray-400">More Info Link</h3>
                                    {{ $answer->question->more_info_link }}
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
