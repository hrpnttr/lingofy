@extends('layouts.app')

@section('content')
<div class="w-[80%] mx-auto pt-12 pb-16">
    <div class="mt-8 max-w-md mx-auto border border-gray-200 p-8 rounded-xl shadow-md bg-white">
        <form id="reset-password-form" action="{{ route('password.update') }}" method="POST">
            @csrf
            
            <div class="font-bold text-2xl text-[#1F2937] text-center uppercase">
                Reset Your Password
            </div>
            <p class="text-center text-gray-500 mt-4 leading-relaxed">
                Enter your email address and new password below.
            </p>

            <!-- Error Alerts -->
            @if($errors->any())
                <div class="mt-6 p-4 text-sm text-red-700 bg-red-100 rounded-lg shadow-sm">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-8 space-y-6">
                <label class="block">
                    <span class="text-gray-700 font-semibold text-sm">Email Address</span>
                    <input
                        type="email"
                        name="email"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-gray-700 px-3 py-2 border"
                        placeholder="john@example.com"
                        value="{{ old('email') }}"
                    />
                </label>

                <label class="block">
                    <span class="text-gray-700 font-semibold text-sm">New Password</span>
                    <input
                        type="password"
                        name="password"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-gray-700 px-3 py-2 border"
                        placeholder="New Password"
                    />
                </label>
                
                <label class="block">
                    <span class="text-gray-700 font-semibold text-sm">Confirm New Password</span>
                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-gray-700 px-3 py-2 border"
                        placeholder="Confirm New Password"
                    />
                </label>

                <button type="submit" class="w-full mt-6 bg-[#0a48f3] text-white p-3 rounded-lg font-bold shadow-md hover:bg-blue-700 transition">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('reset-password-form');
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
                window.location.href = data.redirect;
            }
        })
        .catch(error => {
            console.error('Reset password error:', error);
            
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
                errorsHtml += `<li>Password reset failed. Please check your inputs and try again.</li>`;
            }
            errorsHtml += '</ul>';

            let container = document.getElementById('error-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'error-container';
                container.className = 'mt-6 max-w-md mx-auto p-4 text-sm text-red-700 bg-red-100 rounded-lg shadow-sm';
                form.parentNode.insertBefore(container, form);
            }
            container.innerHTML = errorsHtml;
            container.classList.remove('hidden');
        });
    });
});
</script>
@endsection
