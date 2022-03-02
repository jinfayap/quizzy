<x-app-layout>
    <div class="flex min-h-screen">
        <div class="sidebar bg-gray-800  px-4 py-2 rounded">

            <div class=" flex flex-col space-y-4 ml-2">
                <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                <p class="font-medium text-white uppercase">Role & Permissions</p>
                <a href="{{ route('admin.role') }}"
                    class="px-3 py-2 rounded-md text-sm font-medium {{ Route::is('admin.role') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Role</a>

                <a href="{{ route('admin.permission') }}"
                    class="px-3 py-2 rounded-md text-sm font-medium {{ Route::is('admin.permission')? 'bg-gray-900 text-white': 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Permission</a>

                <a href="{{ route('admin.role-permission') }}"
                    class="px-3 py-2 rounded-md text-sm font-medium {{ Route::is('admin.role-permission')? 'bg-gray-900 text-white': 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Role
                    Permission</a>

                <a href="{{ route('admin.user-role-permission') }}"
                    class="px-3 py-2 rounded-md text-sm font-medium {{ Route::is('admin.user-role-permission')? 'bg-gray-900 text-white': 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">User
                    Role Permission</a>
            </div>
        </div>

        <div class="main-content  min-w-3/4  px-4 py-2">
            Choose one from the menu
        </div>
    </div>
</x-app-layout>
