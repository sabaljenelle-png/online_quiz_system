<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block font-medium text-sm text-gray-700">
                {{ __('Current Password') }}
            </label>
            <input 
                id="update_password_current_password" 
                name="current_password" 
                type="password" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" 
                style="width: 100%; border: 1px solid #ccc; padding: 8px; border-radius: 4px; margin-top: 4px;"
                autocomplete="current-password" 
            />
            @if($errors->updatePassword->has('current_password'))
                <p class="text-sm text-red-600 mt-2" style="color: red;">
                    {{ $errors->updatePassword->first('current_password') }}
                </p>
            @endif
        </div>

        <div>
            <label for="update_password_password" class="block font-medium text-sm text-gray-700">
                {{ __('New Password') }}
            </label>
            <input 
                id="update_password_password" 
                name="password" 
                type="password" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" 
                style="width: 100%; border: 1px solid #ccc; padding: 8px; border-radius: 4px; margin-top: 4px;"
                autocomplete="new-password" 
            />
            @if($errors->updatePassword->has('password'))
                <p class="text-sm text-red-600 mt-2" style="color: red;">
                    {{ $errors->updatePassword->first('password') }}
                </p>
            @endif
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block font-medium text-sm text-gray-700">
                {{ __('Confirm Password') }}
            </label>
            <input 
                id="update_password_password_confirmation" 
                name="password_confirmation" 
                type="password" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" 
                style="width: 100%; border: 1px solid #ccc; padding: 8px; border-radius: 4px; margin-top: 4px;"
                autocomplete="new-password" 
            />
            @if($errors->updatePassword->has('password_confirmation'))
                <p class="text-sm text-red-600 mt-2" style="color: red;">
                    {{ $errors->updatePassword->first('password_confirmation') }}
                </p>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button 
                type="submit" 
                style="background: #4f46e5; color: white; padding: 8px 16px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
                {{ __('Save') }}
            </button>

            @if (session('status') === 'password-updated')
                <p class="text-sm text-gray-600" style="color: green; margin-left: 10px; display: inline;">
                    {{ __('Saved successfully.') }}
                </p>
            @endif
        </div>
    </form>
</section>