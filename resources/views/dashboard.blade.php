<x-app-layout>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        @can('view quiz')
            <div class="border p-4 rounded-md">
                <h3 class="font-bold text-center text-2xl">Total Quiz:</h3>

                <p class="text-center font-bold text-9xl">{{ $totalQuiz }} </p>
            </div>

            <div class="border p-4 rounded-md">
                <h3 class="font-bold text-center text-2xl">Latest Quiz Attempted</h3>

                @foreach ($latestQuizAttempted as $quiz)
                    <a href="{{ $quiz->getResultUrl() }}" class="hover:bg-gray-100 block hover:shadow">
                        <div class="border-b mb-2 p-2 grid grid-cols-4">
                            <span class="text-base font-semibold">{{ $quiz->quiz->title }}</span>
                            <span>{{ $quiz->tester->name ?? 'Anonymous ' }}</span>
                            <span class="text-center font-semibold">{{ $quiz->result }} /
                                {{ $quiz->testAnswers->count() }}</span>
                            <span class="text-xs text-right">{{ $quiz->created_at->diffForHumans() }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endcan


        <div class="border p-4 rounded-md">
            <h3 class="font-bold text-center text-2xl">Upcoming / Incomplete Test</h3>

            @foreach ($upcomingQuiz as $quiz)
                <div class="border-b mb-2 text-center p-2 flex justify-between hover:bg-gray-100 hover:shadow">
                    <span>{{ $quiz->quiz->title }}</span>
                </div>
            @endforeach
        </div>

        <div class="border p-4">
            <h3 class="font-bold text-center text-2xl">Quiz Attempted</h3>

            @foreach ($userQuizAttempted as $quiz)
                <a href="{{ $quiz->getResultUrl() }}" class="hover:bg-gray-100 block hover:shadow">
                    <div class="border-b mb-2 text-center p-2 flex justify-between">
                        <span>{{ $quiz->quiz->title }}</span>
                        <span>{{ $quiz->created_at->diffForHumans() }}</span>
                    </div>
                </a>
            @endforeach
            <div>

            </div>
        </div>
    </div>
</x-app-layout>
