<x-app-layout>
    <div class="border border-gray-200 p-4 rounded-md text-center font-semibold mb-2">
        Here are some public test that you can try now before signing up an account.
    </div>

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


</x-app-layout>
