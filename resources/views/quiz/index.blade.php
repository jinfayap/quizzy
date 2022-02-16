<x-app-layout>

    <div class="flex justify-end mb-4">
        <a href="{{ route('quiz.create') }}">
            <div class="p-2 rounded-md bg-blue-400 hover:bg-blue-500 text-white cursor-pointer flex items-center"><svg
                    class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg><span class="ml-1 hidden lg:block text-xs">New</span></div>
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">

        @foreach ($quizzes as $quiz)
            {{-- Single card --}}
            <div class="border rounded-md hover:shadow p-2 space-y-3 flex flex-col">

                <div class="relative flex-1 ml-4">
                    <div class="absolute bg-sky-300 w-1 h-12 left-0 -ml-4"></div>
                    <h3 class="font-semibold text-lg">
                        {{ $quiz->title }}
                    </h3>
                </div>

                {{-- FAB View and Edit --}}
                <div class="flex justify-end items-center space-x-2 ">
                    <span class="text-sm">View</span>
                    <a href="{{ route('quiz.edit', $quiz) }}">
                        <div
                            class="p-2 rounded-md bg-violet-400 hover:bg-violet-500 text-white cursor-pointer flex items-center">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg><span class="ml-1 hidden lg:block text-xs">Edit</span>
                        </div>
                    </a>
                </div>

                {{-- Public/Private and Created Date --}}
                <div class="flex justify-between items-center ">
                    <span class="flex items-center text-gray-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span class="ml-2 hidden lg:block">Public</span></span>
                    <span class="font-thin text-xs">1 day ago</span>
                </div>
            </div>
        @endforeach

    </div>
</x-app-layout>
