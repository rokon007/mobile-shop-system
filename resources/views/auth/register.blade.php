<x-guest-layout>
    <div class="auth-card">
        <div class="auth-header">
            <div class="logo">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1>Create Account</h1>
            <p>Mobile Shop Management System</p>
        </div>

        <div class="auth-body">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Name') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>
                        <input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Enter your full name" />
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="text-danger" />
                </div>

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Enter your email address" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="text-danger" />
                </div>

                <!-- Phone -->
                <div class="form-group">
                    <label for="phone" class="form-label">{{ __('Phone') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-phone"></i>
                        </span>
                        <input id="phone" class="form-control" type="text" name="phone" :value="old('phone')" required placeholder="Enter your phone number" />
                    </div>
                    <x-input-error :messages="$errors->get('phone')" class="text-danger" />
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" placeholder="Enter your password" />
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
                        <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password" />
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="text-danger" />
                </div>

                <button type="submit" class="btn btn-primary">
                    {{ __('Register') }}
                </button>
            </form>
        </div>

        <div class="auth-footer">
            <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
        </div>
    </div>
</x-guest-layout>
