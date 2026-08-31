@extends('layouts.app')

@section('content')
<div class="w-[80%] mx-auto pt-12 pb-16">
    @if(session('success'))
        <!-- Success Mode -->
        <div class="mt-10 mb-[2.5rem] max-w-md mx-auto border border-gray-200 p-8 rounded-xl shadow-md bg-white">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-green-600">Success!</h2>
                <p class="mt-4 text-gray-600 leading-relaxed">
                    An email has been sent to your address with instructions to
                    reset your password.
                </p>
                <a
                    href="{{ route('password.reset') }}"
                    class="w-full inline-block text-center mt-6 bg-gray-600 text-white p-3 rounded-lg font-bold shadow-md hover:bg-gray-700 transition"
                >
                    Close
                </a>
            </div>
        </div>
    @else
        <!-- Request Form Mode -->
        <div class="mt-10 mb-[0.5rem] max-w-md mx-auto border border-gray-200 p-8 rounded-xl shadow-md bg-white">
            <form id="forgot-password-form" action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="font-bold text-2xl text-[#1F2937] text-center uppercase">
                    Forgot Your Password?
                </div>
                <p class="text-center text-gray-500 mt-4 leading-relaxed">
                    Enter your email address below and we will send you a link to
                    reset your password.
                </p>
                <div class="mt-8">
                    <label class="block">
                        <span class="text-gray-700 font-semibold text-sm">Email address</span>
                        <input
                            type="email"
                            name="email"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-gray-700 px-3 py-2 border"
                            placeholder="john@example.com"
                            value="{{ old('email') }}"
                            required
                        />
                    </label>
                    
                    @if($errors->has('error'))
                        <p class="text-red-500 text-sm mt-2 font-medium">{{ $errors->first('error') }}</p>
                    @endif
                    
                    <button
                        type="submit"
                        class="w-full mt-6 bg-[#0a48f3] text-white p-3 rounded-lg font-bold shadow-md hover:bg-blue-700 transition"
                    >
                        Send Reset Link
                    </button>
                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline font-semibold">
                            Back to Login
                        </a>
                    </div>
                </div>
            </form>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('forgot-password-form');
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
            const parent = form.parentNode;
            parent.innerHTML = `
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-green-600">Success!</h2>
                    <p class="mt-4 text-gray-600 leading-relaxed">
                        An email has been sent to your address with instructions to
                        reset your password.
                    </p>
                    <a
                        href="{{ route('password.reset') }}"
                        class="w-full inline-block text-center mt-6 bg-gray-600 text-white p-3 rounded-lg font-bold shadow-md hover:bg-gray-700 transition"
                    >
                        Close
                    </a>
                </div>
            `;
        })
        .catch(error => {
            console.error('Forgot password error:', error);
            let errorsHtml = '';
            if (error.errors && error.errors.error) {
                errorsHtml = error.errors.error[0];
            } else if (error.message) {
                errorsHtml = error.message;
            } else {
                errorsHtml = 'Email not found. Please try again.';
            }

            let container = document.getElementById('error-container');
            if (!container) {
                container = document.createElement('p');
                container.id = 'error-container';
                container.className = 'text-red-500 text-sm mt-2 font-medium';
                form.querySelector('input[type="email"]').parentNode.appendChild(container);
            }
            container.innerHTML = errorsHtml;
            container.classList.remove('hidden');
        });
    });
});
</script>
@endsection
