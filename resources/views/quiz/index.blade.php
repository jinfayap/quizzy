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

                    <div class=" rounded-md bg-gray-400 hover:bg-gray-500 text-white cursor-pointer">
                        <a href="{{ route('quiz.show', $quiz) }}" class="flex items-center p-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg><span class="ml-1 hidden lg:block text-xs">Settings</span>
                        </a>
                    </div>

                    <div class=" rounded-md bg-violet-400 hover:bg-violet-500 text-white cursor-pointer">
                        <a href="{{ route('quiz.edit', $quiz) }}" class="flex items-center p-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg><span class="ml-1 hidden lg:block text-xs">Edit</span>
                        </a>
                    </div>
                </div>

                {{-- Public/Private and Created Date --}}
                <div class="flex justify-between items-center ">
                    <span class="flex items-center text-gray-500">
                        @if ($quiz->public)
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span class="ml-2 hidden lg:block">Public</span>
                        @else
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                            <span class="ml-2 hidden lg:block">Private</span>
                        @endif
                    </span>


                    <span class="font-thin text-xs">{{ $quiz->created_at->diffForHumans() }}</span>
                </div>
            </div>
        @endforeach

    </div>
</x-app-layout>
