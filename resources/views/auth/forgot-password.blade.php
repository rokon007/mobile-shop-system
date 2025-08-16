<x-guest-layout>
    <div class="auth-card">
        <div class="auth-header">
            <div class="logo">
                <i class="fas fa-key"></i>
            </div>
            <h1>Forgot Password</h1>
            <p>Reset your password</p>
        </div>

        <div class="auth-body">
            <div class="mb-4 text-sm text-gray-600">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input id="email" class="form-control @error('email') is-invalid @enderror"
                               type="email" name="email" value="{{ old('email') }}"
                               required autofocus placeholder="Enter your email address" />
                    </div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-paper-plane me-2"></i>
                    {{ __('Email Password Reset Link') }}
                </button>
            </form>
        </div>

        <div class="auth-footer">
            <p>Remember your password? <a href="{{ route('login') }}">Login here</a></p>
        </div>
    </div>
</x-guest-layout>
