<x-app-layout>

    <h2 class="text-2xl font-bold text-center mb-2">Public Tests</h2>
    @if ($publicTests->count() > 0)
        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No.</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quiz Title</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Created</th>

                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Attempt</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($publicTests as $test)
                                    <tr>

                                        <td class="px-6 py-4">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $test->title }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $test->created_at->diffForHumans() }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">

                                            <a href="{{ $test->getTestUrl() }}"
                                                class="text-indigo-600 hover:text-indigo-900">Attempt</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @else
        <p class="text-center font-semibold text-xl">No Public test available yet...</p>
    @endif

    <h2 class="text-2xl font-bold text-center mb-2 mt-2">Assigned Tests</h2>
    @if ($tests->count() > 0)
        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No.</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quiz Title</th>

                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Start Date</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        End date</th>

                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Attempt</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($tests as $test)
                                    <tr>

                                        <td class="px-6 py-4">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $test->quiz->title }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ isset($test->start_date) ? $test->start_date->format('d-m-Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ isset($test->end_date) ? $test->end_date->format('d-m-Y') : '-' }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if ($test->validTimeFrame())
                                                <a href="{{ route('test.show', $test->quiz) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">Attempt</a>
                                            @else
                                                <span class="text-red-500">Closed</span>
                                            @endif



                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @else
        <p class="text-center font-semibold text-xl">You have no assigned tests...</p>
    @endif

</x-app-layout>
