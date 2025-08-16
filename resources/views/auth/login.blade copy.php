<x-guest-layout>
    <div class="auth-card">
        <div class="auth-header">
            <div class="logo">
                <i class="fas fa-mobile-alt"></i>
            </div>
            <h1>Mobile Shop</h1>
            <p>Management System</p>
        </div>

        <div class="auth-body">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Enter your email address" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="text-danger" />
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="text-danger" />
                </div>

                <!-- Remember Me -->
                <div class="remember-me">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me">{{ __('Remember me') }}</label>
                </div>

                <!-- Forgot Password -->
                @if (Route::has('password.request'))
                    <div class="forgot-password">
                        <a href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    </div>
                @endif

                <button type="submit" class="btn btn-primary">
                    {{ __('Log in') }}
                </button>
            </form>
        </div>

        @if (Route::has('register'))
            <div class="auth-footer">
                <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
            </div>
        @endif
    </div>

    <!-- Demo Credentials Card -->
    <div class="auth-card mt-3">
        <div class="auth-body">
            <h6 class="text-center mb-3">Demo Login Credentials</h6>
            <div class="row">
                <div class="col-12 mb-2">
                    <small><strong>Admin:</strong> admin@mobileshop.com / password</small>
                </div>
                <div class="col-12 mb-2">
                    <small><strong>Manager:</strong> manager@mobileshop.com / password</small>
                </div>
                <div class="col-12 mb-2">
                    <small><strong>Sales:</strong> sales@mobileshop.com / password</small>
                </div>
                <div class="col-12 mb-2">
                    <small><strong>Accountant:</strong> accountant@mobileshop.com / password</small>
                </div>
                <div class="col-12">
                    <small><strong>Inventory:</strong> inventory@mobileshop.com / password</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>
    <!-- Or use CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</x-guest-layout>
