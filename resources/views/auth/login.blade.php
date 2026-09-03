@extends('layouts.app')

@section('content')
<div class="w-[80%] mx-auto pt-12 pb-16">
    <div class="font-bold text-3xl text-[#1F2937] text-center mt-[1.5rem]">
        LOGIN
    </div>
    <p class="text-center text-gray-500 mt-2">
        Please fill all fields below to log in
    </p>

    <!-- Error Alerts -->
    @if($errors->has('error'))
        <div class="mt-6 max-w-md mx-auto p-4 text-sm text-red-700 bg-red-100 rounded-lg shadow-sm">
            {{ $errors->first('error') }}
        </div>
    @endif
    @if(session('status'))
        <div class="mt-6 max-w-md mx-auto p-4 text-sm text-green-700 bg-green-100 rounded-lg shadow-sm">
            {{ session('status') }}
        </div>
    @endif

    <div class="mt-8 max-w-md mx-auto border border-gray-200 p-8 rounded-xl shadow-md bg-white">
        <form id="login-form" action="{{ route('login') }}" method="POST" class="grid grid-cols-1 gap-6">
            @csrf

            <label class="block">
                <span class="text-gray-700 font-semibold text-sm">Email address</span>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-gray-700 px-3 py-2 border"
                    placeholder="john@example.com"
                    required
                />
            </label>

            <label class="block">
                <span class="text-gray-700 font-semibold text-sm">Password</span>
                <input
                    type="password"
                    name="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-gray-700 px-3 py-2 border"
                    placeholder="Password"
                    required
                />
            </label>

            <button
                type="submit"
                class="w-full bg-[#0a48f3] text-white p-3 rounded-lg font-bold shadow-md hover:bg-blue-700 transition"
            >
                Login
            </button>

            <div class="mt-1">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Or</span>
                    </div>
                </div>

                <div class="mt-6 flex justify-between gap-4">
                    <a href="{{ route('register') }}" class="w-full text-center py-2.5 px-4 border border-blue-600 rounded-lg shadow-sm text-sm font-semibold text-blue-600 bg-white hover:bg-blue-50 transition">
                        Register
                    </a>
                    <a href="{{ route('password.request') }}" class="w-full text-center py-2.5 px-4 border border-blue-600 rounded-lg shadow-sm text-sm font-semibold text-blue-600 bg-white hover:bg-blue-50 transition">
                        Forgot Password
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('login-form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnHtml = submitBtn ? submitBtn.innerHTML : 'Login';

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-70', 'cursor-not-allowed', 'pointer-events-none');
            submitBtn.innerHTML = `
                <span class="inline-flex items-center justify-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Logging in...</span>
                </span>
            `;
        }
        
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
            if (data && data.redirect) {
                window.location.href = data.redirect;
            } else {
                window.location.href = "{{ route('profile') }}";
            }
        })
        .catch(error => {
            console.error('Login error:', error);
            
            // Re-enable button on error
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-70', 'cursor-not-allowed', 'pointer-events-none');
                submitBtn.innerHTML = originalBtnHtml;
            }

            let errorsHtml = '<ul class="list-disc pl-5">';
            if (error && error.errors) {
                Object.keys(error.errors).forEach(key => {
                    error.errors[key].forEach(msg => {
                        errorsHtml += `<li>${msg}</li>`;
                    });
                });
            } else if (error && error.message) {
                errorsHtml += `<li>${error.message}</li>`;
            } else {
                errorsHtml += `<li>Login failed. Please check your credentials and try again.</li>`;
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
