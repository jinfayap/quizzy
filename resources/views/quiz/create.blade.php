<x-app-layout>
    <div class="mx-auto max-w-3xl lg:max-w-2xl">
        <h1 class="text-center font-bold text-2xl">Create New Quiz</h1>

        <div class="bg-gray-100 shadow-md rounded-md mt-5 p-10">
            <form method="POST" action="{{ route('quiz.store') }}">
                @csrf

                <div class="mt-2">
                    <label for="title" class="block text-gray-500 text-sm mb-1">Title :</label>
                    <input type="text" name="title" class="w-full rounded-md" value="{{ old('title', '') }}" required>

                    @error('title')
                        <p class="text-xs text-red-500 mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mt-2">
                    <label for="description" class="block text-gray-500 text-sm text-uppercase mb-1">Description
                        :</label>

                    <textarea name="description" rows="3" class="w-full rounded-md placeholder:text-sm min-h-[100px]"
                        placeholder="optional">{{ old('description', '') }}</textarea>

                    @error('description')
                        <p class="text-xs text-red-500 mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mt-2">
                    <label for="description" class="block text-gray-500 text-sm text-uppercase mb-1">Duration (Minutes)
                        :
                    </label>
                    <input type="text" name="duration" class="w-full rounded-md placeholder:text-sm"
                        placeholder="optional" value="{{ old('duration', '') }}">

                    @error('duration')
                        <p class="text-xs text-red-500 mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mt-4 flex justify-end">
                    <button
                        class="bg-blue-400 hover:bg-blue-500 hover:shadow-md px-4 py-2 text-white rounded-md">Create</button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
