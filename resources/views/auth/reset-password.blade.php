<x-guest-layout>
    <div class="auth-card">
        <div class="auth-header">
            <div class="logo">
                <i class="fas fa-lock"></i>
            </div>
            <h1>Reset Password</h1>
            <p>Enter your new password</p>
        </div>

        <div class="auth-body">
            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input id="email" class="form-control" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" placeholder="Enter your email address" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="text-danger" />
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">{{ __('New Password') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" placeholder="Enter your new password" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="text-danger" />
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your new password" />
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="text-danger" />
                </div>

                <button type="submit" class="btn btn-primary">
                    {{ __('Reset Password') }}
                </button>
            </form>
        </div>

        <div class="auth-footer">
            <p>Remember your password? <a href="{{ route('login') }}">Login here</a></p>
        </div>
    </div>
</x-guest-layout>
