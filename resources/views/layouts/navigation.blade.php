<nav class="bg-gray-800" x-data="{menu : false}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <img class="h-8 w-8" src="https://tailwindui.com/img/logos/workflow-mark-indigo-500.svg"
                        alt="Workflow">
                </div>
                @auth
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                            <a href="{{ route('dashboard') }}"
                                class="px-3 py-2 rounded-md text-sm font-medium {{ Route::is('dashboard') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Dashboard</a>

                            @can('view quiz')
                                <a href="{{ route('quiz.index') }}"
                                    class="px-3 py-2 rounded-md text-sm font-medium {{ Route::is('quiz.index') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Quiz</a>
                            @endcan

                            <a href="{{ route('test.index') }}"
                                class="px-3 py-2 rounded-md text-sm font-medium {{ Route::is('test.index') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Test</a>

                            <a href="{{ route('result.index') }}"
                                class="px-3 py-2 rounded-md text-sm font-medium {{ Route::is('result.index') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Result</a>


                        </div>
                    </div>
                @endauth
            </div>

            @auth
                <div class="hidden md:block">
                    <div class="ml-4 flex items-center md:ml-6">
                        @can('view admin panel')
                            <a href="{{ route('admin.index') }}"
                                class="px-3 py-2 rounded-md text-sm font-medium {{ Route::is('admin.index') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Admin</a>
                        @endcan

                        <!-- Profile dropdown -->
                        <div class="ml-3 relative" x-data="{open : false}" x-on:click.away="open = false">
                            <div>
                                <button type="button" x-on:click="open=!open"
                                    class="max-w-xs bg-gray-800 rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
                                    id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="h-8 w-8 rounded-full" src="https://www.gravatar.com/avatar/?d=mp" alt="">
                                </button>
                            </div>

                            <div class="z-10 origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                                role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
                                x-show="open" x-transition:enter="transition ease-out duration-100"
                                x-transiiton:enter-start="transform opacity-0 scale-100"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95">

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <a class="block px-4 py-2 text-sm text-gray-700" href="route('logout')"
                                        onclick="event.preventDefault();
                                                                                                                                                                                    this.closest('form').submit();">
                                        {{ __('Sign out') }}
                                    </a>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="hidden md:block">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}"
                            class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Log
                            in</a>
                    @endif

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Register</a>
                    @endif

                </div>
            @endauth

            <div class="-mr-2 flex md:hidden">
                <!-- Mobile menu button -->
                <button type="button"
                    class="bg-gray-800 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
                    aria-controls="mobile-menu" aria-expanded="false" x-on:click="menu= !menu">
                    <span class="sr-only">Open main menu</span>

                    <svg class="h-6 w-6" x-bind:class="{hidden : menu}" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>

                    <svg class="hidden h-6 w-6" x-bind:class="{hidden : !menu}" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div class="md:hidden" id="mobile-menu" x-show="menu" x-cloak>
        @auth
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                <a href="{{ route('dashboard') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium {{ Route::is('dashboard') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Dashboard</a>

                @can('view quiz')
                    <a href="{{ route('quiz.index') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium {{ Route::is('quiz.index') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Quiz</a>
                @endcan


                <a href="{{ route('test.index') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium {{ Route::is('test.index') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Test</a>

                <a href="{{ route('result.index') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium {{ Route::is('result.index') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Result</a>



                @can('view admin panel')
                    <a href="{{ route('admin.index') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium {{ Route::is('admin.index') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Admin</a>
                @endcan
            </div>

            <div class="pt-4 pb-3 border-t border-gray-700">
                <div class="flex items-center px-5">
                    <div class="flex-shrink-0">
                        <img class="h-10 w-10 rounded-full" src="https://www.gravatar.com/avatar/?d=mp" alt="">
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium leading-none text-white">{{ auth()->user()->name }} </div>
                        <div class="text-sm font-medium leading-none text-gray-400">{{ auth()->user()->email }}</div>
                    </div>

                </div>
                <div class="mt-3 px-2 space-y-1">


                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <a class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700"
                            href="route('logout')"
                            onclick="event.preventDefault();
                                                                                                                                                                            this.closest('form').submit();">
                            {{ __('Sign out') }}
                        </a>
                    </form>
                </div>
            </div>
        @else
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                @if (Route::has('login'))
                    <a href="{{ route('login') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">Log
                        in</a>
                @endif

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">Register</a>
                @endif

            </div>
        @endauth
    </div>
</nav>
