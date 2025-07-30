<x-guest-layout>
    <div class="auth-card">
        <div class="auth-header">
            <div class="logo">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h1>Confirm Password</h1>
            <p>Security verification</p>
        </div>

        <div class="auth-body">
            <div class="mb-4">
                {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
            </div>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

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

                <button type="submit" class="btn btn-primary">
                    {{ __('Confirm') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
