@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-12 px-6 text-[#1F2937]">
    <div class="text-[#1F2937] text-center mb-12">
        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">Users Management</h1>
        <p class="text-gray-500 mt-1.5 font-medium">Manage administrator/teacher user accounts</p>
    </div>

    <!-- Error/Success Alerts -->
    @if($errors->any())
        <div class="p-4 mb-6 text-sm text-red-700 bg-red-50 border border-red-100 rounded-2xl shadow-sm max-w-lg mx-auto">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('success'))
        <div class="p-4 mb-6 text-sm text-green-700 bg-green-50 border border-green-100 rounded-2xl shadow-sm max-w-lg mx-auto flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Create User Form Column -->
        <div class="lg:col-span-1 bg-white border border-gray-150 rounded-2xl p-6 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
            <h3 class="text-lg font-bold text-gray-950 mb-6 flex items-center gap-1.5">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                Create New User
            </h3>
            <form id="create-user-form" action="{{ route('users.store') }}" method="POST" class="grid grid-cols-1 gap-5">
                @csrf
                
                <label class="block text-left">
                    <span class="text-gray-700 text-xs font-bold uppercase tracking-wider">Name</span>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        placeholder="Enter name"
                        class="mt-1.5 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-gray-700 px-3.5 py-2.5 border text-sm"
                    />
                </label>

                <label class="block text-left">
                    <span class="text-gray-700 text-xs font-bold uppercase tracking-wider">Email Address</span>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        placeholder="Enter email address"
                        class="mt-1.5 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-gray-700 px-3.5 py-2.5 border text-sm"
                    />
                </label>

                <label class="block text-left">
                    <span class="text-gray-700 text-xs font-bold uppercase tracking-wider">Level</span>
                    <select
                        name="level"
                        required
                        class="mt-1.5 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-gray-700 px-3.5 py-2.5 border text-sm capitalize"
                    >
                        <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>beginner</option>
                        <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>intermediate</option>
                        <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>advanced</option>
                    </select>
                </label>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white p-3.5 rounded-xl font-bold shadow-md hover:bg-blue-700 transition cursor-pointer text-sm"
                >
                    Create User
                </button>
            </form>
        </div>

        <!-- Users List Table Column -->
        <div class="lg:col-span-2 bg-white border border-gray-150 rounded-2xl overflow-hidden shadow-sm">
            <div class="px-6 py-5 bg-slate-50/50 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-1.5">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    All Registered Users
                </h3>
                <span class="text-xs font-semibold text-gray-400 bg-gray-100 border border-gray-200/50 py-1.5 px-3 rounded-lg">
                    Total: {{ count($users) }}
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-left">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-xs font-bold text-gray-400 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Level</th>
                            <th scope="col" class="px-6 py-3.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Created At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/30 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 font-mono">#{{ $user->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                    <span class="inline-flex items-center rounded-lg px-2.5 py-0.5 text-xs font-semibold
                                        @if($user->level === 'beginner') bg-blue-50 text-blue-700 border border-blue-100
                                        @elseif($user->level === 'intermediate') bg-indigo-50 text-indigo-700 border border-indigo-100
                                        @else bg-purple-50 text-purple-700 border border-purple-100
                                        @endif"
                                    >
                                        {{ $user->level }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $user->created_at ? $user->created_at->format('Y-m-d') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400 italic">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('create-user-form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const errorContainer = document.getElementById('error-container');
        if (errorContainer) {
            errorContainer.classList.add('hidden');
            errorContainer.innerHTML = '';
        }

        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            return response.json().then(errData => {
                throw errData;
            });
        })
        .then(data => {
            if (data.redirect) {
                if (typeof navigateTo === 'function') {
                    navigateTo(data.redirect);
                } else {
                    window.location.href = data.redirect;
                }
            }
        })
        .catch(error => {
            console.error('Create user error:', error);
            
            let errorsHtml = '<ul class="list-disc pl-5">';
            if (error.errors) {
                Object.keys(error.errors).forEach(key => {
                    error.errors[key].forEach(msg => {
                        errorsHtml += `<li>${msg}</li>`;
                    });
                });
            } else if (error.message) {
                errorsHtml += `<li>${error.message}</li>`;
            } else {
                errorsHtml += `<li>User creation failed. Please check your inputs and try again.</li>`;
            }
            errorsHtml += '</ul>';

            let container = document.getElementById('error-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'error-container';
                container.className = 'mt-6 max-w-md mx-auto p-4 text-sm text-red-700 bg-red-100 rounded-lg shadow-sm mb-6';
                form.parentNode.insertBefore(container, form);
            }
            container.innerHTML = errorsHtml;
            container.classList.remove('hidden');
        });
    });
});
</script>
@endsection
