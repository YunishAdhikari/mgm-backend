<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3>Welcome, {{ auth()->user()->name }}</h3>
                <p>Email: {{ auth()->user()->email }}</p>
                <p>Role: {{ auth()->user()->role->name ?? 'No role assigned' }}</p>
                <p>Department: {{ auth()->user()->department->name ?? 'No department assigned' }}</p>
            </div>
            @if(auth()->user()->isAdmin())
                <p>You are an admin.</p>
            @endif
        </div>
    </div>
</x-app-layout>